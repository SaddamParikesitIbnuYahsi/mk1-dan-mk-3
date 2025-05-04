<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        // Eager load relasi untuk response yang lebih informatif
        $packages = Package::with(['sender', 'customer'])->get();
        return response()->json($packages);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Gunakan nama field request snake_case
            'sender_id' => 'required|integer|exists:senders,SenderID',
            'customer_id' => 'required|integer|exists:customers,CustomerID',
            'weight' => 'nullable|numeric|min:0',
            'package_type' => 'nullable|string|max:255',
            'shipping_cost' => 'nullable|numeric|min:0',
            'delivery_date' => 'nullable|date_format:Y-m-d',
            'receipt_date' => 'nullable|date_format:Y-m-d',
        ]);

        // Mapping ke nama kolom Model PascalCase
        $package = Package::create([
            'SenderID' => $validated['sender_id'],
            'CustomerID' => $validated['customer_id'],
            'Weight' => $validated['weight'] ?? null,
            'PackageType' => $validated['package_type'] ?? null,
            'ShippingCost' => $validated['shipping_cost'] ?? null,
            'DeliveryDate' => $validated['delivery_date'] ?? null,
            'ReceiptDate' => $validated['receipt_date'] ?? null,
        ]);

        // Load relasi setelah dibuat jika diperlukan dalam response
        $package->load(['sender', 'customer']);

        return response()->json($package, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        // Eager load relasi
        $package = Package::with(['sender', 'customer', 'shipments'])->findOrFail($id);
        return response()->json($package);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $package = Package::findOrFail($id);

        $validated = $request->validate([
            // FK biasanya tidak diupdate, jika perlu, tambahkan validasi 'exists'
            'weight' => 'sometimes|nullable|numeric|min:0',
            'package_type' => 'sometimes|nullable|string|max:255',
            'shipping_cost' => 'sometimes|nullable|numeric|min:0',
            'delivery_date' => 'sometimes|nullable|date_format:Y-m-d',
            'receipt_date' => 'sometimes|nullable|date_format:Y-m-d',
        ]);

        $updateData = [];
        // Cek jika field ada dalam request sebelum menambahkannya ke updateData
        if ($request->has('weight')) {
            $updateData['Weight'] = $validated['weight'];
        }
        if ($request->has('package_type')) {
            $updateData['PackageType'] = $validated['package_type'];
        }
        if ($request->has('shipping_cost')) {
            $updateData['ShippingCost'] = $validated['shipping_cost'];
        }
        if ($request->has('delivery_date')) {
            $updateData['DeliveryDate'] = $validated['delivery_date'];
        }
        if ($request->has('receipt_date')) {
            $updateData['ReceiptDate'] = $validated['receipt_date'];
        }


        if (!empty($updateData)) {
            $package->update($updateData);
        }

        // Load relasi lagi untuk response update terbaru
        $package->load(['sender', 'customer']);
        return response()->json($package);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $package = Package::findOrFail($id);
        try {
            // Jika migration onDelete cascade, shipment terkait akan ikut terhapus
            $package->delete();
            return response()->json(['message' => 'Package deleted successfully']);
        } catch (\Illuminate\Database\QueryException $e) {
             // Constraint error mungkin tidak terjadi jika cascade, tapi jaga-jaga
            return response()->json(['message' => 'Failed to delete package. Check related shipments or constraints.'], 409);
        } catch (\Exception $e) {
            report($e);
            return response()->json(['message' => 'An error occurred while deleting the package.'], 500);
        }
    }
}
