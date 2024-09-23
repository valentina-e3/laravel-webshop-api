<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\PriceResolutionService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{

    protected $priceResolutionService;

    public function __construct(PriceResolutionService $priceResolutionService)
    {
        $this->priceResolutionService = $priceResolutionService;
    }

    /**
     * Display a paginated list of products belonging to a category.
     *
     * @param Request $request
     * @param Category $category
     * @return AnonymousResourceCollection
     */
    public function showProducts(Request $request, Category $category): AnonymousResourceCollection
    {
        $user = User::find($request->user_id);

        $productsPerPage = $request->input('per_page', 25);

        $categoryIds = $category->getAllSubcategoryIds();

        if ($user !== null) {
            $productsQuery = Product::withFinalPrice($user);
        } else {
            $productsQuery = Product::where('published', true);
        }

        $products = $productsQuery->whereHas('categories', function ($query) use ($categoryIds) {
            $query->whereIn('category_id', $categoryIds);
        })
            ->distinct()
            ->paginate($productsPerPage);

        return ProductResource::collection($products);
    }
}
