<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'organizer_id'   => ['required', 'exists:users,id'],
            'category_id'    => ['nullable', 'exists:categories,id'],
            'venue_id'       => ['nullable', 'exists:venues,id'],
            'title'          => ['required', 'string', 'max:200'],
            'description'    => ['nullable', 'string'],
            'banner_image'   => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:5120'],
            'start_datetime' => ['required', 'date', 'after:now'],
            'end_datetime'   => ['required', 'date', 'after:start_datetime'],
            'max_capacity'   => ['nullable', 'integer', 'min:1'],
            'is_free'        => ['boolean'],
            'fee_amount'     => ['nullable', 'numeric', 'min:0'],
            'status'         => ['required', 'in:draft,published,ongoing,completed,cancelled'],
        ];
    }
}
