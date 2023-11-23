<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreLevelRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'start_points' => 'required|numeric|min:0',
            'end_points' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpg,png,jpeg',
            'name' => 'required|string',
            'color' => 'required|string',
            'feature_ids' => ['required' , 'array'],
            'feature_ids.*' => [Rule::exists('features' , 'id')]
        ];
    }
}
