<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    
    /**
     * @OA\Schema(
     *     schema="Customer",
     *     type="object",
     *     @OA\Property(property="CustomerID", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="Jane Doe"),
     *     @OA\Property(property="email", type="string", example="janedoe@example.com"),
     *     @OA\Property(property="phone_number", type="string", example="08123456789"),
     *     @OA\Property(property="address", type="string", example="Jl. Melati No. 5")
     * )
     */

    /**
     * @OA\Get(
     *     path="/api/customers",
     *     tags={"Customers"},
     *     summary="Get list of customers",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Successfully retrieved customer list"),
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 ref="#/components/schemas/Customer"
     *             ))
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $customers = Customer::all();

        return response()->json([
            'success' => true,
            'message' => 'Successfully retrieved customer list',
            'data' => $customers
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/customers",
     *     tags={"Customers"},
     *     summary="Store a new customer",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Jane Doe"),
     *             @OA\Property(property="email", type="string", example="jane@example.com"),
     *             @OA\Property(property="phone_number", type="string", example="08123456789"),
     *             @OA\Property(property="address", type="string", example="Jl. Melati No. 5")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Customer created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Customer created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Customer")
     *         )
     *     )
     * )
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers,email',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $customer = Customer::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'] ?? null,
            'address' => $validated['address'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Customer created successfully',
            'data' => $customer
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/customers/{id}",
     *     tags={"Customers"},
     *     summary="Get customer details by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Customer retrieved successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Customer")
     *         )
     *     )
     * )
     */
    public function show($id): JsonResponse
    {
        $customer = Customer::findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Customer retrieved successfully',
            'data' => $customer
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/customers/{id}",
     *     tags={"Customers"},
     *     summary="Update a customer by ID",
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
     *             @OA\Property(property="email", type="string", example="janeupdated@example.com"),
     *             @OA\Property(property="phone_number", type="string", example="08123456789"),
     *             @OA\Property(property="address", type="string", example="Jl. Melati No. 10")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Customer updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Customer")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id): JsonResponse
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('customers', 'email')->ignore($customer->CustomerID, 'CustomerID')
            ],
            'phone_number' => 'sometimes|nullable|string|max:20',
            'address' => 'sometimes|nullable|string',
        ]);

        $updateData = [];
        if ($request->has('name')) $updateData['name'] = $validated['name'];
        if ($request->has('email')) $updateData['email'] = $validated['email'];
        if ($request->has('phone_number')) $updateData['phone_number'] = $validated['phone_number'];
        if ($request->has('address')) $updateData['address'] = $validated['address'];

        $customer->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully',
            'data' => $customer
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/customers/{id}",
     *     tags={"Customers"},
     *     summary="Delete a customer by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Customer deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Customer deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Conflict (Could be associated with other data)",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Failed to delete customer. It might be associated with existing packages.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="An error occurred while deleting the customer.")
     *         )
     *     )
     * )
     */
    public function destroy($id): JsonResponse
    {
        $customer = Customer::findOrFail($id);

        try {
            $customer->delete();
            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete customer. It might be associated with existing packages.'
            ], 409);
        } catch (\Exception $e) {
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the customer.'
            ], 500);
        }
    }
}
