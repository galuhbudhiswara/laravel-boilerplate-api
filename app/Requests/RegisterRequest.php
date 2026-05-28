<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => 'required|string|min:3|max:255',
            'email'         => 'required|email|unique:tbl_users,email',
            'password'      => 'required|string|min:6|confirmed',
            'primary_role'  => 'required|uuid',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'         => 'Name is required.',
            'name.min'              => 'Name must be at least 3 characters.',
            'email.required'        => 'Email is required.',
            'email.email'           => 'Email must be a valid email address.',
            'email.unique'          => 'Email is already taken.',
            'password.required'     => 'Password is required.',
            'password.min'          => 'Password must be at least 6 characters.',
            'password.confirmed'    => 'Password confirmation does not match.',
            'primary_role.required' => 'Primary Role is required.',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new BadRequestHttpException($validator->errors()->first());
    }
}