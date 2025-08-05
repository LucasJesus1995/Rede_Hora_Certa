<?php

namespace App\Http\Requests;

class AtendimentoLaudoUploadRequest extends Request
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
            'atendimento_laudo' => 'required|exists:atendimento_laudo,id',
            'file' => 'required|image|mimes:jpeg,png',
        ];
    }
}
