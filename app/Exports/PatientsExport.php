<?php

namespace App\Exports;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PatientsExport implements FromCollection, WithHeadings
{
    /**
     * @param  array{name?: string, cpf?: string, health_indicators?: array<int, string>}  $filters
     */
    public function __construct(
        private readonly array $filters = []
    ) {}

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public function collection(): Collection
    {
        $query = $this->buildQuery();

        return $query->get()->map(function (Patient $patient): array {
            return [
                'id' => $patient->id,
                'nome' => $patient->name,
                'cpf' => $this->formatCpf($patient->cpf),
                'data_nascimento' => $patient->date_of_birth->format('d/m/Y'),
                'sexo' => $patient->gender === 'male' ? 'Masculino' : 'Feminino',
                'endereco' => $patient->address,
                'bairro' => $patient->neighborhood,
                'cidade' => $patient->city,
                'peso' => $patient->weight,
                'altura' => $patient->height,
                'pressao_arterial' => $patient->blood_pressure,
                'glicemia' => $patient->blood_glucose,
                'creatinina' => $patient->creatinine,
                'diabetico' => $patient->is_diabetic ? 'Sim' : 'Não',
                'hipertenso' => $patient->is_hypertensive ? 'Sim' : 'Não',
                'problema_renal' => $patient->has_kidney_problem ? 'Sim' : 'Não',
                'drc_familia' => $patient->has_family_drc ? 'Sim' : 'Não',
                'obesidade' => $patient->is_obese ? 'Sim' : 'Não',
                'exame_fundo_olho' => $patient->has_back_eye_exam ? 'Sim' : 'Não',
            ];
        });
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nome',
            'CPF',
            'Data de Nascimento',
            'Sexo',
            'Endereço',
            'Bairro',
            'Cidade',
            'Peso (kg)',
            'Altura (cm)',
            'Pressão Arterial',
            'Glicemia (mg/dL)',
            'Creatinina',
            'Diabético',
            'Hipertenso',
            'Problema Renal',
            'D.R.C na Família',
            'Obesidade',
            'Exame Fundo de Olho',
        ];
    }

    private function buildQuery(): Builder
    {
        $query = Patient::query()->orderBy('name');

        if (! empty($this->filters['name'])) {
            $query->where('name', 'like', '%'.$this->filters['name'].'%');
        }

        if (! empty($this->filters['cpf'])) {
            $cpfDigits = preg_replace('/\D/', '', $this->filters['cpf']);
            if ($cpfDigits !== '') {
                $query->where('cpf', 'like', '%'.$cpfDigits.'%');
            }
        }

        $indicators = $this->filters['health_indicators'] ?? [];
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
