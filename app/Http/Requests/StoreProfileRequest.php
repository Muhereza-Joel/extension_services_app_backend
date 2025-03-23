<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProfileRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fullname' => 'required|string|max:100',
            'phone_number'  => 'required|string|max:20',
            'nin' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|string|in:male,female',
            'country' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'village' => 'required|string|max:100',
            'user_id' => 'required|numeric|exists:users,id'
        ];
    }

    public function messages(): array
    {
        return [
            'fullname.required' => 'The full name is required.',
            'fullname.max' => 'The full name must not exceed 100 characters.',
            'phone_number.required' => 'The phone number is required.',
            'phone_number.max' => 'The phone number must not exceed 20 characters.',
            'nin.required' => 'The National Identification Number (NIN) is required.',
            'nin.max' => 'The National Identification Number (NIN) must not exceed 20 characters.',
            'date_of_birth.required' => 'The date of birth is required.',
            'date_of_birth.date' => 'The date of birth must be a valid date.',
            'date_of_birth.before' => 'The date of birth must be a date in the past.',
            'gender.required' => 'The gender is required.',
            'gender.in' => 'The gender must be either "male" or "female".',
            'country.required' => 'The country is required.',
            'country.max' => 'The country must not exceed 100 characters.',
            'district.required' => 'The district is required.',
            'district.max' => 'The district must not exceed 100 characters.',
            'village.required' => 'The village is required.',
            'village.max' => 'The village must not exceed 100 characters.',
            'user_id.required' => 'The user ID is required.',
            'user_id.numeric' => 'The user ID must be a number.',
            'user_id.exists' => 'The selected user ID does not exist.'
        ];
    }
}
