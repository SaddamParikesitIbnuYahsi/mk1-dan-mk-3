<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index() {
        $orders = Order::all();
        return response()->json([
            'status' => 200,
            'message' => 'Order retrieved succesfully',
            'data' => $orders,
        ], 200);
    }

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
        'data' => $orders,
    ], 200);
    }

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
