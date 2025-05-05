<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vendors = Vendor::all();

        return response()->json([
            'status' => 200,
            'message' => 'Vendors retrieved successfully',
            'data' => $vendors
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'bussines_name' => 'required|string|max:255',
            'license_number' => 'required|string|max:50',
            'address' => 'required|string',
        ]);

        $vendor = Vendor::create($request->all());

        return response()->json([
            'status' => 201,
            'message' => 'Vendor created successfully',
            'data' => $vendor
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $vendor = Vendor::find($id);

        if (!$vendor) {
            return response()->json([
                'status' => 404,
                'message' => 'Vendor not found',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Vendor retrieved successfully',
            'data' => $vendor
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $vendor = Vendor::find($id);

        if (!$vendor) {
            return response()->json([
                'status' => 404,
                'message' => 'Vendor not found',
                'data' => null
            ], 404);
        }

        $request->validate([
            'bussines_name' => 'sometimes|required|string|max:255',
            'license_number' => 'sometimes|required|string|max:50',
            'address' => 'sometimes|required|string',
        ]);

        $vendor->update($request->all());

        return response()->json([
            'status' => 200,
            'message' => 'Vendor updated successfully',
            'data' => $vendor
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $vendor = Vendor::find($id);

        if (!$vendor) {
            return response()->json([
                'status' => 404,
                'message' => 'Vendor not found',
                'data' => null
            ], 404);
        }

        $vendor->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Vendor deleted successfully',
            'data' => null
        ], 200);
    }
}
