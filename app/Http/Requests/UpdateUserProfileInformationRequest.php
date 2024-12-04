<?php

namespace App\Http\Requests;

use App\Rules\MatchUserPassword;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateUserProfileInformationRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => Str::title($this->name),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user()->id),
            ],
            'files' => [
                'nullable',
                'array',
                'max:1',
            ],
            'files.*' => [
                'file',
                'extensions:jpg,jpeg,png',
                'mimes:jpg,jpeg,png',
            ],
            'name' => [
                'nullable',
                'string',
                'min:3',
                'max:255',
            ],
            'password' => [
                'required_with:email',
                'string',
                new MatchUserPassword,
            ],
        ];
    }
}
