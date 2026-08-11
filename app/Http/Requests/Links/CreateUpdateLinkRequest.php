<?php

namespace App\Http\Requests\Links;

use Illuminate\Foundation\Http\FormRequest;

class CreateUpdateLinkRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $validationStrictness = $this->isMethod('patch')
            ? 'sometimes'
            : 'required';

        return [
            'entity_id' => $validationStrictness.'|integer|exists:entities,id',
            'title' => $validationStrictness.'|string|max:255',
            'url' => $validationStrictness.'|url',
        ];
    }
}
