<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Models\Permission;
use Illuminate\Foundation\Http\FormRequest;

class HospitalRequest extends FormRequest
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
             'name' => 'required|min:4|max:191',
             'description' => 'sometimes|max:255',
//             'level' => 'required',
             'address' => 'sometimes|max:191',
             'telephone' => 'sometimes|max:20',
             'contact_user' => 'sometimes|max:20',
             'contact_number' => 'sometimes|max:20',
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
