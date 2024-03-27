<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        $products = Product::latest()->get();
        return view('admin.allproducts', compact('products'));
    }

    public function AddProduct(){
        $categories = Category::latest()->get();
        $subcategories = SubCategory::latest()->get();
        return view('admin.addproduct', compact('categories', 'subcategories'));
    }

    public function StoreProduct(Request $request){
        $request->validate([
            'product_name' => 'required|unique:products',
            'price' => 'required',
            'quantity' => 'required',
            'product_short_description' => 'required',
            'Product_long_description' => 'required',
            'product_category_id' => 'required',
            'product_subcategory_id' => 'required',
            'product_img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        $image = $request->file('product_img');
        $img_name = hexdec(uniqid()).'.'. $image->getClientOriginalExtension();
        $request->product_img->move(public_path('upload'), $img_name);
        $img_url = 'upload/' . $img_name;

        $category_id = $request->product_category_id;
        $subcategory_id = $request->product_subcategory_id;

        $category_name = Category::where('id', $category_id)->value('category_name');
        $subcategory_name = SubCategory::where('id', $subcategory_id)->value('subcategory_name');

        Product::insert([
            'product_name' => $request -> product_name,
            'product_short_description' => $request -> product_short_description,
            'Product_long_description' => $request -> Product_long_description,
            'price' => $request -> price,
            'product_category_name' => $category_name,
            'product_subcategory_name' => $subcategory_name,
            'product_category_id' => $request -> product_category_id,
            'product_subcategory_id' => $request -> product_subcategory_id,
            'product_img' => $img_url,
            'quantity' => $request -> quantity,
            'slug' => strtolower(str_replace(' ', '-', $request->product_name)),
        ]);

        Category::where('id', $category_id)->increment('product_count', 1);
        Category::where('id', $subcategory_id)->increment('product_count', 1);

        return redirect()->route('allproducts')->with('message', 'Product added successfully!');
    }

    public function EditProductImg($id){
        $productinfo = Product::findOrFail($id);
        return view('admin.editproductimg', compact('productinfo'));
    }

    public function UpdateProductImg(Request $request){
        $request->validate([
            'product_img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $id = $request->id;

        $image = $request->file('product_img');
        $img_name = hexdec(uniqid()).'.'. $image->getClientOriginalExtension();
        $request->product_img->move(public_path('upload'), $img_name);
        $img_url = 'upload/' . $img_name;

        Product::findOrFail($id)->update([
            'product_img' => $img_url,
        ]);

        return redirect()->route('allproducts')->with('message', 'Product image updated successfully!');
    }

    public function EditProduct($id){
        $productinfo = Product::findOrFail($id);

        return view('admin.editproduct', compact('productinfo'));
    }

    public function UpdateProduct(Request $request){
        $productid = $request->id;

        $request->validate([
            'product_name' => 'required|unique:products',
            'price' => 'required',
            'quantity' => 'required',
            'product_short_description' => 'required',
            'Product_long_description' => 'required',
        ]);

        Product::findOrFail('productid')->update([
            'product_name' => $request -> product_name,
            'product_short_description' => $request -> product_short_description,
            'Product_long_description' => $request -> Product_long_description,
            'price' => $request -> price,
            'quantity' => $request -> quantity,
            'slug' => strtolower(str_replace(' ', '-', $request->product_name)),
        ]);

        return redirect()->route('allproducts')->with('message', 'Product info updated successfully!');
    }

    public function DeleteProduct($id){
        $cat_id = Product::where('id', $id)->value('product_category_id');
        $subcat_id = Product::where('id', $id)->value('product_subcategory_id');

        Product::findOrFail($id)->delete();

        Category::where('id', $cat_id)->decrement('product_count', 1);
        SubCategory::where('id', $subcat_id)->decrement('product_count', 1);


        return redirect()->route('allproducts')->with('message', 'Products Deleted Successfully!');
    }
}
