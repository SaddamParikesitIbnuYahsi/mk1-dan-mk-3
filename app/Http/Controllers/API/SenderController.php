<?php

namespace App\Http\Controllers;

use App\Models\Sender;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

/**
 * @OA\Info(version="1.0.0", title="API Pengiriman Paket")
 * @OA\Server(url=L5_SWAGGER_CONST_HOST, description="API Server")
 * @OA\Tag(name="Senders", description="Operasi Pengirim")
 */
class SenderController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/senders",
     * tags={"Senders"},
     * summary="List semua pengirim",
     * operationId="getSendersList",
     * @OA\Response(response=200, description="OK", @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Sender")))
     * )
     */
    public function index(): JsonResponse
    {
        // ... Logika Index ...
        $senders = Sender::all();
        return response()->json($senders);
    }

    /**
     * @OA\Post(
     * path="/api/senders",
     * tags={"Senders"},
     * summary="Buat pengirim baru",
     * operationId="storeSender",
     * @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/StoreSenderRequest")),
     * @OA\Response(response=201, description="Created", @OA\JsonContent(ref="#/components/schemas/Sender")),
     * @OA\Response(response=422, description="Validation Error")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        // ... Logika Store ...
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:senders,Email',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $sender = Sender::create([
            'Name' => $validated['name'],
            'Email' => $validated['email'],
            'PhoneNumber' => $validated['phone_number'] ?? null,
            'Address' => $validated['address'] ?? null,
        ]);

        return response()->json($sender, 201);
    }

    /**
     * @OA\Get(
     * path="/api/senders/{id}",
     * tags={"Senders"},
     * summary="Tampilkan detail pengirim",
     * operationId="getSenderById",
     * @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     * @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/Sender")),
     * @OA\Response(response=404, description="Not Found")
     * )
     */
    public function show($id): JsonResponse
    {
        // ... Logika Show ...
         $sender = Sender::findOrFail($id);
        return response()->json($sender);
    }

     /**
     * @OA\Put(
     * path="/api/senders/{id}",
     * tags={"Senders"},
     * summary="Update pengirim",
     * operationId="updateSender",
     * @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     * @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/UpdateSenderRequest")),
     * @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/Sender")),
     * @OA\Response(response=404, description="Not Found"),
     * @OA\Response(response=422, description="Validation Error")
     * )
     */
    public function update(Request $request, $id): JsonResponse
    {
        // ... Logika Update ...
        $sender = Sender::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes','required','string','email','max:255', Rule::unique('senders', 'Email')->ignore($sender->SenderID, 'SenderID')],
            'phone_number' => 'sometimes|nullable|string|max:20',
            'address' => 'sometimes|nullable|string',
        ]);

        $updateData = [];
        if ($request->has('name')) { $updateData['Name'] = $validated['name']; }
        if ($request->has('email')) { $updateData['Email'] = $validated['email']; }
        if ($request->has('phone_number')) { $updateData['PhoneNumber'] = $validated['phone_number']; }
        if ($request->has('address')) { $updateData['Address'] = $validated['address']; }

        if (!empty($updateData)) {
            $sender->update($updateData);
        }

        return response()->json($sender);
    }

    /**
     * @OA\Delete(
     * path="/api/senders/{id}",
     * tags={"Senders"},
     * summary="Hapus pengirim",
     * operationId="deleteSender",
     * @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     * @OA\Response(response=200, description="OK", @OA\JsonContent(@OA\Property(property="message", type="string"))),
     * @OA\Response(response=404, description="Not Found"),
     * @OA\Response(response=409, description="Conflict")
     * )
     */
    public function destroy($id): JsonResponse
    {
        // ... Logika Destroy ...
        $sender = Sender::findOrFail($id);
        try {
             $sender->delete();
             return response()->json(['message' => 'Sender deleted successfully']);
        } catch (\Illuminate\Database\QueryException $e) {
             return response()->json(['message' => 'Failed to delete sender. It might be associated with existing packages.'], 409);
        } catch (\Exception $e) {
             report($e);
             return response()->json(['message' => 'An error occurred while deleting the sender.'], 500);
        }
    }
}

// --- Skema Tetap Diperlukan ---

/**
 * @OA\Schema(
 * schema="Sender", title="Sender", description="Model Pengirim",
 * @OA\Property(property="SenderID", type="integer"),
 * @OA\Property(property="Name", type="string"),
 * @OA\Property(property="Email", type="string", format="email"),
 * @OA\Property(property="PhoneNumber", type="string", nullable=true),
 * @OA\Property(property="Address", type="string", nullable=true),
 * @OA\Property(property="created_at", type="string", format="date-time"),
 * @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

 /**
 * @OA\Schema(
 * schema="StoreSenderRequest", title="Store Sender Request", required={"name", "email"},
 * @OA\Property(property="name", type="string", example="Budi"),
 * @OA\Property(property="email", type="string", format="email", example="budi@mail.com"),
 * @OA\Property(property="phone_number", type="string", example="081234"),
 * @OA\Property(property="address", type="string", example="Jl. Sana Sini")
 * )
 */

  /**
 * @OA\Schema(
 * schema="UpdateSenderRequest", title="Update Sender Request",
 * @OA\Property(property="name", type="string"),
 * @OA\Property(property="email", type="string", format="email"),
 * @OA\Property(property="phone_number", type="string", nullable=true),
 * @OA\Property(property="address", type="string", nullable=true)
 * )
 */