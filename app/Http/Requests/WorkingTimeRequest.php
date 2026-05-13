<?php

namespace App\Http\Requests;

use App\Models\WorkingTime;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class WorkingTimeRequest extends FormRequest
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
            'division_id' => 'nullable|integer',
            'employee_id' => 'nullable|integer',
            'location_name' => 'required|string|max:255',
            'division_name' => 'nullable|string|max:255',
            'employee_name' => 'nullable|string|max:255',
            'type' => 'required|in:office hours,shift',
            'allotment' => 'required|in:general,specific division,specific employee',
            'office_hours' => 'nullable|json', // Should be a JSON string representing office hours
            'shift_started_at' => 'nullable|date_format:Y-m-d H:i:s',
            'shift_ended_at' => 'nullable|date_format:Y-m-d H:i:s|after:shift_started_at',
        ];
    }

    public function store()
    {
        $workingTime = WorkingTime::create($this->validated());

        Log::info('WorkingTime created with ID: ' . $workingTime->id);

        return $workingTime;
    }

    public function update($id)
    {
        $workingTime = WorkingTime::findOrFail($id);

        $workingTime->update($this->validated());

        Log::info('WorkingTime updated with ID: ' . $workingTime->id);

        return $workingTime;
    }
}
