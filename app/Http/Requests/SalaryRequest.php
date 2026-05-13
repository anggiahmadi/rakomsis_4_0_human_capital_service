<?php

namespace App\Http\Requests;

use App\Models\Salary;
use App\Models\WorkAgreement;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class SalaryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tenant_id' => 'required',
            'work_agreement_id' => 'required|exists:work_agreements,id',
            'employee_name' => 'required|string|max:255',
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|json',
            'deductions' => 'nullable|json',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
            'payment_frequency' => 'required|in:weekly,biweekly,monthly,yearly',
        ];
    }

    public function store()
    {
        $workAgreement = WorkAgreement::find($this->work_agreement_id);

        $this->merge([
            'employee_id' => $workAgreement->employee_id,
        ]);

        $salary = Salary::create($this->validated());

        Log::info('Salary created with ID: ' . $salary->id);

        return $salary;
    }

    public function update(Salary $salary)
    {
        $workAgreement = WorkAgreement::find($this->work_agreement_id);

        $this->merge([
            'employee_id' => $workAgreement->employee_id,
        ]);

        $salary->update($this->validated());

        Log::info('Salary updated with ID: ' . $salary->id);

        return $salary;
    }
}
