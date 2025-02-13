<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SearchClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'idcard' => 'nullable|string|max:255',
        ];
    }

    protected function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            if (!$this->filled('name') && !$this->filled('idcard')) {
                $validator->errors()->add('validation-error', 'Az ügyfél neve vagy az okmányazonosító megadása kötelező!');
            }

            if ($this->filled('name') && $this->filled('idcard')) {
                $validator->errors()->add('validation-error', 'Csak az egyik mező lehet kitöltve!');
            }
        });
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'A megadott adatok érvénytelenek.',
            'errors' => $validator->errors()
        ], 422));
    }
}
