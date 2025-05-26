<?php
namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'          => 'sometimes|string|max:255',
            'content'        => 'sometimes|string',
            'image_url'      => 'nullable|url',
            'scheduled_time' => 'sometimes|date|after:now',
            'status'         => 'sometimes|in:draft,scheduled,published',
            'platform_ids'   => 'nullable|array',
            'platform_ids.*' => 'exists:platforms,id',
        ];
    }
}
