<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateReceivingRequest extends FormRequest {

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
			'received_date' => 'required', 
            'delivery_id' => 'required', 
		];
	}

	public function attributes()
	{
		return [
			'received_date' => 'Receive date',
			'delivery_id' => 'Source Document',
		];
	}

}
