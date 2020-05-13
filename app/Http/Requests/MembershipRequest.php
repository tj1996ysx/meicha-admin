<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Permission;
use Illuminate\Foundation\Http\FormRequest;

class MembershipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
//        return backpack_auth()->check();
        return $this->user()->can(Permission::DATA_MANAGEMENT);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:1|max:191',
            'prefix' => 'required|min:2|max:6',
            'description' => 'sometimes|max:256',
            'price' => 'required|numeric|min:0|max:10000',
            'rebate' => 'required|numeric|min:0|max:0.8',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
        ];
    }
}
