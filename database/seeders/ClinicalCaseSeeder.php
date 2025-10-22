<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\ClinicalCase;

class ClinicalCaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClinicalCase::create([
            'title' => 'Infección respiratoria aguda',
            'title_ru' => 'Острая респираторная инфекция',
            'description' => 'Paciente adulto con fiebre, tos y malestar general de 3 días de evolución.',
            'description_ru' => 'Взрослый пациент с лихорадкой, кашлем и общим недомоганием в течение 3 дней.',
            'steps' => "Evaluar signos vitales\nSolicitar hemograma\nIniciar tratamiento sintomático",
            'steps_ru' => "Оценить жизненные показатели\nНазначить общий анализ крови\nНачать симптоматическое лечение",
            'solution' => 'Infección viral',
            'solution_ru' => 'Вирусная инфекция',
            'options' => json_encode(['Infección bacteriana','Infección viral','Alergia','Neumonía']),
            'options_ru' => json_encode(['Бактериальная инфекция','Вирусная инфекция','Аллергия','Пневмония']),
            'language' => 'es',
        ]);

        ClinicalCase::create([
            'title' => 'Paciente con poliuria y polidipsia',
            'title_ru' => 'Пациент с полиурией и полидипсией',
            'description' => 'Síntomas de hiperglucemia en paciente con antecedentes de diabetes tipo 2.',
            'description_ru' => 'Симптомы гипергликемии у пациента с диабетом 2 типа в анамнезе.',
            'steps' => "Medir glucemia capilar\nRevisar medicamentos\nConsiderar ajuste de insulina",
            'steps_ru' => "Измерить уровень глюкозы в капилляре\nПроверить лекарства\nРассмотреть коррекцию инсулина",
            'solution' => 'Hiperglucemia por mala adherencia',
            'solution_ru' => 'Гипергликемия из-за плохой приверженности',
            'options' => "Hiperglucemia por mala adherencia\nCetoacidosis diabética\nHipoglucemia\nInfección intercurrente",
            'options_ru' => "Гипергликемия из-за плохой приверженности\nДиабетический кетоацидоз\nГипогликемия\nСопутствующая инфекция",
            'language' => 'es',
        ]);

        ClinicalCase::create([
            'title' => 'Dolor torácico pleurítico',
            'title_ru' => 'Плевритическая боль в груди',
            'description' => 'Dolor localizado que empeora con la respiración profunda.',
            'description_ru' => 'Локализованная боль, усиливающаяся при глубоком дыхании.',
            'steps' => "Examinar auscultación\nSolicitar radiografía de tórax\nConsiderar analgesia",
            'steps_ru' => "Аускультация\nНазначить рентген грудной клетки\nРассмотреть назначение анальгетиков",
            'solution' => 'Pleuritis/pleuresía',
            'solution_ru' => 'Плеврит',
            'options' => json_encode(['Pleuritis','Infarto agudo de miocardio','Reflujo gastroesofágico','Costocondritis']),
            'options_ru' => json_encode(['Плеврит','Острый инфаркт миокарда','ГЭРБ','Костохондрит']),
            'language' => 'es',
        ]);
    }
}
