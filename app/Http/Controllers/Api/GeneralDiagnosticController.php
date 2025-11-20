<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GeneralDiagnostic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GeneralDiagnosticController extends Controller
{
    public function index(): JsonResponse
    {
        $items = GeneralDiagnostic::with('symptoms')->latest()->get();
        return response()->json(['data' => $items], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'description' => 'required|string',
            'date' => 'nullable|date',
            'symptoms' => 'nullable|array',
        ]);

        $gd = GeneralDiagnostic::create([
            'description' => $data['description'],
            'date' => $data['date'] ?? now()->toDateString(),
            'user_id' => optional(auth()->user())->id,
        ]);

        if (!empty($data['symptoms'])) {
            $gd->symptoms()->sync(array_filter($data['symptoms']));
        }

        $gd->load('symptoms');
        return response()->json(['data' => $gd], 201);
    }

    public function show(GeneralDiagnostic $general_diagnostic): JsonResponse
    {
        $general_diagnostic->load('symptoms');
        return response()->json(['data' => $general_diagnostic], 200);
    }

    public function update(Request $request, GeneralDiagnostic $general_diagnostic): JsonResponse
    {
        $data = $request->validate([
            'description' => 'required|string',
            'date' => 'nullable|date',
            'symptoms' => 'nullable|array',
        ]);

        $general_diagnostic->update([
            'description' => $data['description'],
            'date' => $data['date'] ?? $general_diagnostic->date,
            'user_id' => optional(auth()->user())->id,
        ]);

        $general_diagnostic->symptoms()->sync($data['symptoms'] ?? []);

        $general_diagnostic->load('symptoms');
        return response()->json(['data' => $general_diagnostic], 200);
    }

    public function destroy(GeneralDiagnostic $general_diagnostic): JsonResponse
    {
        $general_diagnostic->delete();
        return response()->json(null, 204);
    }
}
