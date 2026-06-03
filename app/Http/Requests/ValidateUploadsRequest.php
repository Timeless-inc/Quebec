<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidateUploadFile;

class ValidateUploadsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // permitir PDF maior (5MB) quando for processamento via encaminhamento
            'anexos.*' => ['nullable','file', new ValidateUploadFile(['pdf' => config('validation.file_limits.pdf_process', 5120)])],
            'anexarArquivos.*' => ['nullable','file', new ValidateUploadFile()],
            'anexos_finalizacao.*' => ['nullable','file', new ValidateUploadFile()],
            'name_document' => ['nullable','file', new ValidateUploadFile()],
            'matricula_document' => ['nullable','file', new ValidateUploadFile()],
            'cpf_document' => ['nullable','file', new ValidateUploadFile()],
            'rg_document' => ['nullable','file', new ValidateUploadFile()],
        ];
    }
}
