<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class ShipmentController extends Controller
{
    public function index(): JsonResponse
    {
        $shipments = Shipment::with(['package', 'vendor', 'courier'])->get();

        return response()->json([
            'success' => true,
            'message' => 'Successfully retrieved shipment list',
            'data' => $shipments
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'vendor_id' => 'required|exists:vendors,id',
            'courier_id' => 'required|exists:couriers,id',
            'tracking_number' => 'required|string|max:255|unique:shipments,tracking_number',
            'delivery_date' => 'required|date',
            'status' => 'required|string|max:100',
        ]);

        $shipment = Shipment::create($validated);
        $shipment->load(['package', 'vendor', 'courier']);

        return response()->json([
            'success' => true,
            'message' => 'Shipment created successfully',
            'data' => $shipment
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $shipment = Shipment::with(['package', 'vendor', 'courier'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Shipment retrieved successfully',
            'data' => $shipment
        ]);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $shipment = Shipment::findOrFail($id);

        $validated = $request->validate([
            'package_id' => 'sometimes|required|exists:packages,id',
            'vendor_id' => 'sometimes|required|exists:vendors,id',
            'courier_id' => 'sometimes|required|exists:couriers,id',
            'tracking_number' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('shipments', 'tracking_number')->ignore($shipment->id),
            ],
            'delivery_date' => 'sometimes|nullable|date',
            'status' => 'sometimes|nullable|string|max:100',
        ]);

        $shipment->update($validated);
        $shipment->load(['package', 'vendor', 'courier']);

        return response()->json([
            'success' => true,
            'message' => 'Shipment updated successfully',
            'data' => $shipment
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $shipment = Shipment::findOrFail($id);

        try {
            $shipment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Shipment deleted successfully'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete shipment. It might be associated with other data.'
            ], 409);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the shipment.'
            ], 500);
        }
    }
}
