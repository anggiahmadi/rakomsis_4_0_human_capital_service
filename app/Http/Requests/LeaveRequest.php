<?php

namespace App\Http\Requests;

use App\Models\Leave;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class LeaveRequest extends FormRequest
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
            'employee_id' => 'required|integer',
            'leave_type_id' => 'required|integer',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
            'reason' => 'nullable|string|max:255',
        ];
    }

    public function store()
    {
        $leave = Leave::create($this->validated());

        Log::info('Leave created with ID: ' . $leave->id);

        return $leave;
    }

    public function update($id)
    {
        $leave = Leave::findOrFail($id);

        $leave->update($this->validated());

        Log::info('Leave updated with ID: ' . $leave->id);

        return $leave;
    }
}
