<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostBookRequest extends FormRequest
{
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
        // @TODO implement
        return [
            'isbn' => 'required|string|size:13|unique:books',
            'title' => 'required',
            'description' => 'required',
            'published_year' => 'required|integer|between:1900,2020',
            'authors' => 'required|array',
            'authors.*' => 'required|integer|exists:authors,id',
        ];
    }
}
