<?php

namespace App\Slices\Order\Http;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            // Add your validation rules here
        ];
    }
}