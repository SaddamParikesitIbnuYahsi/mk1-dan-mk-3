<?php

namespace App\Http\Controllers;

use App\Models\Sender;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class SenderController extends Controller
{
    public function index(): JsonResponse
    {
        $senders = Sender::all();
        return response()->json([
            'success' => true,
            'message' => 'Successfully retrieved customer list',
            'data' => $senders
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:senders,email',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $sender = Sender::create($validated);

        return response()->json($sender, 201);
    }

    public function show($id): JsonResponse
    {
        $sender = Sender::findOrFail($id);
        return response()->json($sender);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $sender = Sender::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'nullable',
                'email',
                'max:255',
                Rule::unique('senders')->ignore($sender->id),
            ],
            'phone_number' => 'sometimes|nullable|string|max:20',
            'address' => 'sometimes|nullable|string|max:255',
        ]);

        $sender->update($validated);

        return response()->json($sender);
    }

    public function destroy($id): JsonResponse
    {
        $sender = Sender::findOrFail($id);

        try {
            $sender->delete();
            return response()->json(['message' => 'Sender deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete sender'], 500);
        }
    }
}