<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

/**
 * @codeCoverageIgnore
 */
class ApiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'validation failed',
            'errors'  => $validator->errors(),
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
