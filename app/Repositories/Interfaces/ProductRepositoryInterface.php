<?php
namespace App\Repositories\Interfaces;
use App\Models\Product;
use Illuminate\Support\Collection;
interface ProductRepositoryInterface
{
    public function GetAllProduct(): Collection;
    public function CreateProduct(array $data):Product;
    public function UpdateProduct(array $data, $id):Product;
    public function DeleteProduct($id);
    public function ShowProductById($id):Product;
    public function SearchProductByName($name):Product;
}
