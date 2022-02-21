<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Repositories\Interfaces\ProductRepositoryInterface;

class ProductController extends Controller
{
    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository=$productRepository;
    }
    
    public function index():Response
    {
        $product=$this->productRepository->GetAllProduct();
        return new Response($product);
    }

    public function store(ProductCreateRequest $request):Response
    {
        $product = $this->productRepository->CreateProduct($request->all());
        return new Response($product->toArray(), 201);   
    }

    public function show($id):Response
    {
        $product = $this->productRepository->ShowProductById($id);
        return new Response($product);
    }

    public function update(ProductUpdateRequest $request, $id):Response
    {
        $product = $this->productRepository->UpdateProduct($request->all(), $id);
        return new Response($product->toArray(), 201);
    }

    public function destroy($id)
    {
        $product = $this->productRepository->DeleteProduct($id);
    }

    public function search($name):Response
    {
        $product = $this->productRepository->SearchProductByName($name);
        return new Response($product);
    }
}
?>