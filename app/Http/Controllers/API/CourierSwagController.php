<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

/**
 * @OA\Tag(
 *     name="Couriers",
 *     description="API Endpoints for Managing Couriers"
 * )
 */
class CourierSwagController extends Controller
{
    /**
     * @OA\Get(
     *     path="/couriers",
     *     summary="Get all couriers",
     *     tags={"Couriers"},
     *     @OA\Response(
     *         response=200,
     *         description="Couriers retrieved successfully"
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $couriers = Courier::all();

        return response()->json([
            'status' => 200,
            'message' => 'Couriers retrieved successfully',
            'data' => $couriers
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/couriers",
     *     summary="Create a new courier",
     *     tags={"Couriers"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"Name"},
     *             @OA\Property(property="Name", type="string", example="Jane Doe"),
     *             @OA\Property(property="LicenseNumber", type="string", example="B1234XYZ"),
     *             @OA\Property(property="VehicleType", type="string", example="Car"),
     *             @OA\Property(property="PhoneNumber", type="string", example="082233445566")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Courier created successfully"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'Name' => 'required|string',
            'LicenseNumber' => 'nullable|string|unique:couriers,LicenseNumber',
            'VehicleType' => 'nullable|string',
            'PhoneNumber' => 'nullable|string',
        ]);

        $courier = Courier::create($request->all());

        return response()->json([
            'status' => 201,
            'message' => 'Courier created successfully',
            'data' => $courier
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/couriers/{id}",
     *     summary="Update a courier",
     *     tags={"Couriers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Courier ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="Name", type="string", example="Jane Updated"),
     *             @OA\Property(property="LicenseNumber", type="string", example="D4321CBA"),
     *             @OA\Property(property="VehicleType", type="string", example="Truck"),
     *             @OA\Property(property="PhoneNumber", type="string", example="085566778899")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Courier updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Courier not found"
     *     )
     * )
     */
    public function update(Request $request, $id): JsonResponse
    {
        $courier = Courier::find($id);

        if (!$courier) {
            return response()->json([
                'status' => 404,
                'message' => 'Courier not found',
                'data' => null
            ], 404);
        }

        $request->validate([
            'Name' => 'required|string',
            'LicenseNumber' => [
                'nullable', 'string',
                Rule::unique('couriers', 'LicenseNumber')->ignore($courier->CourierID, 'CourierID'),
            ],
            'VehicleType' => 'nullable|string',
            'PhoneNumber' => 'nullable|string',
        ]);

        $courier->update($request->all());

        return response()->json([
            'status' => 200,
            'message' => 'Courier updated successfully',
            'data' => $courier
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/couriers/{id}",
     *     summary="Delete a courier",
     *     tags={"Couriers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Courier ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Courier deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Courier not found"
     *     )
     * )
     */
    public function destroy($id): JsonResponse
    {
        $courier = Courier::find($id);

        if (!$courier) {
            return response()->json([
                'status' => 404,
                'message' => 'Courier not found',
                'data' => null
            ], 404);
        }

        $courier->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Courier deleted successfully',
            'data' => null
        ], 200);
    }
}
