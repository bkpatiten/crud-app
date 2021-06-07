<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use DB;
use Log;
use Validator;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $search = !empty($request->search) ? $request->search : NULL;
        $header_arr = ['id','name','status'];
        $sort_details = ['id','desc'];
        if(!empty($request->sort_by) && (strpos($request->sort_by, '.') !== false) && (substr_count($request->sort_by,'.') == 1))
        {
            if(!empty($sort_details[0]) && !in_array($sort_details[0],$header_arr))
            {
                $sort_details = ['id','desc'];
            }
            else
            {
                $sort_details = explode(".",$request->sort_by);
            }
        }
        $products = Product::when($search, function($q) use($search){
                return $q->where('name','like','%'.$search.'%')
                    ->orwhere('description','like','%'.$search.'%');
            })
            ->paginate(10);
        return view('products.list_product',[
            'products' => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('products.create_product',[]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $messages = [];
        $validator = Validator::make($request->all(), [
            'product_image' => 'file|image|mimes:jpeg,png,gif,jpg|max:2048',
            'name' => 'required|unique:products,name,NULL,id',
        ],$messages);
        if ($validator->fails())
        {
            return redirect()
                ->route('products.create')
                ->withErrors($validator)
                ->withInput();
        }
        else
        {
            $path = NULL;
            DB::beginTransaction();
            try
            {
                if($request->hasFile('product_image'))
                {
                    $fileName = 'product-image-'.time().'.'.$request->file('product_image')->getClientOriginalExtension();
                    $path = $request->file('product_image')->storeAs('products',$fileName);
                }
                $product = Product::create([
                    'name' => $request->name,
                    'description' => $request->description,
                    'image' => $path,
                ]);
                DB::commit();
                return redirect()->route('products.index')->with('flash_success', 'Product Created!!');
            }
            catch(\Exception $e)
            {
                DB::rollback();
                Log::alert($e);
                abort(500);
            }
            catch(\Throwable $e)
            {
                DB::rollback();
                Log::alert($e);
                abort(500);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
        return view('products.show_product',['product' => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
        return view('products.edit_product',['product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
        $messages = [];
        $validator = Validator::make($request->all(), [
            'product_image' => 'file|image|mimes:jpeg,png,gif,jpg|max:2048',
            'name' => 'required|unique:products,name,'.$product->id.',id',
        ],$messages);
        if ($validator->fails())
        {
            return redirect()
                ->route('products.edit',['product' => $product])
                ->withErrors($validator)
                ->withInput();
        }
        else
        {
            $path = $product->image;
            DB::beginTransaction();
            try
            {
                if($request->hasFile('product_image'))
                {
                    if(!empty($product->image))
                    {
                        if (Storage::exists($product->image))
                        {
                            Storage::delete($product->image);
                        }
                    }
                    $fileName = 'product-image-'.time().'.'.$request->file('product_image')->getClientOriginalExtension();
                    $path = $request->file('product_image')->storeAs('products',$fileName);
                }
                $product->update([
                    'name' => $request->name,
                    'description' => $request->description,
                    'image' => $path,
                ]);
                DB::commit();
                return redirect()->route('products.edit',['product' => $product])->with('flash_success', 'Product Updated!!');
            }
            catch(\Exception $e)
            {
                DB::rollback();
                Log::alert($e);
                abort(500);
            }
            catch(\Throwable $e)
            {
                DB::rollback();
                Log::alert($e);
                abort(500);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
        DB::beginTransaction();
        try
        {
            if(!empty($product->image))
            {
                Storage::delete($product->image);
            }
            $product->delete();
            DB::commit();
            return redirect()->route('products.index')->with('flash_success', 'Product Deleted!!');
        }
        catch(\Exception $e)
        {
            DB::rollback();
            Log::alert($e);
            abort(500);
        }
        catch(\Throwable $e)
        {
            DB::rollback();
            Log::alert($e);
            abort(500);
        }
    }
    public function updateStatus(Product $product,$status)
    {
        DB::beginTransaction();
        try
        {
            $message = !empty($status) && strcasecmp($status,'inactive') == 0 ? 'Deactivated!!' : 'Activated!!';
            $product->update([
                'status' => $status
            ]);
            DB::commit();
            return redirect()->route('products.index')->with('flash_success', 'Product '.$message);
        }
        catch(\Exception $e)
        {
            DB::rollback();
            Log::alert($e);
            abort(500);
        }
        catch(\Throwable $e)
        {
            DB::rollback();
            Log::alert($e);
            abort(500);
        }
    }
}
