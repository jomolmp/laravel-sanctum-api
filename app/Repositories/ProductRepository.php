<?php 
//declare(strict_types=1);
namespace App\Repositories;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\Collection;
use App\Models\Product;
use Facade\FlareClient\Http\Response;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAllProducts():Collection
    {
        return Product::all();
    }
    public function createProduct(array $data):Product
    {
        $pro=new Product();
        $pro->setAttribute('name',$data['name']);
        $pro->setAttribute('slug',$data['slug']);
        $pro->setAttribute('description',$data['description']);
        $pro->setAttribute('price',$data['price']);
        $pro->save();
        return $pro;
    }
    public function find_product_by_id($id):Product
    {
        $pro=Product::find($id);
        return $pro;
    }
    public function find_product_by_name($name):Product
    {
        $pro=Product::find($name);
        return $pro;
    }
    public function updateProduct(array $data, $id):Product
    {
        $pro=Product::find($id);
        $pro->setAttribute('name',$data['name']);
        $pro->setAttribute('slug', $data['slug']);
        $pro->setAttribute('description', $data['description']);
        $pro->setAttribute('price',$data['price']);
        $pro->save();
        return $pro;
    }
    public function deleteProduct($id):void
    {
        Product::destroy($id);
    }
}

