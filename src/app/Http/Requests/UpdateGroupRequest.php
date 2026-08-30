<?php

namespace App\Http\Requests;

use App\ValueObjects\GroupName;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGroupRequest extends FormRequest
{
    /**
     * グループ更新の認可はルートの check.group.permission:4 ミドルウェアで担保される。
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
            'name' => [
                'required',
                'string',
                'max:'.GroupName::MAX_LENGTH,
                Rule::unique('groups')->ignore($this->route('group')),
            ],
        ];
    }
}
