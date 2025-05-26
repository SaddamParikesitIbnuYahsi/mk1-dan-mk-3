<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

/**
 * @OA\Schema(
 *     schema="Shipment",
 *     type="object",
 *     @OA\Property(property="ShipmentID", type="integer", example=1),
 *     @OA\Property(property="PackageID", type="integer", example=1),
 *     @OA\Property(property="VendorID", type="integer", example=2),
 *     @OA\Property(property="CourierID", type="integer", example=3),
 *     @OA\Property(property="TrackingNumber", type="string", example="TRK123456"),
 *     @OA\Property(property="DeliveryDate", type="string", format="date", example="2025-06-01"),
 *     @OA\Property(property="Status", type="string", example="On Delivery")
 * )
 */
class ShipmentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/shipments",
     *     tags={"Shipments"},
     *     summary="Get list of shipments",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Shipment"))
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $shipments = Shipment::with(['package', 'vendor', 'courier'])->get();
        return response()->json($shipments);
    }

    /**
     * @OA\Post(
     *     path="/api/shipments",
     *     tags={"Shipments"},
     *     summary="Store a new shipment",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="package_id", type="integer", example=1),
     *             @OA\Property(property="vendor_id", type="integer", example=2),
     *             @OA\Property(property="courier_id", type="integer", example=3),
     *             @OA\Property(property="tracking_number", type="string", example="TRK123456"),
     *             @OA\Property(property="delivery_date", type="string", format="date", example="2025-06-01"),
     *             @OA\Property(property="status", type="string", example="On Delivery")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Shipment created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Shipment")
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'package_id' => 'required|integer|exists:packages,PackageID',
            'vendor_id' => 'required|integer|exists:vendors,VendorID',
            'courier_id' => 'required|integer|exists:couriers,CourierID',
            'tracking_number' => 'required|string|max:255|unique:shipments,TrackingNumber',
            'delivery_date' => 'nullable|date_format:Y-m-d',
            'status' => 'required|string|max:255',
        ]);

        $shipment = Shipment::create([
            'PackageID' => $validated['package_id'],
            'VendorID' => $validated['vendor_id'],
            'CourierID' => $validated['courier_id'],
            'TrackingNumber' => $validated['tracking_number'],
            'DeliveryDate' => $validated['delivery_date'] ?? null,
            'Status' => $validated['status'],
        ]);

        $shipment->load(['package', 'vendor', 'courier']);
        return response()->json($shipment, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/shipments/{id}",
     *     tags={"Shipments"},
     *     summary="Get a shipment by ID",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Shipment retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Shipment")
     *     )
     * )
     */
    public function show($id): JsonResponse
    {
        $shipment = Shipment::with(['package', 'vendor', 'courier'])->findOrFail($id);
        return response()->json($shipment);
    }

    /**
     * @OA\Put(
     *     path="/api/shipments/{id}",
     *     tags={"Shipments"},
     *     summary="Update a shipment by ID",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="tracking_number", type="string", example="TRK12345678"),
     *             @OA\Property(property="delivery_date", type="string", format="date", example="2025-06-02"),
     *             @OA\Property(property="status", type="string", example="Delivered")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Shipment updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Shipment")
     *     )
     * )
     */
    public function update(Request $request, $id): JsonResponse
    {
        $shipment = Shipment::findOrFail($id);

        $validated = $request->validate([
            'tracking_number' => [
                'sometimes',
                'required',
                'string',
                'max:255',
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

        $shipment->load(['package', 'vendor', 'courier']);
        return response()->json($shipment);
    }

    /**
     * @OA\Delete(
     *     path="/api/shipments/{id}",
     *     tags={"Shipments"},
     *     summary="Delete a shipment by ID",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Shipment deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Shipment deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="An error occurred while deleting the shipment.")
     *         )
     *     )
     * )
     */
    public function destroy($id): JsonResponse
    {
        $shipment = Shipment::findOrFail($id);
        try {
            $shipment->delete();
            return response()->json(['message' => 'Shipment deleted successfully']);
        } catch (\Exception $e) {
            report($e);
            return response()->json(['message' => 'An error occurred while deleting the shipment.'], 500);
        }
    }
}
