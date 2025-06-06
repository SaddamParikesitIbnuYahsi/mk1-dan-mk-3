<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Vendors",
 *     description="API for managing vendors"
 * )
 */
class VendorSwagController extends Controller
{
    /**
     * @OA\Get(
     *     path="/vendors",
     *     summary="Get all vendors",
     *     tags={"Vendors"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="List of vendors retrieved successfully."),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Vendor"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        try {
            $vendors = Vendor::all();
            return response()->json([
                'status' => 'success',
                'message' => 'List of vendors retrieved successfully.',
                'data' => $vendors
            ]);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while retrieving vendors.',
                'data' => null
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/vendors",
     *     summary="Create new vendor",
     *     tags={"Vendors"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"business_name"},
     *             @OA\Property(property="business_name", type="string", example="CV Maju Jaya"),
     *             @OA\Property(property="license_number", type="string", example="LN-12345"),
     *             @OA\Property(property="address", type="string", example="Jl. Merdeka No. 10, Jakarta")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Vendor created",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Vendor created successfully."),
     *             @OA\Property(property="data", ref="#/components/schemas/Vendor")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        // kode store tetap sama
    }

    /**
     * @OA\Put(
     *     path="/vendors/{id}",
     *     summary="Update a vendor",
     *     tags={"Vendors"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Vendor ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\JsonContent(
     *             @OA\Property(property="business_name", type="string", example="CV Maju Lancar"),
     *             @OA\Property(property="license_number", type="string", example="LN-54321"),
     *             @OA\Property(property="address", type="string", example="Jl. Veteran No. 5, Bandung")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vendor updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Vendor updated successfully."),
     *             @OA\Property(property="data", ref="#/components/schemas/Vendor")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vendor not found"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function update(Request $request, $id): JsonResponse
    {
        // kode update tetap sama
    }

    /**
     * @OA\Delete(
     *     path="/vendors/{id}",
     *     summary="Delete a vendor",
     *     tags={"Vendors"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Vendor ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vendor deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Vendor deleted successfully."),
     *             @OA\Property(property="data", type="string", nullable=true, example=null)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vendor not found"
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Conflict – vendor has dependencies"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error"
     *     )
     * )
     */
    public function destroy($id): JsonResponse
    {
        // kode destroy tetap sama
    }
}
