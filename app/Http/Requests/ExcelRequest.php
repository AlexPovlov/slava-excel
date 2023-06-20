<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExcelRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'file' => ['required', 'file', 'max:50000', 'mimes:doc,csv,xlsx,xls,docx,ppt,odt,ods,odp']
        ];
    }
}
