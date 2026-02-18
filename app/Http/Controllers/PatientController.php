<?php

namespace App\Http\Controllers;

use App\Exports\PatientsExport;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PatientController extends Controller
{
    public function index(Request $request): Response
    {
        $filters = [
            'name' => $request->string('name')->trim()->toString() ?: null,
            'cpf' => $request->string('cpf')->trim()->toString() ?: null,
            'health_indicators' => array_values(array_filter(
                array_map(fn (string $v) => trim($v) ?: null, $request->array('health_indicators', []) ?: [])
            )) ?: null,
        ];
        $filters = array_filter($filters);

        $query = $this->buildPatientQuery($filters);

        $patients = $query->orderBy('name')->paginate(15)->withQueryString()->through(function (Patient $patient): array {
            return $this->formatPatientForList($patient);
        });

        return Inertia::render('patients/Index', [
            'patients' => $patients,
            'filters' => $filters,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('patients/Create');
    }

    public function store(StorePatientRequest $request): RedirectResponse
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

        return redirect()->route('patients.index')->with('success', 'Paciente cadastrado com sucesso.');
    }

    public function edit(Patient $patient): Response
    {
        $patientData = [
            'id' => $patient->id,
            'name' => $patient->name,
            'cpf' => $this->formatCpf($patient->cpf),
            'date_of_birth' => $patient->date_of_birth->format('Y-m-d'),
            'gender' => $patient->gender,
            'address' => $patient->address,
            'neighborhood' => $patient->neighborhood,
            'city' => $patient->city,
            'weight' => $patient->weight !== null ? (string) $patient->weight : '',
            'height' => $patient->height !== null ? (string) $patient->height : '',
            'blood_pressure' => $patient->blood_pressure ?? '',
            'blood_glucose' => $patient->blood_glucose !== null ? (string) $patient->blood_glucose : '',
            'creatinine' => $patient->creatinine ?? '',
            'is_diabetic' => $patient->is_diabetic,
            'is_hypertensive' => $patient->is_hypertensive,
            'has_kidney_problem' => $patient->has_kidney_problem,
            'has_family_drc' => $patient->has_family_drc,
            'is_obese' => $patient->is_obese,
            'has_back_eye_exam' => $patient->has_back_eye_exam,
        ];

        return Inertia::render('patients/Edit', [
            'patient' => $patientData,
        ]);
    }

    public function update(UpdatePatientRequest $request, Patient $patient): RedirectResponse
    {
        $data = $request->validated();

        $booleanFields = [
            'is_diabetic', 'is_hypertensive', 'has_kidney_problem',
            'has_family_drc', 'is_obese', 'has_back_eye_exam',
        ];

        foreach ($booleanFields as $field) {
            $data[$field] = (bool) ($data[$field] ?? false);
        }

        $patient->update($data);

        return redirect()->route('patients.index')->with('success', 'Paciente atualizado com sucesso.');
    }

    public function destroy(Patient $patient): RedirectResponse
    {
        $patient->delete();

        return redirect()->route('patients.index')->with('success', 'Paciente excluído com sucesso.');
    }

    public function export(Request $request): BinaryFileResponse
    {
        $filters = [
            'name' => $request->string('name')->trim()->toString() ?: null,
            'cpf' => $request->string('cpf')->trim()->toString() ?: null,
            'health_indicators' => array_values(array_filter(
                array_map(fn (string $v) => trim($v) ?: null, $request->array('health_indicators', []) ?: [])
            )) ?: null,
        ];
        $filters = array_filter($filters);

        return Excel::download(
            new PatientsExport($filters),
            'pacientes-'.now()->format('Y-m-d-His').'.xlsx',
            \Maatwebsite\Excel\Excel::XLSX
        );
    }

    /**
     * @param  array<string, string|array<int, string>>  $filters
     */
    private function buildPatientQuery(array $filters): Builder
    {
        $query = Patient::query();

        if (! empty($filters['name'])) {
            $query->where('name', 'like', '%'.$filters['name'].'%');
        }

        if (! empty($filters['cpf'])) {
            $cpfDigits = preg_replace('/\D/', '', (string) $filters['cpf']);
            if ($cpfDigits !== '') {
                $query->where('cpf', 'like', '%'.$cpfDigits.'%');
            }
        }

        $indicators = $filters['health_indicators'] ?? [];
        if (is_array($indicators) && ! empty($indicators)) {
            $query->where(function (Builder $q) use ($indicators): void {
                $first = true;
                foreach ($indicators as $indicator) {
                    $column = match ($indicator) {
                        'diabetic' => 'is_diabetic',
                        'hypertensive' => 'is_hypertensive',
                        'renal' => 'has_kidney_problem',
                        'obesity' => 'is_obese',
                        default => null,
                    };
                    if ($column !== null) {
                        $first
                            ? $q->where($column, true)
                            : $q->orWhere($column, true);
                        $first = false;
                    }
                }
            });
        }

        return $query;
    }

    /**
     * @return array<string, mixed>
     */
    private function formatPatientForList(Patient $patient): array
    {
        $words = explode(' ', trim($patient->name));
        $initials = count($words) >= 2
            ? strtoupper(substr($words[0], 0, 1).substr($words[array_key_last($words)], 0, 1))
            : strtoupper(substr($patient->name, 0, 2));

        $healthIndicators = [];
        if ($patient->is_diabetic) {
            $healthIndicators[] = ['key' => 'diabetic', 'label' => 'Diabético', 'color' => 'blue'];
        }
        if ($patient->is_hypertensive) {
            $healthIndicators[] = ['key' => 'hypertensive', 'label' => 'Hipertenso', 'color' => 'red'];
        }
        if ($patient->has_kidney_problem) {
            $healthIndicators[] = ['key' => 'renal', 'label' => 'Problema Renal', 'color' => 'orange'];
        }
        if ($patient->is_obese) {
            $healthIndicators[] = ['key' => 'obesity', 'label' => 'Obesidade', 'color' => 'green'];
        }
        if (empty($healthIndicators)) {
            $healthIndicators[] = ['key' => 'none', 'label' => 'Nenhum indicador', 'color' => 'gray'];
        }

        return [
            'id' => $patient->id,
            'name' => $patient->name,
            'initials' => $initials,
            'cpf' => $this->formatCpf($patient->cpf),
            'date_of_birth' => $patient->date_of_birth->format('d/m/Y'),
            'age' => $patient->date_of_birth->age,
            'city' => $patient->city,
            'health_indicators' => $healthIndicators,
        ];
    }

    private function formatCpf(string $cpf): string
    {
        $digits = preg_replace('/\D/', '', $cpf);

        return preg_replace(
            '/^(\d{3})(\d{3})(\d{3})(\d{2})$/',
            '$1.$2.$3-$4',
            $digits
        ) ?: $cpf;
    }
}
