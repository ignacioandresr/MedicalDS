<?php

namespace App\Http\Controllers;

use App\Models\Symptom;
use App\Models\Diagnostic;
use App\Models\GeneralDiagnostic;
use App\Models\Patient;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class SymptomController extends Controller
{
    public function index()
    {
        $symptoms = Symptom::all();
        return view('symptoms.index', compact('symptoms'));
    }

    public function create()
    {
        $diagnostics = Diagnostic::with('patient')->get();
        $patients = Patient::all();
        return view('symptoms.create', compact('diagnostics', 'patients'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'diagnostic_id' => 'nullable|exists:diagnostics,id',
            'general_diagnostic_id' => 'nullable|exists:general_diagnostics,id',
        ]);

        $symptom = Symptom::create([
            'name' => $data['name'] ?? null,
            'description' => $data['description'] ?? null,
            'patient_id' => $data['patient_id'],
        ]);

        // Determinar o crear el diagnóstico asociado
        $diag = null;
        if (!empty($data['diagnostic_id'])) {
            $diag = Diagnostic::find($data['diagnostic_id']);
        } elseif (!empty($data['general_diagnostic_id'])) {
            // crear un Diagnostic basado en el GeneralDiagnostic seleccionado
            $gd = GeneralDiagnostic::with('symptoms')->find($data['general_diagnostic_id']);
            if ($gd) {
                $diag = Diagnostic::create([
                    'description' => $gd->description,
                    'date' => now()->toDateString(),
                    'patient_id' => $data['patient_id'],
                    'user_id' => auth()->id(),
                ]);
                // adjuntar los síntomas del GD al nuevo diagnóstico (si existen)
                $symptomIds = $gd->symptoms->pluck('id')->toArray();
                if (!empty($symptomIds)) {
                    $diag->symptoms()->sync($symptomIds);
                }
            }
        } else {
            // crear un diagnóstico nuevo a partir de la descripción del síntoma
            $diag = Diagnostic::create([
                'description' => $data['description'] ?? ('Síntoma: ' . ($data['name'] ?? 'Sin nombre')),
                'date' => now()->toDateString(),
                'patient_id' => $data['patient_id'],
                'user_id' => auth()->id(),
            ]);
        }

        // Si se creó o seleccionó un diagnóstico, asociar el síntoma y actualizar record
        if ($diag) {
            $diag->symptoms()->attach($symptom->id);

            $record = \App\Models\Record::where('patient_id', $data['patient_id'])->latest('created_at')->first();
            if ($record) {
                $record->diagnostic_id = $diag->id;
                $record->save();
            } else {
                \App\Models\Record::create([
                    'patient_id' => $data['patient_id'],
                    'diagnostic_id' => $diag->id,
                    'antecedentes_salud' => 'Sin observaciones',
                    'fecha' => now()->toDateString(),
                ]);
            }
        }

        return redirect()->route('symptoms.index')->with('success', 'Síntoma creado correctamente.');
    }

    public function edit(Symptom $symptom)
    {
        $diagnostics = Diagnostic::with('patient')->get();
        $patients = Patient::all();
        $attached = $symptom->diagnostics()->pluck('diagnostics.id')->toArray();
        return view('symptoms.edit', compact('symptom', 'diagnostics', 'patients', 'attached'));
    }

    public function show(Symptom $symptom)
    {
        $symptom->load('patient', 'diagnostics');
        return view('symptoms.show', compact('symptom'));
    }

    public function update(Request $request, Symptom $symptom)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $symptom->update([
            'name' => $data['name'] ?? null,
            'description' => $data['description'] ?? null,
            'patient_id' => $data['patient_id'],
        ]);

        $diagId = $request->input('diagnostic_id');
        if ($diagId) {
            $symptom->diagnostics()->sync([$diagId]);
        }

        return redirect()->route('symptoms.index')->with('success', 'Síntoma actualizado.');
    }

    public function destroy(Symptom $symptom)
    {
        $symptom->delete();
        return redirect()->route('symptoms.index')->with('success', 'Síntoma eliminado.');
    }

    /**
     * Sugiere diagnósticos a partir del texto de síntoma (usa OpenAI si está configurado, sino fallback local).
     * Espera payload POST: { text: string, patient_id?: int }
     */
    public function suggest(Request $request)
    {
        $text = trim($request->input('text', ''));
        $patientId = $request->input('patient_id');
        if ($text === '') {
            return response()->json(['data' => []]);
        }

        // Si existe clave OPENAI_API_KEY en env, intentar llamada a OpenAI Chat API
        $openaiKey = env('OPENAI_API_KEY');
        if ($openaiKey) {
            try {
                $client = new Client(['base_uri' => 'https://api.openai.com/']);
                $prompt = "Eres un asistente médico (no reemplazas a un profesional). Recibe la siguiente descripción de síntomas y sugiere hasta 5 diagnósticos probables, cada uno con un título corto y una breve justificación. Responde en JSON con una clave 'suggestions' que sea una lista de objetos {title, confidence, reason}. Texto de síntomas: \n" . $text;
                $res = $client->post('v1/chat/completions', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $openaiKey,
                        'Content-Type' => 'application/json',
                    ],
                    'json' => [
                        'model' => 'gpt-4o-mini',
                        'messages' => [
                            ['role' => 'system', 'content' => 'Eres un asistente médico que sugiere diagnósticos (no das instrucciones peligrosas).'],
                            ['role' => 'user', 'content' => $prompt],
                        ],
                        'max_tokens' => 600,
                        'temperature' => 0.2,
                    ],
                    'timeout' => 10,
                ]);
                $body = json_decode((string)$res->getBody(), true);
                $textResp = $body['choices'][0]['message']['content'] ?? '';

                // Intentar parsear JSON si el modelo devolvió JSON literal
                $suggestions = [];
                if (Str::startsWith(trim($textResp), '{') || Str::startsWith(trim($textResp), '[')) {
                    $parsed = json_decode($textResp, true);
                    if (is_array($parsed)) {
                        if (isset($parsed['suggestions']) && is_array($parsed['suggestions'])) {
                            $suggestions = $parsed['suggestions'];
                        } else {
                            // intentar adaptar estructura simple
                            $suggestions = $parsed;
                        }
                    }
                } else {
                    // Si no es JSON, desglosar por líneas como fallback
                    $lines = preg_split('/\r?\n/', $textResp);
                    foreach ($lines as $ln) {
                        $ln = trim($ln);
                        if ($ln === '') continue;
                        $suggestions[] = ['title' => $ln, 'confidence' => null, 'reason' => null];
                    }
                }

                // intentar mapear sugerencias a diagnósticos generales existentes
                foreach ($suggestions as &$s) {
                    $s['diagnostic_id'] = $s['diagnostic_id'] ?? null;
                    $s['general_diagnostic_id'] = null;
                    $title = isset($s['title']) ? trim($s['title']) : null;
                    if ($title) {
                        $gd = GeneralDiagnostic::where('description', 'like', '%' . substr($title, 0, 100) . '%')->first();
                        if ($gd) {
                            $s['general_diagnostic_id'] = $gd->id;
                        }
                    }
                }

                return response()->json(['data' => $suggestions]);
            } catch (\Throwable $e) {
                // si falla la llamada a OpenAI, caer al fallback local
            }
        }

        // Fallback local: buscar diagnósticos generales que contengan palabras clave del texto
        $words = preg_split('/\s+/', strip_tags($text));
        $words = array_filter(array_map(function ($w) { return preg_replace('/[^\p{L}0-9_-]/u', '', $w); }, $words));
        if (empty($words)) {
            return response()->json(['data' => []]);
        }
        $diags = GeneralDiagnostic::with('symptoms')->get()->map(function ($d) use ($words) {
            $score = 0;
            $hay = [];
            foreach ($words as $w) {
                if ($w === '') continue;
                if (stripos($d->description, $w) !== false) { $score += 2; $hay[] = $w; }
                foreach ($d->symptoms as $s) {
                    if (stripos($s->name, $w) !== false || stripos($s->description ?? '', $w) !== false) { $score += 1; $hay[] = $s->name; }
                }
            }
            $d->score = $score;
            $d->matched = array_values(array_unique($hay));
            return $d;
        })->filter(function ($d) {
            return $d->score > 0;
        })->sortByDesc('score')->values()->take(10);

        $out = $diags->map(function ($d) {
            return [
                'title' => $d->description,
                'confidence' => null,
                'reason' => 'Coincidencias: ' . implode(', ', $d->matched),
                'diagnostic_id' => null,
                'general_diagnostic_id' => $d->id,
            ];
        });

        return response()->json(['data' => $out]);
    }
}
