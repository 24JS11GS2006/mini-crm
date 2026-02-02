<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:open,in_progress,closed',
            'priority' => 'required|in:low,medium,high',
            // Aceptamos opened_at y closed_at como opcionales y como fechas
            // 'date' valida strings que PHP/Carbon pueden parsear.
            'opened_at' => 'nullable|date',
            'closed_at' => 'nullable|date',
        ];
    }
}