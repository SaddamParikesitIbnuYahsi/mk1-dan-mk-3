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
            'LicenseNumber' => 'nullable|string|max:255|unique:couriers,license_number',
            'VehicleType' => 'nullable|string|max:255',
            'PhoneNumber' => 'nullable|string|max:20',
        ]);

        $courier = Courier::create([
            'name' => $validated['Name'],
            'license_number' => $validated['LicenseNumber'] ?? null,
            'vehicle_type' => $validated['VehicleType'] ?? null,
            'phone_number' => $validated['PhoneNumber'] ?? null,
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
            'Name' => 'sometimes|required|string|max:255',
            'LicenseNumber' => [
                'sometimes',
                'nullable',
                'string',
                'max:255',
                Rule::unique('couriers', 'license_number')->ignore($courier->courier_id, 'courier_id')
            ],
            'VehicleType' => 'sometimes|nullable|string|max:255',
            'PhoneNumber' => 'sometimes|nullable|string|max:20',
        ]);

        $courier->update([
            'name' => $validated['Name'] ?? $courier->name,
            'license_number' => $validated['LicenseNumber'] ?? $courier->license_number,
            'vehicle_type' => $validated['VehicleType'] ?? $courier->vehicle_type,
            'phone_number' => $validated['PhoneNumber'] ?? $courier->phone_number,
        ]);

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