<?php

namespace App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StorePropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
      return Auth::hasUser();
        
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => ['required', 'string', 'max:128'],
            'description' => ['required', 'string'],
            'room_number' => ['required', 'integer'],
            'bath_number' => ['required', 'integer'],
            'mq2' => ['required', 'integer'],
            'address' => ['required', 'string'],
            'img' => ['sometimes', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'visible' => ['required', 'boolean'],
            'price_per_night' => ['required', 'numeric', 'digits_between:1,4'],
            'bed_number' => ['required', 'integer'],
            'services' => ['nullable', 'array'],
            'services.*' => ['integer', 'exists:services,id'],
      
        ];
    }
}