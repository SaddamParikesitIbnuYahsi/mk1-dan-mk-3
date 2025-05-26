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
        return response()->json($shipments);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'vendor_id' => 'required|exists:vendors,id',
            'courier_id' => 'required|exists:couriers,id',
            'tracking_number' => 'required|string|unique:shipments,tracking_number|max:255',
            'delivery_date' => 'nullable|date',
            'status' => 'nullable|string|max:100',
        ]);

        $shipment = Shipment::create($validated);

        return response()->json($shipment, 201);
    }

    public function show($id): JsonResponse
    {
        $shipment = Shipment::with(['package', 'vendor', 'courier'])->findOrFail($id);
        return response()->json($shipment);
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
                Rule::unique('shipments')->ignore($shipment->id),
            ],
            'delivery_date' => 'sometimes|nullable|date',
            'status' => 'sometimes|nullable|string|max:100',
        ]);

        $shipment->update($validated);

        return response()->json($shipment);
    }

    public function destroy($id): JsonResponse
    {
        $shipment = Shipment::findOrFail($id);

        try {
            $shipment->delete();
            return response()->json(['message' => 'Shipment deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete shipment'], 500);
        }
    }
}