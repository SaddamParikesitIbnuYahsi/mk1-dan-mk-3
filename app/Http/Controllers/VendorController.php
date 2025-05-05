<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class VendorController extends Controller
{
    /**
     * @OA\Schema(
     *     schema="Vendor",
     *     type="object",
     *     @OA\Property(property="VendorID", type="integer", example=1),
     *     @OA\Property(property="BusinessName", type="string", example="CV Maju Jaya"),
     *     @OA\Property(property="LicenseNumber", type="string", example="LIC-123456"),
     *     @OA\Property(property="Address", type="string", example="Jl. Anggrek No. 10")
     * )
     */

    /**
     * @OA\Get(
     *     path="/api/vendors",
     *     tags={"Vendors"},
     *     summary="Get list of vendors",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Vendor")
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $vendors = Vendor::all();
        return response()->json($vendors);
    }

    /**
     * @OA\Post(
     *     path="/api/vendors",
     *     tags={"Vendors"},
     *     summary="Store a new vendor",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="business_name", type="string", example="CV Maju Jaya"),
     *             @OA\Property(property="license_number", type="string", example="LIC-123456"),
     *             @OA\Property(property="address", type="string", example="Jl. Anggrek No. 10")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Vendor created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Vendor")
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/vendors/{id}",
     *     tags={"Vendors"},
     *     summary="Get vendor by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vendor retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Vendor")
     *     )
     * )
     */
    public function show($id): JsonResponse
    {
        $vendor = Vendor::findOrFail($id);
        return response()->json($vendor);
    }

    /**
     * @OA\Put(
     *     path="/api/vendors/{id}",
     *     tags={"Vendors"},
     *     summary="Update a vendor by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="business_name", type="string", example="CV Baru Jaya"),
     *             @OA\Property(property="license_number", type="string", example="LIC-654321"),
     *             @OA\Property(property="address", type="string", example="Jl. Kenanga No. 2")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vendor updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Vendor")
     *     )
     * )
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
     * @OA\Delete(
     *     path="/api/vendors/{id}",
     *     tags={"Vendors"},
     *     summary="Delete a vendor by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vendor deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Vendor deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Failed to delete vendor due to relationship",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Failed to delete vendor. It might be associated with existing shipments.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="An error occurred while deleting the vendor.")
     *         )
     *     )
     * )
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
