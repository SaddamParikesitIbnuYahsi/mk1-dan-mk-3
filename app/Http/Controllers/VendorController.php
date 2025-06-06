<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class VendorController extends Controller
{
    public function index(): JsonResponse
    {
        $vendors = Vendor::all();

        return response()->json([
            'success' => true,
            'message' => 'Successfully retrieved vendor list',
            'data' => $vendors
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'bussines_name' => 'required|string|max:255',
            'license_number' => 'required|string|max:255|unique:vendors,license_number',
            'address' => 'required|string|max:255',
        ]);

        $vendor = Vendor::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Vendor created successfully',
            'data' => $vendor
        ], 201); // pastikan 201 Created
    }

    public function show($id): JsonResponse
    {
        $vendor = Vendor::findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Vendor retrieved successfully',
            'data' => $vendor
        ]);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $vendor = Vendor::findOrFail($id);

        $validated = $request->validate([
            'bussines_name' => 'sometimes|required|string|max:255',
            'license_number' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('vendors')->ignore($vendor->id),
            ],
            'address' => 'sometimes|required|string|max:255',
        ]);

        $vendor->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Vendor updated successfully',
            'data' => $vendor
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $vendor = Vendor::findOrFail($id);

        try {
            $vendor->delete();

            return response()->json([
                'success' => true,
                'message' => 'Vendor deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete vendor'
            ], 500);
        }
    }
}
