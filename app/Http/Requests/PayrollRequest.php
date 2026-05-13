<?php

namespace App\Http\Requests;

use App\Models\Payroll;
use App\Models\PayrollDetail;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PayrollRequest extends FormRequest
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
            'work_agreement_id' => 'required|exists:work_agreements,id',
            'payroll_date' => 'required|date',
            'amount' => 'required|numeric',
            'payroll_details' => 'array',
            'payroll_details.*.salary_parameter_id' => 'nullable|exists:salary_parameters,id',
            'payroll_details.*.parameter_name' => 'nullable|string',
            'payroll_details.*.type' => 'required|in:allowance,deduction',
            'payroll_details.*.amount' => 'required|numeric',
        ];
    }

    public function store()
    {
        DB::beginTransaction();

        $payroll = Payroll::store($this->validated());

        $payrollDetails = (!is_array($this->payroll_details)) ? json_decode($this->payroll_details) : json_decode(json_encode($this->payroll_details));

        $this->generateDetails($payroll, $payrollDetails);

        DB::commit();

        Log::info('Payroll created with ID: ' . $payroll->id);

        return $payroll;
    }

    public function update($id)
    {
        DB::beginTransaction();

        $payroll = Payroll::findOrFail($id);

        $payroll->updatePayroll($this->validated());

        $payrollDetails = (!is_array($this->payroll_details)) ? json_decode($this->payroll_details) : json_decode(json_encode($this->payroll_details));

        PayrollDetail::where('payroll_id', $id)->delete();

        $this->generateDetails($payroll, $payrollDetails);

        DB::commit();

        Log::info('Payroll updated with ID: ' . $payroll->id);

        return $payroll;
    }

    public function generateDetails($payroll, $payrollDetails)
    {
        foreach ($payrollDetails as $detail) {
            PayrollDetail::create([
                'tenant_id' => $payroll->tenant_id,
                'payroll_id' => $payroll->id,
                'salary_parameter_id' => $detail->salary_parameter_id,
                'parameter_name' => $detail->parameter_name,
                'type' => $detail->type,
                'amount' => $detail->amount,
            ]);
        }
    }
}
