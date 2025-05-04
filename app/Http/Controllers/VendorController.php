<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $vendors = Vendor::all();
        return response()->json($vendors);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'license_number' => 'nullable|string|max:255|unique:vendors,LicenseNumber',
            'address' => 'nullable|string',
        ]);

        $vendor = Vendor::create([
            'BusinessName' => $validated['business_name'],
            'LicenseNumber' => $validated['license_number'] ?? null,
            'Address' => $validated['address'] ?? null,
        ]);

        return response()->json($vendor, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $vendor = Vendor::findOrFail($id);
        return response()->json($vendor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $vendor = Vendor::findOrFail($id);

        $validated = $request->validate([
            'business_name' => 'sometimes|required|string|max:255',
            'license_number' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
                // Pastikan nama kolom PK benar (VendorID)
                Rule::unique('vendors', 'LicenseNumber')->ignore($vendor->VendorID, 'VendorID')
            ],
            'address' => 'sometimes|nullable|string',
        ]);

        $updateData = [];
        if ($request->has('business_name')) {
            $updateData['BusinessName'] = $validated['business_name'];
        }
        if ($request->has('license_number')) {
            $updateData['LicenseNumber'] = $validated['license_number'];
        }
        if ($request->has('address')) {
            $updateData['Address'] = $validated['address'];
        }

        if (!empty($updateData)) {
            $vendor->update($updateData);
        }

        return response()->json($vendor);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $vendor = Vendor::findOrFail($id);
        try {
            $vendor->delete();
            return response()->json(['message' => 'Vendor deleted successfully']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => 'Failed to delete vendor. It might be associated with existing shipments.'], 409);
        } catch (\Exception $e) {
            report($e);
            return response()->json(['message' => 'An error occurred while deleting the vendor.'], 500);
        }
    }
}
