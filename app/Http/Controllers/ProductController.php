<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\PriceResolutionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    protected $priceResolutionService;

    public function __construct(PriceResolutionService $priceResolutionService)
    {
        $this->priceResolutionService = $priceResolutionService;
    }

    /**
     * Display a paginated list of published products.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $user = User::find($request->user_id);

        $productsPerPage = $request->input('per_page', 25);

        if ($user !== null) {
            $productsQuery = Product::withFinalPrice($user);
        } else {
            $productsQuery = Product::where('published', true);
        }

        $products = $productsQuery->paginate($productsPerPage);

        return ProductResource::collection($products);
    }

    /**
     * Display the specified published product.
     *
     * @param Request $request
     * @param string $product_SKU
     * @return ProductResource | JsonResponse
     */
    public function show(Request $request, string $product_SKU): ProductResource | JsonResponse
    {
        $user = User::find($request->user_id);
        try {
            $product = Product::where('SKU', $product_SKU)->where('published', true)->firstOrFail();
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['message' => 'Product not found.'], 404);
        }
        $resolvedPrice = $this->priceResolutionService->getProductPriceForUser($product, $user);

        return new ProductResource(resource: $product->setAttribute('final_price', $resolvedPrice));
    }

    /**
     * Filter published products based on various criteria, including name, category, price range, and sorting.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function filter(Request $request): AnonymousResourceCollection
    {
        $user = User::find($request->user_id);
        $productsPerPage = $request->input('per_page', 30);


        if ($user !== null) {
            $query = Product::withFinalPrice($user);
        } else {
            $query = Product::where('published', true);
        }

        // Filter by name
        if ($request->filled('name')) {
            $query->where('products.name', 'like', '%' . $request->input('name') . '%');
        }

        // Filter by category and its subcategories
        if ($request->has('category_id')) {
            $category = Category::find($request->input('category_id'));
            if ($category) {
                $subcategoryIds = $category->getAllSubcategoryIds();
                $query->whereHas('categories', function ($subQuery) use ($subcategoryIds) {
                    $subQuery->whereIn('category_id', $subcategoryIds);
                });
            }
        }

        // Filter by price
        if ($user !== null) {
            if ($request->filled('min_price') && $request->filled('max_price')) {
                $query->whereRaw('COALESCE(contract_lists.price, price_list_items.price, products.price) BETWEEN ? AND ?', [
                    $request->input('min_price'),
                    $request->input('max_price')
                ]);
            } elseif ($request->filled('min_price')) {
                $query->whereRaw('COALESCE(contract_lists.price, price_list_items.price, products.price) >= ?', [$request->input('min_price')]);
            } elseif ($request->filled('max_price')) {
                $query->whereRaw('COALESCE(contract_lists.price, price_list_items.price, products.price) <= ?', [$request->input('max_price')]);
            }
        } else {
            if ($request->filled('min_price')) {
                $query->where('products.price', '>=', $request->input('min_price'));
            }
            if ($request->filled('max_price')) {
                $query->where('products.price', '<=', $request->input('max_price'));
            }
        }

        // Sorting
        if ($request->filled('sort_by')) {
            $sortBy = $request->input('sort_by');
            $sortOrder = $request->input('sort_order', 'asc');

            if ($sortBy === 'name') {
                $query->orderBy('products.name', $sortOrder);
            } elseif ($sortBy === 'price') {
                if ($user === null) {
                    $query->orderBy('products.price', $sortOrder);
                } else {
                    $query->orderBy('final_price', $sortOrder);
                }
            }
        }

        $products = $query->paginate($productsPerPage);

        return ProductResource::collection($products);
    }
}
