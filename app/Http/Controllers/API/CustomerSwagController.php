<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use OpenApi\Annotations as OA;

class CustomerSwagController extends Controller
{
    /**
     * @OA\Schema(
     * schema="Customer",
     * type="object",
     * title="Customer",
     * description="Customer model representing a user of the service.",
     * required={"name", "email"},
     * @OA\Property(property="id", type="integer", example=1, description="Unique ID of the customer"),
     * @OA\Property(property="name", type="string", example="Jane Doe", description="Full name of the customer"),
     * @OA\Property(property="email", type="string", format="email", example="janedoe@example.com", description="Email address of the customer (must be unique)"),
     * @OA\Property(property="phone_number", type="string", example="08123456789", description="Phone number of the customer", nullable=true),
     * @OA\Property(property="address", type="string", example="Jl. Melati No. 5", description="Physical address of the customer", nullable=true),
     * @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp when the customer was created", example="2023-01-01T12:00:00Z"),
     * @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp when the customer was last updated", example="2023-01-01T12:00:00Z")
     * )
     */

    /**
     * @OA\Get(
     * path="/customers",
     * tags={"Customers"},
     * operationId="listCustomers",
     * summary="Get list of customers",
     * description="Retrieve a list of all customers registered in the system.",
     * @OA\Response(
     * response=200,
     * description="Successfully retrieved customer list",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="message", type="string", example="Successfully retrieved customer list"),
     * @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Customer"))
     * )
     * ),
     * @OA\Response(
     * response=500,
     * description="Internal server error",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="boolean", example=false),
     * @OA\Property(property="message", type="string", example="An error occurred while retrieving customers.")
     * )
     * )
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
     * path="/customers",
     * tags={"Customers"},
     * operationId="storeCustomer",
     * summary="Store a new customer",
     * description="Create a new customer record in the database.",
     * @OA\RequestBody(
     * required=true,
     * description="Customer data to store",
     * @OA\JsonContent(
     * required={"name", "email"},
     * @OA\Property(property="name", type="string", example="John Doe", description="Full name of the customer"),
     * @OA\Property(property="email", type="string", format="email", example="john@example.com", description="Email address of the customer"),
     * @OA\Property(property="phone_number", type="string", example="081234567890", description="Phone number of the customer", nullable=true),
     * @OA\Property(property="address", type="string", example="Jl. Damai No. 10", description="Address of the customer", nullable=true)
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Customer created successfully",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="message", type="string", example="Customer created successfully"),
     * @OA\Property(property="data", ref="#/components/schemas/Customer")
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="The given data was invalid."),
     * @OA\Property(property="errors", type="object", example={"email": {"The email has already been taken."}})
     * )
     * ),
     * @OA\Response(
     * response=500,
     * description="Internal server error",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="boolean", example=false),
     * @OA\Property(property="message", type="string", example="An error occurred while creating the customer.")
     * )
     * )
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
     * @OA\Put(
     * path="/customers/{id}",
     * tags={"Customers"},
     * operationId="updateCustomer",
     * summary="Update a customer by ID",
     * description="Update details of an existing customer by their ID.",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the customer to update",
     * @OA\Schema(type="integer", format="int64", example=1)
     * ),
     * @OA\RequestBody(
     * required=false,
     * description="Fields to update for the customer",
     * @OA\JsonContent(
     * @OA\Property(property="name", type="string", example="Jane Doe Updated", nullable=true),
     * @OA\Property(property="email", type="string", format="email", example="janeupdated@example.com", nullable=true),
     * @OA\Property(property="phone_number", type="string", example="081234567890", nullable=true),
     * @OA\Property(property="address", type="string", example="Jl. Melati No. 10", nullable=true)
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Customer updated successfully",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="message", type="string", example="Customer updated successfully"),
     * @OA\Property(property="data", ref="#/components/schemas/Customer")
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Customer not found",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="boolean", example=false),
     * @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Customer] 123")
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Validation error",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="The given data was invalid."),
     * @OA\Property(property="errors", type="object", example={"email": {"The email has already been taken."}})
     * )
     * ),
     * @OA\Response(
     * response=500,
     * description="Internal server error",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="boolean", example=false),
     * @OA\Property(property="message", type="string", example="An error occurred while updating the customer.")
     * )
     * )
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
                Rule::unique('customers', 'email')->ignore($customer->id, 'id')
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
     * path="/customers/{id}",
     * tags={"Customers"},
     * operationId="deleteCustomer",
     * summary="Delete a customer by ID",
     * description="Delete an existing customer record by its ID.",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID of the customer to delete",
     * @OA\Schema(type="integer", format="int64", example=1)
     * ),
     * @OA\Response(
     * response=200,
     * description="Customer deleted successfully",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="boolean", example=true),
     * @OA\Property(property="message", type="string", example="Customer deleted successfully")
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Customer not found",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="boolean", example=false),
     * @OA\Property(property="message", type="string", example="No query results for model [App\\Models\\Customer] 123")
     * )
     * ),
     * @OA\Response(
     * response=409,
     * description="Conflict (Could be associated with other data, e.g., orders)",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="boolean", example=false),
     * @OA\Property(property="message", type="string", example="Failed to delete customer. It might be associated with existing orders or packages.")
     * )
     * ),
     * @OA\Response(
     * response=500,
     * description="Internal server error",
     * @OA\JsonContent(
     * @OA\Property(property="success", type="boolean", example=false),
     * @OA\Property(property="message", type="string", example="An error occurred while deleting the customer.")
     * )
     * )
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
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete customer. It might be associated with existing orders or packages.'
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
