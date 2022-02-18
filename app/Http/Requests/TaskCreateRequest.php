<?php
    declare(strict_types=1);

    namespace App\Http\Requests;

    use Illuminate\Foundation\Http\FormRequest;
    use Illuminate\Support\Facades\Session;

    final class TaskCreateRequest extends FormRequest

    {
        public function authorize():bool
        {
          
            return true;
        }
    
        public function rules(): array
        {
         return[
            'name' =>'required|string',
            'description' => 'required',
            'status' => 'required'
            ];
        }
    }

    

