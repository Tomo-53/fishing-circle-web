<?php

namespace App\Http\Requests;

use App\ValueObjects\GroupName;
use Illuminate\Foundation\Http\FormRequest;

class StoreGroupRequest extends FormRequest
{
    /**
     * グループ作成は認証済みであれば誰でも可能（ルートの auth ミドルウェアで担保）。
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:'.GroupName::MAX_LENGTH, 'unique:groups,name'],
        ];
    }
}
