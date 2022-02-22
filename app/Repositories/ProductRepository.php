<?php
declare(strict_types=1);
namespace App\Repositories;
use Illuminate\Support\Collection;
use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
class ProductRepository implements ProductRepositoryInterface
{
    public function GetAllProduct():Collection
    {
        return Product::all();
    }

    public function CreateProduct(array $data):Product
    {
        $product = new Product();
        $product->setAttribute('name', $data['name']);
        $product->setAttribute('slug', $data['slug']);
        $product->setAttribute('description', $data['description']);
        $product->setAttribute('price', $data['price']);
        $product->save();
        return $product;
    }

    public function UpdateProduct(array $data,$id):Product
    {
        $product=Product::find($id);
        $product->setAttribute('name', $data['name']);
        $product->setAttribute('slug', $data['slug']);
        $product->setAttribute('description', $data['description']);
        $product->setAttribute('price', $data['price']);
        $product->save();
        return $product;
    }

    public function DeleteProduct($id):void
    {
        Product::destroy($id);
    }
    
    public function ShowProductById($id):Product
    {
       $product=Product::find($id); 
       return $product;
    }  
    
    public function SearchProductByName($name): Product
    {
        $product=Product::find($name);
        return $product;
    }
}
?>