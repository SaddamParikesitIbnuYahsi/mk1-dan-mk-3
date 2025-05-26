<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PackageController extends Controller
{
    /**
     * @OA\Schema(
     *     schema="Package",
     *     type="object",
     *     @OA\Property(property="PackageID", type="integer", example=1),
     *     @OA\Property(property="SenderID", type="integer", example=1),
     *     @OA\Property(property="CustomerID", type="integer", example=2),
     *     @OA\Property(property="weight", type="number", format="float", example=2.5),
     *     @OA\Property(property="package_type", type="string", example="Box"),
     *     @OA\Property(property="shipping_cost", type="number", format="float", example=25000),
     *     @OA\Property(property="receipt_date", type="string", format="date", example="2025-05-26")
     * )
     */

    /**
     * @OA\Get(
     *     path="/api/packages",
     *     tags={"Packages"},
     *     summary="Get list of packages",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Successfully retrieved package list"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Package"))
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $packages = Package::all();

        return response()->json([
            'success' => true,
            'message' => 'Successfully retrieved package list',
            'data' => $packages
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/packages",
     *     tags={"Packages"},
     *     summary="Store a new package",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
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
     *         description="Package created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Package created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Package")
     *         )
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'SenderID' => 'required|exists:senders,SenderID',
            'CustomerID' => 'required|exists:customers,CustomerID',
            'weight' => 'required|numeric',
            'package_type' => 'required|string|max:50',
            'shipping_cost' => 'required|numeric',
            'receipt_date' => 'required|date',
        ]);

        $package = Package::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Package created successfully',
            'data' => $package
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/packages/{id}",
     *     tags={"Packages"},
     *     summary="Get package details by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Package retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Package retrieved successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Package")
     *         )
     *     )
     * )
     */
    public function show($id): JsonResponse
    {
        $package = Package::findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Package retrieved successfully',
            'data' => $package
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/packages/{id}",
     *     tags={"Packages"},
     *     summary="Update a package by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
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
     *         description="Package updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Package updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Package")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id): JsonResponse
    {
        $package = Package::findOrFail($id);

        $validated = $request->validate([
            'SenderID' => 'sometimes|required|exists:senders,SenderID',
            'CustomerID' => 'sometimes|required|exists:customers,CustomerID',
            'weight' => 'sometimes|required|numeric',
            'package_type' => 'sometimes|required|string|max:50',
            'shipping_cost' => 'sometimes|required|numeric',
            'receipt_date' => 'sometimes|required|date',
        ]);

        $package->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Package updated successfully',
            'data' => $package
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/packages/{id}",
     *     tags={"Packages"},
     *     summary="Delete a package by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Package deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Package deleted successfully")
     *         )
     *     )
     * )
     */
    public function destroy($id): JsonResponse
    {
        $package = Package::findOrFail($id);
        $package->delete();

        return response()->json([
            'success' => true,
            'message' => 'Package deleted successfully'
        ]);
    }
}
