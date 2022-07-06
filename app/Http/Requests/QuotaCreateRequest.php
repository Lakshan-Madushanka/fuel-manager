<?php

namespace App\Http\Requests;

use App\Enums\Quota\Basis;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class QuotaCreateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'basis' => ['required', new Enum(Basis::class)],
            'regular_amount' => ['required', 'integer', 'gt:0'],
            'special_amount' => ['required', 'integer', 'gt:0'],
        ];
    }
}
