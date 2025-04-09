<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\Domain\User\ValueObjects\Country;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Can be modified based on authorization requirements
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'regex:/^\+?[0-9]{10,15}$/'],
            'country' => ['required', 'string', 'in:' . implode(',', array_keys(Country::getValidCountries()))],
            'gender' => ['required', 'string', 'in:male,female,other'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'profile_picture' => ['nullable', 'image', 'max:2048'], // Max 2MB
            'introduction' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'surname.required' => 'The surname field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already in use.',
            'phone.required' => 'The phone field is required.',
            'phone.regex' => 'Please enter a valid phone number.',
            'country.required' => 'The country field is required.',
            'country.in' => 'Please select a valid country.',
            'gender.required' => 'The gender field is required.',
            'gender.in' => 'Please select a valid gender.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            'profile_picture.image' => 'The profile picture must be an image.',
            'profile_picture.max' => 'The profile picture must not be larger than 2MB.',
            'introduction.max' => 'The introduction must not exceed 1000 characters.',
        ];
    }
} 