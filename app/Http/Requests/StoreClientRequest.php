<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        // obtener id del cliente si viene (en update via route-model-binding)
        $clientId = null;
        if ($this->route('client')) {
            // $this->route('client') puede ser instancia del modelo o el id
            $client = $this->route('client');
            $clientId = is_object($client) && isset($client->id) ? $client->id : $client;
        }

        return [
            'name' => 'required|string|max:255',
            // unique con ignore para evitar colisión al actualizar
            'document_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('clients', 'document_number')->ignore($clientId),
            ],
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
        ];
    }
}