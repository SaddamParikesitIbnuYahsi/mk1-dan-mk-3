<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    // Method untuk menampilkan semua data shipment
    public function index()
    {
        $shipments = Shipment::with(['order', 'vendor', 'courier'])->get();
        
        return response()->json([
            'status' => 200,
            'message' => 'Shipments retrieved successfully.',
            'data' => $shipments
        ], 200);
    }

    // Method untuk menampilkan satu data shipment berdasarkan ID
    public function show($id)
    {
        $shipment = Shipment::with(['order', 'vendor', 'courier'])->find($id);

        if (!$shipment) {
            return response()->json([
                'status' => 404,
                'message' => 'Shipment not found.',
                'data' => null
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'message' => 'Shipment retrieved successfully.',
            'data' => $shipment
        ], 200);
    }

    // Method untuk membuat shipment baru
    public function store(Request $request)
    {
        // Validasi input request
        $request->validate([
            'order_id' => 'required|string|max:255',
            'vendor_id' => 'required|string|max:255',
            'courier_id' => 'required|string|max:255',
            'trackingnumber' => 'required|string|max:255',
            'deliverydate' => 'required|date',
            'status' => 'required|string|max:255'
        ]);

        // Membuat shipment baru
        $shipment = Shipment::create($request->all());

        return response()->json([
            'status' => 201,
            'message' => 'Shipment created successfully.',
            'data' => $shipment
        ], 201);
    }

    // Method untuk mengupdate data shipment berdasarkan ID
    public function update(Request $request, $id)
    {
        // Mencari shipment berdasarkan ID
        $shipment = Shipment::find($id);

        if (!$shipment) {
            return response()->json([
                'status' => 404,
                'message' => 'Shipment not found.',
                'data' => null
            ], 404);
        }

        // Validasi input request
        $request->validate([
            'order_id' => 'required|string|max:255',
            'vendor_id' => 'required|string|max:255',
            'courier_id' => 'required|string|max:255',
            'trackingnumber' => 'required|string|max:255',
            'deliverydate' => 'required|date',
            'status' => 'required|string|max:255'
        ]);

        // Mengupdate shipment
        $shipment->update($request->all());

        return response()->json([
            'status' => 200,
            'message' => 'Shipment updated successfully.',
            'data' => $shipment
        ], 200);
    }

    // Method untuk menghapus shipment berdasarkan ID
    public function destroy($id)
    {
        // Mencari shipment berdasarkan ID
        $shipment = Shipment::find($id);

        if (!$shipment) {
            return response()->json([
                'status' => 404,
                'message' => 'Shipment not found.',
                'data' => null
            ], 404);
        }

        // Menghapus shipment
        $shipment->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Shipment deleted successfully.',
            'data' => null
        ], 200);
    }
}
