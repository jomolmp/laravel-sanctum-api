<?php
    declare(strict_types=1);
    namespace App\Http\Requests;
    use Illuminate\Foundation\Http\FormRequest;
    final class ProductUpdateRequest extends FormRequest
    {
        public function authorize():bool
        {  
            return true;
        }
    
        public function rules(): array
        {
         return[
            'name' =>'required|string',
            'slug' => 'required',
            'description' => 'required',
            'price' =>'required'
            ];
        }
    }
?>

