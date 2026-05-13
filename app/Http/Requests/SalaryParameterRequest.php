<?php

namespace App\Http\Requests;

use App\Models\SalaryParameter;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class SalaryParameterRequest extends FormRequest
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
            'tenant_id' => 'required|integer',
            'location_id' => 'nullable|integer',
            'division_id' => 'nullable|integer',
            'location_name' => 'nullable|string|max:255',
            'division_name' => 'nullable|string|max:255',
            'allotment' => 'required|in:general,specific location,specific division',
            'type' => 'required|in:allowance,deduction',
            'name' => 'required|string|max:255',
            'calculation_method' => 'required|in:fixed,percentage,using parameters',
            'amount' => 'required_if:calculation_method,fixed|numeric',
            'percentage' => 'required_if:calculation_method,percentage|numeric',
            'parameters' => 'nullable|json',
        ];
    }

    public function store()
    {
        $salaryParameter = SalaryParameter::create($this->validated());

        Log::info('SalaryParameter created with ID: ' . $salaryParameter->id);

        return $salaryParameter;
    }

    public function update($id)
    {
        $salaryParameter = SalaryParameter::findOrFail($id);

        $salaryParameter->update($this->validated());

        Log::info('SalaryParameter updated with ID: ' . $salaryParameter->id);

        return $salaryParameter;
    }
}
