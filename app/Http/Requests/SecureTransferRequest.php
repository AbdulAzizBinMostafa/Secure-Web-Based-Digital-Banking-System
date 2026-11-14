<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class SecureTransferRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        // ABDUL AZIZ: Strict input validation preventing SQLi & XSS
        return [
            'account_number' => ['required', 'string', 'regex:/^DB[0-9]{9}$/'], // Exact bank format
            'amount' => ['required', 'numeric', 'min:1', 'max:500000'],
            'description' => ['required', 'string', 'max:100', 'regex:/^[a-zA-Z0-9\s]+$/'] // No special chars allowed
        ];
    }
}