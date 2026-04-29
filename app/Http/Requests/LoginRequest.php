<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required'],
        ]; 
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $email = $this->input('email');
                $password = $this->input('password');
                
                if ($email && $password) {
                    $user = User::where('email', $email)->first();
                    
                    if ($user && !Hash::check($password, $user->password)) {
                        $validator->errors()->add(
                            'email',
                            'The provided credentials do not match our records.'
                        );
                    }
                }
            },
        ];
    }
}
