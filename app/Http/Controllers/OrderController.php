<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\User;
use App\Repositories\OrderRepository;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    protected $orderService;
    protected $orderRepository;


    public function __construct(OrderService $orderService, OrderRepository $orderRepository)
    {
        $this->orderService = $orderService;
        $this->orderRepository = $orderRepository;
    }

    /**
     * Store a new order.
     *
     * @param StoreOrderRequest $request
     * @return JsonResponse
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        $user = User::find($request->user_id);

        $orderItemsValid = $this->orderService->checkOrderItemsValidity($request->products, $user);

        if (!$orderItemsValid) {
            return response()->json([
                'error' => 'One or more product information is incorrect.',
            ], 422);
        }

        $orderAmountCorrect = $this->orderService->checkOrderAmount(
            $request->products,
            $request->total_amount,
            $user
        );

        if (!$orderAmountCorrect) {
            return response()->json([
                'error' => 'Total amount for this order is incorrect.',
            ], 422);
        }

        $orderData = [
            'name' => $request->name,
            'telephone_number' => $request->telephone_number,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'user_id' => $request->user_id,
            'order_date' => now(),
            'total_amount' => $request->total_amount,
        ];

        $order = $this->orderRepository->create($orderData, $request->products);

        return response()->json([
            'message' => 'Order sucessfully created!',
            'data' => [
                'order_id' => $order->id,
            ]
        ]);
    }
}
