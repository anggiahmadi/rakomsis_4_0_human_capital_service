<?php

namespace App\Http\Requests;

use App\Models\WorkAgreement;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class WorkAgreementRequest extends FormRequest
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
            'location_id' => 'required|integer',
            'division_id' => 'required|integer',
            'employee_id' => 'required|integer',
            'code' => 'required|string|max:255',
            'location_name' => 'required|string|max:255',
            'division_name' => 'required|string|max:255',
            'employee_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'bank_account_number' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'yearly_leave_allowance' => 'required|integer|min:0',
            'is_active' => 'required|boolean'
        ];
    }

    public function store()
    {
        $this->validate([
            'code' => 'required|string|max:255|unique:work_agreements,code,' . $this->id . ',id,tenant_id,' . $this->tenant_id,
        ]);

        $workAgreement = WorkAgreement::create($this->validated());

        Log::info('WorkAgreement created with ID: ' . $workAgreement->id);

        return $workAgreement;
    }

    public function update(WorkAgreement $workAgreement)
    {
        $this->validate([
            'code' => 'required|string|max:255|unique:work_agreements,code,' . $workAgreement->id . ',id,tenant_id,' . $this->tenant_id,
        ]);

        $workAgreement->update($this->validated());

        Log::info('WorkAgreement updated with ID: ' . $workAgreement->id);

        return $workAgreement;
    }
}
