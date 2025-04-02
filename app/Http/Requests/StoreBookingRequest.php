<?php

namespace App\Http\Requests;

/**
 * this request uses ApiRequest to make Laravel validation errors suitable for the API
 * here is the path: app\Http\Requests\ApiRequest.php
 */
class StoreBookingRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; 
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'customer' => 'required|string|max:255',
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
        ];
    }
}
