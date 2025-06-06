<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * @OA\Tag(
 *     name="Shipments",
 *     description="API Endpoints for Managing Shipments"
 * )
 */
class ShipmentSwagController extends Controller
{
    /**
     * @OA\Get(
     *     path="/shipments",
     *     summary="Get all shipments",
     *     tags={"Shipments"},
     *     @OA\Response(
     *         response=200,
     *         description="Shipments retrieved successfully"
     *     )
     * )
     */
    public function index()
    {
        $shipments = Shipment::with(['package', 'vendor', 'courier'])->get();

        return response()->json([
            'status' => 200,
            'message' => 'Shipments retrieved successfully',
            'data' => $shipments
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/shipments",
     *     summary="Create a new shipment",
     *     tags={"Shipments"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"package_id", "vendor_id", "courier_id", "tracking_number", "status"},
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
     *         description="Shipment created successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(Request $request)
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

        return response()->json([
            'status' => 201,
            'message' => 'Shipment created successfully',
            'data' => $shipment
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/shipments/{id}",
     *     summary="Update a shipment",
     *     tags={"Shipments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Shipment ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
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
     *         description="Shipment updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Shipment not found"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $shipment = Shipment::find($id);

        if (!$shipment) {
            return response()->json([
                'status' => 404,
                'message' => 'Shipment not found',
                'data' => null
            ], 404);
        }

        $validated = $request->validate([
            'tracking_number' => [
                'sometimes', 'required', 'string', 'max:255',
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

        return response()->json([
            'status' => 200,
            'message' => 'Shipment updated successfully',
            'data' => $shipment
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/shipments/{id}",
     *     summary="Delete a shipment",
     *     tags={"Shipments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Shipment ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Shipment deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Shipment not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $shipment = Shipment::find($id);

        if (!$shipment) {
            return response()->json([
                'status' => 404,
                'message' => 'Shipment not found',
                'data' => null
            ], 404);
        }

        $shipment->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Shipment deleted successfully',
            'data' => null
        ], 200);
    }
}
