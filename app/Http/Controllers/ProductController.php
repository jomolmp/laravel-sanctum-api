<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    private ProductRepositoryInterface $productRepository;
    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    public function index():Response
    {
        $pro=$this->productRepository->getAllProducts();
        return new Response($pro);
    }
    public function store(ProductCreateRequest $request):Response
    {
        
        $request -> validate([
            'name' => 'required',
            'slug' => 'required',
            'price' => 'required'
        ]);
        $pro=$this->productRepository->createProduct($request->all());
        return new Response($pro->toArray(),201);
        
    }
    public function show($id):Response
    {

        $pro=$this->productRepository->find_product_by_id($id);
        return new Response($pro);
    }
    public function update(ProductUpdateRequest $request, $id):Response
    {
        
        $pro=$this->productRepository->updateProduct($request->all(),$id);
        return new Response($pro->toArray(), 201);
    }
    public function destroy($id):void
    {
     $this->productRepository->deleteProduct($id);
    }
    
    public function search($name):Response
    {
        
        $pro=$this->productRepository->find_product_by_name($name);
        return new Response($pro);
    }
}
