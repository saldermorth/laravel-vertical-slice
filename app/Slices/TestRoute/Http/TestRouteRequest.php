<?php

namespace App\Slices\TestRoute\Http;

use Illuminate\Foundation\Http\FormRequest;

class TestRouteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            // Add your validation rules here
        ];
    }
}