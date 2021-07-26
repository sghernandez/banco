<?php
    
namespace App\Http\Controllers;
    
use App\Models\Product;
use Illuminate\Http\Request;
    
class ProductController extends Controller
{ 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $shared_permissions = 'permission:product-manager|product-editor';    
              
         $this->middleware($shared_permissions, ['only' => ['index','show']]);
         $this->middleware('permission:product-manager', ['only' => ['create', 'store', 'destroy']]);
         $this->middleware($shared_permissions, ['only' => ['edit','update']]);                
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::latest()->paginate(5);
        return view('products.products',compact('products'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.form_product');
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->setRules($request);
        Product::create($request->all());

        return redirect('products')->with('success', 'Producto generado con éxito');
    }
    

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return view('products.show',compact('product'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {        
        return view('products.form_product', compact('product'));
    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $this->setRules($request, $product->id);
        $product->update($request->all());
    
        return redirect('products')->with('success', 'Producto actualizado con éxito');
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
    
        return redirect('products')->with('success', 'Producto borrado con éxito');
    }


    /* Form rules */
    private function setRules(Request $request, $id=0)
    {
        return $request->validate([
            'name' => 'required||min:3|unique:products,name,'. $id,            
            'detail' => 'required|min:10', 
            'price' => 'required|int'          
        ]);
    }  

}