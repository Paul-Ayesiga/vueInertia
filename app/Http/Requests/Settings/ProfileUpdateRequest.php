<?php

namespace App\Http\Requests\Settings;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()?->id)],
            'stageName' => ['sometimes', 'required', 'string', 'max:255'],
            'bio' => ['sometimes', 'required', 'string', 'min:50'],
            'phoneNumber' => ['sometimes', 'required', 'string', 'min:10'],
            'gender' => ['sometimes', 'required', 'string', 'in:male,female,other'],
            'nationality' => ['sometimes', 'required', 'string', 'max:255'],
            'country' => ['sometimes', 'required', 'string', 'max:255'],
            'region' => ['sometimes', 'required', 'string', 'max:255'],
            'address' => ['sometimes', 'required', 'string', 'max:255'],
            'profilePhoto' => ['sometimes', 'nullable', 'image', 'max:2048'],
            'coverPhoto' => ['sometimes', 'nullable', 'image', 'max:4096'],
            'socialMediaLinks' => ['sometimes', 'required', 'array', 'min:1'],
            'socialMediaLinks.*.platform' => ['required_with:socialMediaLinks', 'string', 'in:instagram,twitter,facebook,tiktok,youtube'],
            'socialMediaLinks.*.url' => ['required_with:socialMediaLinks', 'url'],
            'musicLinks' => ['sometimes', 'required', 'array', 'min:1'],
            'musicLinks.*.platform' => ['required_with:musicLinks', 'string', 'in:spotify,apple,youtube,soundcloud,other'],
            'musicLinks.*.url' => ['required_with:musicLinks', 'url'],
        ];
    }
}
