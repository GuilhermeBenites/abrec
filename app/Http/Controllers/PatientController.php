<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Models\Patient;
use Inertia\Inertia;
use Inertia\Response;

class PatientController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('patients/Create');
    }

    public function store(StorePatientRequest $request)
    {
        $data = $request->validated();

        $booleanFields = [
            'is_diabetic', 'is_hypertensive', 'has_kidney_problem',
            'has_family_drc', 'is_obese', 'has_back_eye_exam',
        ];

        foreach ($booleanFields as $field) {
            $data[$field] = (bool) ($data[$field] ?? false);
        }

        Patient::query()->create($data);

        return redirect()->back()->with('success', 'Paciente cadastrado com sucesso.');
    }
}
