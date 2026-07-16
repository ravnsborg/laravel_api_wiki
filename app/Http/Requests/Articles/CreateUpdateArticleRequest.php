<?php

namespace App\Http\Requests\Articles;

use App\Http\Requests\Traits\MapParametersTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUpdateArticleRequest extends FormRequest
{
    use MapParametersTrait;

    /*
     * Allowed optional parameters to return model relationships (param => model_relationship)
     */

    private const ALLOWED_INCLUDES = [
        'category' => 'category',
    ];

    public function rules(): array
    {
        $validationStrictness = $this->isMethod('patch')
            ? 'sometimes'
            : 'required';

        return [
            'category_id' => $validationStrictness.'|integer|exists:categories,id',
            'title' => $validationStrictness.'|string|max:255',
            'body' => $validationStrictness.'|string',
            'is_favorite' => 'sometimes|boolean',
            'include' => [
                'sometimes',
                'string',
                Rule::in(array_keys(self::ALLOWED_INCLUDES)),
            ],
        ];
    }
}
