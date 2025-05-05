<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * @OA\Get(
     *     path="/orders",
     *     summary="Get all orders",
     *     tags={"Orders"},
     *     @OA\Response(
     *         response=200,
     *         description="Order retrieved successfully"
     *     )
     * )
     */
    public function index() {
        $orders = Order::all();
        return response()->json([
            'status' => 200,
            'message' => 'Order retrieved succesfully',
            'data' => $orders,
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/orders",
     *     summary="Create a new order",
     *     tags={"Orders"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"customer_id", "vendor_id", "courier_id", "tracking_number", "status"},
     *             @OA\Property(property="customer_id", type="string"),
     *             @OA\Property(property="vendor_id", type="string"),
     *             @OA\Property(property="courier_id", type="string"),
     *             @OA\Property(property="tracking_number", type="string"),
     *             @OA\Property(property="status", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully"
     *     )
     * )
     */
    public function store(Request $request) {
        $request->validate([
            'customer_id' => 'required|string|max:255',
            'vendor_id' => 'required|string|max:255',
            'courier_id' => 'required|string|max:255',
            'tracking_number' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        $orders = Order::create($request->all());
        
        return response()->json([
            'status' => 201,
            'message' => 'Order created succesfully.',
            'data' => $orders
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/orders/{id}",
     *     summary="Get a specific order",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order retrieved successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found"
     *     )
     * )
     */
    public function show($id) {
        $orders = Order::find($id);

        if (!$orders) {
            return response()->json([
                'status' => 404,
                'message' => 'Order not found.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Order retrieved successfully.',
            'data' => $ord,
        ], 200);
    }

    /**
     * @OA\Put(
     *     path="/orders/{id}",
     *     summary="Update an order",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"customer_id", "vendor_id", "courier_id", "tracking_number", "status"},
     *             @OA\Property(property="customer_id", type="string"),
     *             @OA\Property(property="vendor_id", type="string"),
     *             @OA\Property(property="courier_id", type="string"),
     *             @OA\Property(property="tracking_number", type="string"),
     *             @OA\Property(property="status", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found"
     *     )
     * )
     */
    public function update(Request $request, $id) {
        $orders = Order::find($id);

        if (!$orders) {
            return response()->json([
                'status' => 404,
                'message' => 'Order not found.',
                'data' => null
            ], 404);
        }

        $request->validate([
            'customer_id' => 'required|string|max:255',
            'vendor_id' => 'required|string|max:255',
            'courier_id' => 'required|string|max:255',
            'tracking_number' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);
        $orders->update($request->all());

        return response()->json([
            'status' => 200,
            'message' => 'Order updated successfully.',
            'data' => $orders
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/orders/{id}",
     *     summary="Delete an order",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found"
     *     )
     * )
     */
    public function destroy($id) {
        $orders = Order::find($id);

        if (!$orders) {
            return response()->json([
                'status' => 404,
                'message' => 'Order not found.',
                'data' => null
            ], 404);
        }

        $orders->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Order deleted successfully.',
            'data' => null
        ], 200);
    }
}
