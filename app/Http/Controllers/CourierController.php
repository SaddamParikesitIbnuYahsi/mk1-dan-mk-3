<?php
namespace App\Http\Controllers;

use App\Models\Courier;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class CourierController extends Controller
{
    public function index(): JsonResponse
    {
        $couriers = Courier::all();

        return response()->json([
            'success' => true,
            'message' => 'Successfully retrieved courier list',
            'data' => $couriers
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'Name' => 'required|string|max:255',
            'LicenseNumber' => 'nullable|string|max:255|unique:couriers,LicenseNumber',
            'VehicleType' => 'nullable|string|max:255',
            'PhoneNumber' => 'nullable|string|max:20',
        ]);

        $courier = Courier::create([
            'Name' => $validated['Name'],
            'LicenseNumber' => $validated['LicenseNumber'] ?? null,
            'VehicleType' => $validated['VehicleType'] ?? null,
            'PhoneNumber' => $validated['PhoneNumber'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Courier created successfully',
            'data' => $courier
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $courier = Courier::findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Successfully retrieved courier',
            'data' => $courier
        ]);
    }

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
                Rule::unique('couriers', 'LicenseNumber')->ignore($courier->CourierID, 'CourierID')
            ],
            'vehicle_type' => 'sometimes|nullable|string|max:255',
            'phone_number' => 'sometimes|nullable|string|max:20',
        ]);

        $updateData = [];
        if ($request->has('name')) $updateData['Name'] = $validated['name'];
        if ($request->has('license_number')) $updateData['LicenseNumber'] = $validated['license_number'];
        if ($request->has('vehicle_type')) $updateData['VehicleType'] = $validated['vehicle_type'];
        if ($request->has('phone_number')) $updateData['PhoneNumber'] = $validated['phone_number'];

        $courier->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Courier updated successfully',
            'data' => $courier
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $courier = Courier::findOrFail($id);

        try {
            $courier->delete();
            return response()->json([
                'success' => true,
                'message' => 'Courier deleted successfully'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete courier. It might be associated with existing shipments.'
            ], 409);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the courier.'
            ], 500);
        }
    }
}
