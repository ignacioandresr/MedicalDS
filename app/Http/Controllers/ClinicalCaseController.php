<?php

namespace App\Http\Controllers;

use App\Models\ClinicalCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $clinical_case->update($data);
    return redirect()->route('clinical_cases.index')->with('success', 'Caso clínico actualizado.');
    }

    public function destroy(ClinicalCase $clinical_case)
    {
        $clinical_case->delete();
    return back()->with('success', 'Caso clínico eliminado.');
    }
}
