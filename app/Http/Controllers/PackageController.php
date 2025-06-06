<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PackageController extends Controller
{
    public function index(): JsonResponse
    {
        // Eager load customer dan sender
        $packages = Package::with(['customer', 'sender'])->get();

        return response()->json([
            'success' => true,
            'message' => 'Successfully retrieved package list',
            'data' => $packages
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'sender_id' => 'required|exists:senders,id',
            'customer_id' => 'required|exists:customers,id',
            'weight' => 'required|numeric',
            'package_type' => 'required|string|max:50',
            'shipping_cost' => 'required|numeric',
            'receipt_date' => 'required|date',
        ]);

        $package = Package::create($validated);

        // Muat relasi untuk response
        $package->load(['customer', 'sender']);

        return response()->json([
            'success' => true,
            'message' => 'Package created successfully',
            'data' => $package
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $package = Package::with(['customer', 'sender'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Package retrieved successfully',
            'data' => $package
        ]);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $package = Package::findOrFail($id);

        $validated = $request->validate([
            'sender_id' => 'sometimes|required|exists:senders,id',
            'customer_id' => 'sometimes|required|exists:customers,id',
            'weight' => 'sometimes|required|numeric',
            'package_type' => 'sometimes|required|string|max:50',
            'shipping_cost' => 'sometimes|required|numeric',
            'receipt_date' => 'sometimes|required|date',
        ]);

        $package->update($validated);

        // Muat relasi untuk response
        $package->load(['customer', 'sender']);

        return response()->json([
            'success' => true,
            'message' => 'Package updated successfully',
            'data' => $package
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $package = Package::findOrFail($id);
        $package->delete();

        return response()->json([
            'success' => true,
            'message' => 'Package deleted successfully'
        ]);
    }
}
