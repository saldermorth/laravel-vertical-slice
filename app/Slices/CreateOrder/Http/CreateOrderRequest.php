<?php

namespace App\Slices\CreateOrder\Http;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            // Add your validation rules here
        ];
    }
}