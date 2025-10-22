<?php

namespace App\Http\Controllers;

use App\Models\ClinicalCase;
use Illuminate\Http\Request;

class VisitorTrainingController extends Controller
{
    // public page for visitors (martians) to see cases in Russian
    public function trainingRu()
    {
        $cases = ClinicalCase::where('language', 'ru')
            ->orWhereNotNull('title_ru')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('visitors.training_ru', compact('cases'));
    }

    // show single case to attempt
    public function show(ClinicalCase $clinical_case)
    {
        return view('visitors.case_show_ru', ['case' => $clinical_case]);
    }

    // process an attempt/answer from visitor
    public function attempt(Request $request, ClinicalCase $clinical_case)
    {
        $data = $request->validate([
            'answer' => 'required|string',
        ]);

        $correct = false;
        $expected = $clinical_case->solution_ru ?: $clinical_case->solution ?: $clinical_case->solution_es;
        if ($expected && trim(mb_strtolower($data['answer'])) === trim(mb_strtolower($expected))) {
            $correct = true;
        }

        return back()->with('attempt_result', $correct ? 'correct' : 'incorrect');
    }
}
