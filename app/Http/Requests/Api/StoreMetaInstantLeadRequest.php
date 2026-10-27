<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreMetaInstantLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'meta_lead_id' => $this->input('meta_lead_id')
                ?: $this->input('lead_id')
                ?: $this->input('leadgen_id'),
            'phone' => $this->input('phone')
                ?: $this->input('phone_number')
                ?: $this->input('mobile'),
        ]);
    }

    public function rules(): array
    {
        return [
            'meta_lead_id' => ['required', 'string', 'max:20'],
            'created_time' => ['nullable', 'date'],
            'form_id' => ['required', 'string', 'max:64'],
            'form_name' => ['nullable', 'string', 'max:191'],
            'page_id' => ['nullable', 'string', 'max:64'],

            'kids_first_name' => ['required_without:full_name', 'nullable', 'string', 'max:191'],
            'kids_last_name' => ['nullable', 'string', 'max:191'],
            'full_name' => ['required_without:kids_first_name', 'nullable', 'string', 'max:191'],
            'parent_name' => ['nullable', 'string', 'max:191'],
            'email' => ['required', 'email', 'max:191'],
            'phone' => ['required', 'string', 'max:32', 'regex:/^\+?[0-9][0-9\s().-]{6,30}$/'],
            'country_code' => ['nullable', 'string', 'max:8', 'regex:/^\+?[0-9]{1,7}$/'],
            'country' => ['required', 'string', 'max:191'],
            'city' => ['nullable', 'string', 'max:191'],
            'age' => ['nullable', 'integer', 'min:3', 'max:99'],
            'timezone' => ['nullable', 'string', 'max:191'],
            'language_preference' => ['nullable', 'string', 'max:191'],
            'level' => ['nullable', 'string', 'max:191'],

            'campaign_id' => ['nullable', 'string', 'max:64'],
            'campaign_name' => ['nullable', 'string', 'max:191'],
            'adset_id' => ['nullable', 'string', 'max:64'],
            'adset_name' => ['nullable', 'string', 'max:191'],
            'ad_id' => ['nullable', 'string', 'max:64'],
            'ad_name' => ['nullable', 'string', 'max:191'],
        ];
    }
}
