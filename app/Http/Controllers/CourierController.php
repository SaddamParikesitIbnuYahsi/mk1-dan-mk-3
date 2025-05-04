<?php

namespace App\Http\Controllers;

use App\Models\Courier;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class CourierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $couriers = Courier::all();
        return response()->json($couriers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'license_number' => 'nullable|string|max:255|unique:couriers,LicenseNumber',
            'vehicle_type' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
        ]);

        $courier = Courier::create([
            'Name' => $validated['name'],
            'LicenseNumber' => $validated['license_number'] ?? null,
            'VehicleType' => $validated['vehicle_type'] ?? null,
            'PhoneNumber' => $validated['phone_number'] ?? null,
        ]);

        return response()->json($courier, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $courier = Courier::findOrFail($id);
        return response()->json($courier);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $courier = Courier::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'license_number' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
                // Pastikan nama kolom PK benar (CourierID)
                Rule::unique('couriers', 'LicenseNumber')->ignore($courier->CourierID, 'CourierID')
            ],
            'vehicle_type' => 'sometimes|nullable|string|max:255',
            'phone_number' => 'sometimes|nullable|string|max:20',
        ]);

        $updateData = [];
        if ($request->has('name')) {
            $updateData['Name'] = $validated['name'];
        }
        if ($request->has('license_number')) {
            $updateData['LicenseNumber'] = $validated['license_number'];
        }
        if ($request->has('vehicle_type')) {
            $updateData['VehicleType'] = $validated['vehicle_type'];
        }
        if ($request->has('phone_number')) {
            $updateData['PhoneNumber'] = $validated['phone_number'];
        }

        if (!empty($updateData)) {
            $courier->update($updateData);
        }

        return response()->json($courier);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $courier = Courier::findOrFail($id);
        try {
            $courier->delete();
            return response()->json(['message' => 'Courier deleted successfully']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => 'Failed to delete courier. It might be associated with existing shipments.'], 409);
        } catch (\Exception $e) {
            report($e);
            return response()->json(['message' => 'An error occurred while deleting the courier.'], 500);
        }
    }
}
