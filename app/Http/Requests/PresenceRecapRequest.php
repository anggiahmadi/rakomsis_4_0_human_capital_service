<?php

namespace App\Http\Requests;

use App\Models\PresenceRecap;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class PresenceRecapRequest extends FormRequest
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
            'code' => 'required|string|max:255',
            'location_name' => 'required|string|max:255',
            'division_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ];
    }

    public function store()
    {
        $this->validate([
            'code' => 'required|string|max:255|unique:presence_recaps,code,' . $this->id . ',id,tenant_id,' . $this->tenant_id,
        ]);

        $presenceRecap = PresenceRecap::create($this->validated());

        Log::info('PresenceRecap created with ID: ' . $presenceRecap->id);

        return $presenceRecap;
    }

    public function update(PresenceRecap $presenceRecap)
    {
        $this->validate([
            'code' => 'required|string|max:255|unique:presence_recaps,code,' . $presenceRecap->id . ',id,tenant_id,' . $this->tenant_id,
        ]);

        $presenceRecap->update($this->validated());

        Log::info('PresenceRecap updated with ID: ' . $presenceRecap->id);

        return $presenceRecap;
    }
}
