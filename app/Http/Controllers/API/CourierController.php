<?php
namespace App\Http\Controllers;

use App\Models\Courier;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class CourierController extends Controller
{
    /**
     * @OA\Schema(
     *     schema="Courier",
     *     type="object",
     *     required={"CourierID", "Name", "LicenseNumber", "VehicleType", "PhoneNumber"},
     *     @OA\Property(property="CourierID", type="integer", example=1),
     *     @OA\Property(property="Name", type="string", example="John Doe"),
     *     @OA\Property(property="LicenseNumber", type="string", example="ABC123"),
     *     @OA\Property(property="VehicleType", type="string", example="Motorcycle"),
     *     @OA\Property(property="PhoneNumber", type="string", example="08123456789")
     * )
     */

    /**
     * @OA\Get(
     *     path="/api/couriers",
     *     tags={"Couriers"},
     *     summary="Get list of couriers",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Successfully retrieved courier list"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Courier"))
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $couriers = Courier::all();

        return response()->json([
            'success' => true,
            'message' => 'Successfully retrieved courier list',
            'data' => $couriers
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/couriers",
     *     tags={"Couriers"},
     *     summary="Store a new courier",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Jane Doe"),
     *             @OA\Property(property="license_number", type="string", example="XYZ789"),
     *             @OA\Property(property="vehicle_type", type="string", example="Car"),
     *             @OA\Property(property="phone_number", type="string", example="08123456789")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Courier created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Courier created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Courier")
     *         )
     *     )
     * )
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

        return response()->json([
            'success' => true,
            'message' => 'Courier created successfully',
            'data' => $courier
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/couriers/{id}",
     *     tags={"Couriers"},
     *     summary="Get courier details by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Courier retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Courier retrieved successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Courier")
     *         )
     *     )
     * )
     */
    public function show($id): JsonResponse
    {
        $courier = Courier::findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Successfully retrieved courier',
            'data' => $courier
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/couriers/{id}",
     *     tags={"Couriers"},
     *     summary="Update a courier by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Jane Doe Updated"),
     *             @OA\Property(property="license_number", type="string", example="XYZ999"),
     *             @OA\Property(property="vehicle_type", type="string", example="Van"),
     *             @OA\Property(property="phone_number", type="string", example="08123456789")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Courier updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Courier updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Courier")
     *         )
     *     )
     * )
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

    /**
     * @OA\Delete(
     *     path="/api/couriers/{id}",
     *     tags={"Couriers"},
     *     summary="Delete a courier by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Courier deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Courier deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Conflict (Could be associated with other data)",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to delete courier. It might be associated with existing shipments.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="An error occurred while deleting the courier.")
     *         )
     *     )
     * )
     */
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
