<?php

namespace Gtd\Suda\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'=>'required|min:2|max:255|'.Rule::unique('roles')->ignore($this->id),
        ];
    }

    public function messages()
    {
        return [
            'name.required'=>'请输入角色名称',
            'name.unique'=>'角色名称已存在',
        ];
    }
}
