<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Js;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): JsonResponse
    {
        // Debug opcional para diagnosticar problema de acceso vía navegador.
        // Usar: /api/patients?debug=1
        if (request()->boolean('debug')) {
            $user = auth()->user();
            return response()->json([
                'debug' => true,
                'authenticated' => auth()->check(),
                'guard' => Auth::getDefaultDriver(),
                'user' => $user ? [
                    'id' => $user->id,
                    'email' => $user->email,
                    'roles' => $user->roles->pluck('name'),
                ] : null,
                'cookies_sent' => array_keys(request()->cookies->all()),
                'headers' => [
                    'accept' => request()->header('Accept'),
                    'authorization_present' => request()->hasHeader('Authorization'),
                    'x_xsrf_token_present' => request()->hasHeader('X-XSRF-TOKEN'),
                ],
            ], 200);
        }

        $patients = Patient::all();
        return response()->json(['data' => $patients], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): JsonResponse
    {
        return response()->json(['message' => 'Not implemented'], 501);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'rut' => 'required|unique:patients',
            'name' => 'required',
            'birth_date' => 'required|date',
            'gender' => 'required',
            'adress' => 'required',
        ]);


        $patient = Patient::create($request->all());

        return response()->json(['data' => $patient], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function show(Patient $patient): JsonResponse
    {
        return response()->json(['data' => $patient], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(Patient $patient): JsonResponse
    {
        return response()->json(['message' => 'Not implemented'], 501);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Patient $patient): JsonResponse
    {
        return response()->json(['message' => 'Not implemented'], 501);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patient $patient): JsonResponse
    {
        $patient->delete();
        return response()->json(null, 204);
    }
}
