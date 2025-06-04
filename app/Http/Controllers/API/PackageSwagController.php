<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Packages",
 *     description="API Endpoints for Managing Packages"
 * )
 */
class PackageSwagController extends Controller
{
    /**
     * @OA\Get(
     *     path="/packages",
     *     summary="Get all packages",
     *     tags={"Packages"},
     *     @OA\Response(
     *         response=200,
     *         description="Packages retrieved successfully"
     *     )
     * )
     */
    public function index()
    {
        $packages = Package::all();

        return response()->json([
            'status' => 200,
            'message' => 'Packages retrieved successfully',
            'data' => $packages
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/packages",
     *     summary="Create a new package",
     *     tags={"Packages"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"SenderID", "CustomerID", "weight", "package_type", "shipping_cost", "receipt_date"},
     *             @OA\Property(property="SenderID", type="integer", example=1),
     *             @OA\Property(property="CustomerID", type="integer", example=2),
     *             @OA\Property(property="weight", type="number", format="float", example=2.5),
     *             @OA\Property(property="package_type", type="string", example="Box"),
     *             @OA\Property(property="shipping_cost", type="number", format="float", example=25000),
     *             @OA\Property(property="receipt_date", type="string", format="date", example="2025-05-26")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Package created successfully"
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
            'sender_id' => 'sometimes|required|exists:senders,sender_id',
            'customer_id' => 'sometimes|required|exists:customers,customer_id',
            'weight' => 'required|numeric',
            'package_type' => 'required|string|max:50',
            'shipping_cost' => 'required|numeric',
            'receipt_date' => 'required|date',
        ]);

        $package = Package::create($validated);

        return response()->json([
            'status' => 201,
            'message' => 'Package created successfully',
            'data' => $package
        ], 201);
    }

    /**
     * @OA\Put(
     *     path="/packages/{id}",
     *     summary="Update a package",
     *     tags={"Packages"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Package ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="SenderID", type="integer", example=1),
     *             @OA\Property(property="CustomerID", type="integer", example=2),
     *             @OA\Property(property="weight", type="number", format="float", example=3.0),
     *             @OA\Property(property="package_type", type="string", example="Envelope"),
     *             @OA\Property(property="shipping_cost", type="number", format="float", example=15000),
     *             @OA\Property(property="receipt_date", type="string", format="date", example="2025-05-27")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Package updated successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Package not found"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $package = Package::find($id);

        if (!$package) {
            return response()->json([
                'status' => 404,
                'message' => 'Package not found',
                'data' => null
            ], 404);
        }

        $validated = $request->validate([
            'sender_id' => 'sometimes|required|exists:senders,Sender_id',
            'customer_id' => 'sometimes|required|exists:customers,Customer_id',
            'weight' => 'sometimes|required|numeric',
            'package_type' => 'sometimes|required|string|max:50',
            'shipping_cost' => 'sometimes|required|numeric',
            'receipt_date' => 'sometimes|required|date',
        ]);

        $package->update($validated);

        return response()->json([
            'status' => 200,
            'message' => 'Package updated successfully',
            'data' => $package
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/packages/{id}",
     *     summary="Delete a package",
     *     tags={"Packages"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Package ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Package deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Package not found"
     *     )
     * )
     */
    public function destroy($id)
    {
        $package = Package::find($id);

        if (!$package) {
            return response()->json([
                'status' => 404,
                'message' => 'Package not found',
                'data' => null
            ], 404);
        }

        $package->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Package deleted successfully',
            'data' => null
        ], 200);
    }
}
