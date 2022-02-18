<?php 
namespace App\Repositories\Interfaces;
use Illuminate\Support\Collection;
use App\Models\Product;

interface ProductRepositoryInterface
{
    public function getAllProducts():Collection;
    public function createProduct(array $data):Product;
    public function find_product_by_id($id):Product;
    public function find_product_by_name($name):Product;
    public function updateProduct(array $data, $id):Product;
    public function deleteProduct($id):void;
}