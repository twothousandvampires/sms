<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status_id' => 'required|numeric',
            'assigned_id' => 'required|exists:users,id',
            'completion_date' => 'nullable|date',
            'attachment' => 'nullable|file|max:10240'
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Заголовок обязателен',
            'title.max' => 'Заголовок не должен превышать 255 символов',
            'description.required' => 'Описание обязательно',
            'status_id.required' => 'Статус обязателен',
            'status_id.number' => 'Статус должен быть числом',
            'assigned_id.exists' => 'Указанный исполнитель не найден',
            'attachment.file' => 'Файл должен быть валидным файлом',
            'attachment.max' => 'Размер файла не должен превышать 10MB'
        ];
    }
}
