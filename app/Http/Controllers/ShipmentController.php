<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class ShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        // Eager load relasi
        $shipments = Shipment::with(['package', 'vendor', 'courier'])->get();
        return response()->json($shipments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Gunakan nama field request snake_case
            'package_id' => 'required|integer|exists:packages,PackageID',
            'vendor_id' => 'required|integer|exists:vendors,VendorID',
            'courier_id' => 'required|integer|exists:couriers,CourierID',
            'tracking_number' => 'required|string|max:255|unique:shipments,TrackingNumber',
            'delivery_date' => 'nullable|date_format:Y-m-d',
            'status' => 'required|string|max:255',
        ]);

        // Mapping ke nama kolom Model PascalCase
        $shipment = Shipment::create([
            'PackageID' => $validated['package_id'],
            'VendorID' => $validated['vendor_id'],
            'CourierID' => $validated['courier_id'],
            'TrackingNumber' => $validated['tracking_number'],
            'DeliveryDate' => $validated['delivery_date'] ?? null,
            'Status' => $validated['status'],
        ]);

        // Load relasi setelah dibuat
        $shipment->load(['package', 'vendor', 'courier']);
        return response()->json($shipment, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        // Eager load relasi
        $shipment = Shipment::with(['package', 'vendor', 'courier'])->findOrFail($id);
        return response()->json($shipment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $shipment = Shipment::findOrFail($id);

        $validated = $request->validate([
            // FK biasanya tidak diupdate
            'tracking_number' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                 // Pastikan nama kolom PK benar (ShipmentID)
                Rule::unique('shipments', 'TrackingNumber')->ignore($shipment->ShipmentID, 'ShipmentID')
            ],
            'delivery_date' => 'sometimes|nullable|date_format:Y-m-d',
            'status' => 'sometimes|required|string|max:255',
        ]);

        $updateData = [];
        if ($request->has('tracking_number')) {
            $updateData['TrackingNumber'] = $validated['tracking_number'];
        }
        if ($request->has('delivery_date')) {
            $updateData['DeliveryDate'] = $validated['delivery_date'];
        }
        if ($request->has('status')) {
            $updateData['Status'] = $validated['status'];
        }


        if (!empty($updateData)) {
            $shipment->update($updateData);
        }

        // Load relasi lagi untuk response update terbaru
        $shipment->load(['package', 'vendor', 'courier']);
        return response()->json($shipment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $shipment = Shipment::findOrFail($id);
        try {
            $shipment->delete();
            return response()->json(['message' => 'Shipment deleted successfully']);
        } catch (\Exception $e) {
            // Tangani error umum (constraint error tidak umum di sini kecuali ada relasi lain)
            report($e);
            return response()->json(['message' => 'An error occurred while deleting the shipment.'], 500);
        }
    }
}
