<?php

namespace App\Http\Controllers;

use App\Models\Sender;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class SenderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $senders = Sender::all();
        return response()->json($senders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:senders,Email', // Pastikan kolom Email ada di tabel senders
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        // Mapping snake_case ke PascalCase untuk create
        $sender = Sender::create([
            'Name' => $validated['name'],
            'Email' => $validated['email'],
            'PhoneNumber' => $validated['phone_number'] ?? null,
            'Address' => $validated['address'] ?? null,
        ]);

        return response()->json($sender, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse // Tetap gunakan $id sesuai format sebelumnya
    {
        $sender = Sender::findOrFail($id);
        return response()->json($sender);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse // Tetap gunakan $id
    {
        $sender = Sender::findOrFail($id); // Cari sender terlebih dahulu

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                // Pastikan nama kolom PK benar (SenderID)
                Rule::unique('senders', 'Email')->ignore($sender->SenderID, 'SenderID')
            ],
            'phone_number' => 'sometimes|nullable|string|max:20',
            'address' => 'sometimes|nullable|string',
        ]);

        // Buat array data untuk diupdate secara eksplisit
        $updateData = [];
        if ($request->has('name')) { // Cek jika field dikirim
            $updateData['Name'] = $validated['name'];
        }
        if ($request->has('email')) {
            $updateData['Email'] = $validated['email'];
        }
        if ($request->has('phone_number')) { // Cek field nullable
            $updateData['PhoneNumber'] = $validated['phone_number']; // Ambil nilai (bisa null)
        }
        if ($request->has('address')) { // Cek field nullable
            $updateData['Address'] = $validated['address']; // Ambil nilai (bisa null)
        }

        // Lakukan update hanya jika ada data yang valid
        if (!empty($updateData)) {
            $sender->update($updateData);
        }

        return response()->json($sender);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse // Tetap gunakan $id
    {
        $sender = Sender::findOrFail($id);

        try {
             $sender->delete();
             return response()->json(['message' => 'Sender deleted successfully']);
        } catch (\Illuminate\Database\QueryException $e) {
            // Tangani error constraint jika ada relasi yang melarang delete
             return response()->json(['message' => 'Failed to delete sender. It might be associated with existing packages.'], 409); // 409 Conflict
        } catch (\Exception $e) {
             // Tangani error umum lainnya
             report($e); // Laporkan error untuk debugging
             return response()->json(['message' => 'An error occurred while deleting the sender.'], 500);
        }
    }
}
