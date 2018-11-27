<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Lang;

class RegistrationRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|alpha_num|min:3|unique:users,name',
            'email'=> 'required|email|unique:users,email',
            'password'=>'required|confirmed|min:6|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            //Contain at least one uppercase/lowercase letters and one number
        ];
    }

    public function messages()
    {
        return [
            'password.regex' => Lang::get('validation.custom.custom_password.user'),
        ];
    }

    /**
     * Extra validation rules
     * @param $validator
     */
    public function withValidator($validator)
    {

        $validator->after(function ($validator) {
            if (! $this->nameStartsWithLetter($this->input('name'))) {
                $validator->errors()->add('error',Lang::get('validation.custom.custom_password.first_not_letter'));
            }
        });
    }

    /**
     * @param $name
     * Checks if the name starts with a letter
     * @return bool
     */
    public function nameStartsWithLetter($name)
    {
        return ctype_alpha($name[0]);
    }
}
