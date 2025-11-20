<?php

namespace App\Http\Controllers;

use App\Models\ClinicalCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ClinicalCaseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $cases = ClinicalCase::latest()->paginate(20);
        return view('clinical_cases.index', compact('cases'));
    }

    public function create()
    {
        return view('clinical_cases.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            // Require Spanish title for admin input
            'title_es' => 'required|string|max:255',
            'description_es' => 'nullable|string',
            'steps_es' => 'nullable|string',
            'language' => 'nullable|string',
            'solution_es' => 'nullable|string',
            'options_es' => 'nullable|string',
            'correct_index' => 'nullable|integer',
            // Spanish fields
            // legacy/other fields allowed but not required
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'steps' => 'nullable|string',
            'solution' => 'nullable|string',
        ]);
        $data['created_by'] = Auth::id();
        // Ensure main generic fields are populated for backward compatibility
        $data['title'] = $data['title_es'] ?? ($data['title'] ?? null);
        $data['description'] = $data['description_es'] ?? ($data['description'] ?? null);
        $data['steps'] = $data['steps_es'] ?? ($data['steps'] ?? null);
        $data['solution'] = $data['solution_es'] ?? ($data['solution'] ?? null);

        // Handle options: if admin provided options_es as textarea (one per line), convert to JSON and store in 'options' and keep raw in options_es
        if (!empty($data['options_es'])) {
            // normalize lines and filter empty
            $lines = preg_split('/\r\n|\r|\n/', trim($data['options_es']));
            $lines = array_values(array_filter(array_map('trim', $lines), function ($v) { return $v !== ''; }));
            $data['options'] = json_encode($lines, JSON_UNESCAPED_UNICODE);
            $data['options_es'] = implode("\n", $lines);
        }
        // Attempt to translate Spanish fields to Russian and save into _ru fields
        try {
            if (!empty($data['title_es'])) {
                $t = $this->translateText($data['title_es']);
                if (is_string($t)) {
                    $data['title_ru'] = mb_strlen($t) > 255 ? mb_substr($t, 0, 252) . '...' : $t;
                }
            }
            if (!empty($data['description_es'])) {
                $t = $this->translateText($data['description_es']);
                if (is_string($t)) {
                    $data['description_ru'] = mb_strlen($t) > 2000 ? mb_substr($t, 0, 1997) . '...' : $t;
                }
            }
            if (!empty($data['steps_es'])) {
                $t = $this->translateText($data['steps_es']);
                if (is_string($t)) {
                    $data['steps_ru'] = mb_strlen($t) > 4000 ? mb_substr($t, 0, 3997) . '...' : $t;
                }
            }
            if (!empty($data['solution_es'])) {
                $t = $this->translateText($data['solution_es']);
                if (is_string($t)) {
                    $data['solution_ru'] = mb_strlen($t) > 1000 ? mb_substr($t, 0, 997) . '...' : $t;
                }
            }
            if (!empty($lines) && is_array($lines)) {
                $translatedOptions = [];
                foreach ($lines as $opt) {
                    $topt = $this->translateText($opt);
                    if (is_string($topt)) {
                        $translatedOptions[] = mb_strlen($topt) > 255 ? mb_substr($topt, 0, 252) . '...' : $topt;
                    }
                }
                if (count($translatedOptions)) {
                    $data['options_ru'] = json_encode($translatedOptions, JSON_UNESCAPED_UNICODE);
                }
            }
        } catch (\Throwable $e) {
            // If translation fails, continue without ru fields (don't block admin)
        }

        ClinicalCase::create($data);
    return redirect()->route('clinical_cases.index')->with('success', 'Caso clínico creado.');
    }

    public function edit(ClinicalCase $clinical_case)
    {
        return view('clinical_cases.edit', ['case' => $clinical_case]);
    }

    public function update(Request $request, ClinicalCase $clinical_case)
    {
        $data = $request->validate([
            // Require Spanish title for admin edits
            'title_es' => 'required|string|max:255',
            'description_es' => 'nullable|string',
            'steps_es' => 'nullable|string',
            'language' => 'nullable|string',
            'solution_es' => 'nullable|string',
            'options_es' => 'nullable|string',
            'correct_index' => 'nullable|integer',
            // legacy/other fields allowed but not required
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'steps' => 'nullable|string',
            'solution' => 'nullable|string',
        ]);
        // Keep generic fields in sync with Spanish values for compatibility
        $data['title'] = $data['title_es'] ?? ($data['title'] ?? $clinical_case->title);
        $data['description'] = $data['description_es'] ?? ($data['description'] ?? $clinical_case->description);
        $data['steps'] = $data['steps_es'] ?? ($data['steps'] ?? $clinical_case->steps);
        $data['solution'] = $data['solution_es'] ?? ($data['solution'] ?? $clinical_case->solution);

        if (!empty($data['options_es'])) {
            $lines = preg_split('/\r\n|\r|\n/', trim($data['options_es']));
            $lines = array_values(array_filter(array_map('trim', $lines), function ($v) { return $v !== ''; }));
            $data['options'] = json_encode($lines, JSON_UNESCAPED_UNICODE);
            $data['options_es'] = implode("\n", $lines);
        }

        // Update Russian translations when Spanish fields exist
        try {
            if (!empty($data['title_es'])) {
                $t = $this->translateText($data['title_es']);
                if (is_string($t)) {
                    $data['title_ru'] = mb_strlen($t) > 255 ? mb_substr($t, 0, 252) . '...' : $t;
                }
            }
            if (!empty($data['description_es'])) {
                $t = $this->translateText($data['description_es']);
                if (is_string($t)) {
                    $data['description_ru'] = mb_strlen($t) > 2000 ? mb_substr($t, 0, 1997) . '...' : $t;
                }
            }
            if (!empty($data['steps_es'])) {
                $t = $this->translateText($data['steps_es']);
                if (is_string($t)) {
                    $data['steps_ru'] = mb_strlen($t) > 4000 ? mb_substr($t, 0, 3997) . '...' : $t;
                }
            }
            if (!empty($data['solution_es'])) {
                $t = $this->translateText($data['solution_es']);
                if (is_string($t)) {
                    $data['solution_ru'] = mb_strlen($t) > 1000 ? mb_substr($t, 0, 997) . '...' : $t;
                }
            }
            if (!empty($lines) && is_array($lines)) {
                $translatedOptions = [];
                foreach ($lines as $opt) {
                    $topt = $this->translateText($opt);
                    if (is_string($topt)) {
                        $translatedOptions[] = mb_strlen($topt) > 255 ? mb_substr($topt, 0, 252) . '...' : $topt;
                    }
                }
                if (count($translatedOptions)) {
                    $data['options_ru'] = json_encode($translatedOptions, JSON_UNESCAPED_UNICODE);
                }
            }
        } catch (\Throwable $e) {
            // ignore translation errors
        }

        $clinical_case->update($data);
    return redirect()->route('clinical_cases.index')->with('success', 'Caso clínico actualizado.');
    }

    public function destroy(ClinicalCase $clinical_case)
    {
        $clinical_case->delete();
    return back()->with('success', 'Caso clínico eliminado.');
    }

    /**
     * Translate text from source to target using configured translation API.
     * Falls back to LibreTranslate public instance if no URL provided.
     */
    private function translateText(string $text, string $source = 'es', string $target = 'ru')
    {
        $text = trim($text);
        if ($text === '') return null;

        $apiUrl = env('TRANSLATE_API_URL', 'https://libretranslate.de/translate');
        $apiKey = env('TRANSLATE_API_KEY', null);

        $payload = [
            'q' => $text,
            'source' => $source,
            'target' => $target,
            'format' => 'text'
        ];
        if ($apiKey) {
            $payload['api_key'] = $apiKey;
        }

        $response = Http::timeout(10)->post($apiUrl, $payload);

        // Only accept JSON responses from the translation API. If we receive HTML
        // (e.g. the web page) or other unexpected content, treat it as an error so
        // we don't store huge HTML into DB columns.
        $contentType = strtolower($response->header('Content-Type') ?? '');

        if (strpos($contentType, 'application/json') !== false) {
            $json = $response->json();
            if (is_array($json)) {
                if (isset($json['translatedText']) && is_string($json['translatedText'])) return $json['translatedText'];
                if (isset($json['result']) && is_string($json['result'])) return $json['result'];
            }
        }

        // Try a safe json decode of body as fallback
        $decoded = @json_decode($response->body(), true);
        if (is_array($decoded)) {
            if (isset($decoded['translatedText']) && is_string($decoded['translatedText'])) return $decoded['translatedText'];
            if (isset($decoded['result']) && is_string($decoded['result'])) return $decoded['result'];
        }

        throw new \Exception('Translation API returned unexpected content.');
    }
}
