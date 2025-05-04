<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $customers = Customer::all();
        return response()->json($customers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers,Email',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $customer = Customer::create([
            'Name' => $validated['name'],
            'Email' => $validated['email'],
            'PhoneNumber' => $validated['phone_number'] ?? null,
            'Address' => $validated['address'] ?? null,
        ]);

        return response()->json($customer, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $customer = Customer::findOrFail($id);
        return response()->json($customer);
    }

    /**
     * Update the specified resource in storage.
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
                // Pastikan nama kolom PK benar (CustomerID)
                Rule::unique('customers', 'Email')->ignore($customer->CustomerID, 'CustomerID')
            ],
            'phone_number' => 'sometimes|nullable|string|max:20',
            'address' => 'sometimes|nullable|string',
        ]);

        $updateData = [];
        if ($request->has('name')) {
            $updateData['Name'] = $validated['name'];
        }
        if ($request->has('email')) {
            $updateData['Email'] = $validated['email'];
        }
        if ($request->has('phone_number')) {
            $updateData['PhoneNumber'] = $validated['phone_number'];
        }
        if ($request->has('address')) {
            $updateData['Address'] = $validated['address'];
        }

        if (!empty($updateData)) {
            $customer->update($updateData);
        }

        return response()->json($customer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $customer = Customer::findOrFail($id);
        try {
            $customer->delete();
            return response()->json(['message' => 'Customer deleted successfully']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['message' => 'Failed to delete customer. It might be associated with existing packages.'], 409);
        } catch (\Exception $e) {
            report($e);
            return response()->json(['message' => 'An error occurred while deleting the customer.'], 500);
        }
    }
}
