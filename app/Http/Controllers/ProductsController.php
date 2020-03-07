<?php

namespace App\Http\Controllers;

use App\Country;
use App\DeliveryAddress;
use App\Order;
use App\OrdersProduct;
use App\ProductsImage;
use App\Cart;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Auth;
use Illuminate\Support\Facades\Input;
use Session;
use Image;
use App\Product;
use App\Category;
use App\Coupon;
use App\ProductsAttribute;
use DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class ProductsController extends Controller
{
    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            if (empty($data['category_id'])) {
                return redirect()->back()->with('flash_message_error', 'Under Category is Missing!');
            }
            $product = new Product;
            $product->category_id = $data['category_id'];
            $product->product_name = $data['product_name'];
            $product->product_code = $data['product_code'];
            $product->product_color = $data['product_color'];
            if (!empty($data['weight'])) {
                $product->weight = $data['weight'];
            } else {
                $product->weight = 0;
            }
            if (!empty($data['description'])) {
                $product->description = $data['description'];
            } else {
                $product->description = '';
            }
            if (!empty($data['care'])) {
                $product->care = $data['care'];
            } else {
                $product->care = '';
            }
            if (!empty($data['sleeve'])) {
                $product->sleeve = $data['sleeve'];
            } else {
                $product->sleeve = '';
            }
            if (!empty($data['pattern'])) {
                $product->pattern = $data['pattern'];
            } else {
                $product->pattern = '';
            }
            $product->price = $data['price'];
            //Upload Image
            if ($request->hasFile('image')) {
                $image_tmp = $request->file('image');
                if ($image_tmp->isValid()) {
                    $extension = $image_tmp->getClientOriginalExtension();
                    $filename = rand(111, 99999) . '.' . $extension;
                    $large_image_path = 'images/backend_images/products/large/' . $filename;
                    $medium_image_path = 'images/backend_images/products/medium/' . $filename;
                    $small_image_path = 'images/backend_images/products/small/' . $filename;

                    //REsize Images
                    Image::make($image_tmp)->save($large_image_path);
                    Image::make($image_tmp)->resize(600, 600)->save($medium_image_path);
                    Image::make($image_tmp)->resize(300, 300)->save($small_image_path);

                    //Store image name in products table
                    $product->image = $filename;
                }
            }
            //Upload Video
            if ($request->hasFile('video')) {
                $video_tmp = Input::file('video');
                $video_name = $video_tmp->getClientOriginalName();
                $video_path = 'videos/';
                $video_tmp->move($video_path, $video_name);
                $product->video = $video_name;
            }

            if (empty($data['status'])) {
                $status = 0;
            } else {
                $status = 1;
            }
            if (empty($data['feature_item'])) {
                $feature_item = 0;
            } else {
                $feature_item = 1;
            }
            $product->status = $status;
            $product->feature_item = $feature_item;
            $product->save();
            return redirect('/admin/view-products')->with('flash_message_success', 'New Category Added!');
        }
        $categories = Category::where(['parent_id' => 0])->get();
        $categories_dropdown = "<option selected disabled>Select</option>";
        foreach ($categories as $cat) {
            $categories_dropdown .= "<optgroup label='" . $cat->name . "'>";
            $categories_dropdown .= "<option value='" . $cat->parent_id . "'>" . $cat->name . "</option>";
            $sub_categories = Category::where(['parent_id' => $cat->id])->get();
            foreach ($sub_categories as $sub_cat) {
                $categories_dropdown .= "<option value='" . $sub_cat->id . "'>" . $sub_cat->name . "</option>";
            }
            $categories_dropdown .= "</optgroup>";
        }

        $sleeveArray = array('Full Sleeve', 'Half Sleeve', 'Short Sleeve', 'Sleeveless');
        $patternArray = array('Checked', 'Plain', 'Printed', 'Self', 'Solid');
        return view('admin.products.add_product')->with(compact('categories_dropdown', 'sleeveArray', 'patternArray'));
    }

    public function edit(Request $request, $id = null)
    {

        if ($request->isMethod('post')) {
            $data = $request->all();
            /*echo "<pre>"; print_r($data); die;*/

            if (empty($data['status'])) {
                $status = '0';
            } else {
                $status = '1';
            }

            if (empty($data['feature_item'])) {
                $feature_item = '0';
            } else {
                $feature_item = '1';
            }

            if (!empty($data['sleeve'])) {
                $sleeve = $data['sleeve'];
            } else {
                $sleeve = '';
            }
            if (!empty($data['pattern'])) {
                $pattern = $data['pattern'];
            } else {
                $pattern = '';
            }
            if (!empty($data['weight'])) {
                $weight = $data['weight'];
            } else {
                $weight = 0;
            }

            // Upload Image
            if ($request->hasFile('image')) {
                $image_tmp = $request->file('image');
                if ($image_tmp->isValid()) {
                    // Upload Images after Resize
                    $extension = $image_tmp->getClientOriginalExtension();
                    $fileName = rand(111, 99999) . '.' . $extension;
                    $large_image_path = 'images/backend_images/product/large' . '/' . $fileName;
                    $medium_image_path = 'images/backend_images/product/medium' . '/' . $fileName;
                    $small_image_path = 'images/backend_images/product/small' . '/' . $fileName;

                    Image::make($image_tmp)->save($large_image_path);
                    Image::make($image_tmp)->resize(600, 600)->save($medium_image_path);
                    Image::make($image_tmp)->resize(300, 300)->save($small_image_path);

                }
            } else if (!empty($data['current_image'])) {
                $fileName = $data['current_image'];
            } else {
                $fileName = '';
            }

            // Upload Video
            if ($request->hasFile('video')) {
                $video_tmp = Input::file('video');
                $video_name = $video_tmp->getClientOriginalName();
                $video_path = 'videos/';
                $video_tmp->move($video_path, $video_name);
                $videoName = $video_name;
            } else if (!empty($data['current_video'])) {
                $videoName = $data['current_video'];
            } else {
                $videoName = '';
            }

            if (empty($data['description'])) {
                $data['description'] = '';
            }

            if (empty($data['care'])) {
                $data['care'] = '';
            }

            Product::where(['id' => $id])->update(['feature_item' => $feature_item, 'status' => $status, 'category_id' => $data['category_id'], 'product_name' => $data['product_name'],
                'product_code' => $data['product_code'], 'product_color' => $data['product_color'], 'description' => $data['description'], 'care' => $data['care'],
                'price' => $data['price'], 'image' => $fileName, 'video' => $videoName, 'sleeve' => $sleeve, 'pattern' => $pattern,'weight'=>$weight]);
            return redirect()->back()->with('flash_message_success', 'Product has been edited successfully');
        }

        // Get Product Details start //
        $productDetails = Product::where(['id' => $id])->first();
        // Get Product Details End //

        // Categories drop down start //
        $categories = Category::where(['parent_id' => 0])->get();

        $categories_drop_down = "<option value='' disabled>Select</option>";
        foreach ($categories as $cat) {
            if ($cat->id == $productDetails->category_id) {
                $selected = "selected";
            } else {
                $selected = "";
            }
            $categories_drop_down .= "<option value='" . $cat->id . "' " . $selected . ">" . $cat->name . "</option>";
            $sub_categories = Category::where(['parent_id' => $cat->id])->get();
            foreach ($sub_categories as $sub_cat) {
                if ($sub_cat->id == $productDetails->category_id) {
                    $selected = "selected";
                } else {
                    $selected = "";
                }
                $categories_drop_down .= "<option value='" . $sub_cat->id . "' " . $selected . ">&nbsp;&nbsp;--&nbsp;" . $sub_cat->name . "</option>";
            }
        }
        // Categories drop down end //

        $sleeveArray = array('Full Sleeve', 'Half Sleeve', 'Short Sleeve', 'Sleeveless');
        $patternArray = array('Checked', 'Plain', 'Printed', 'Self', 'Solid');
        return view('admin.products.edit_product')->with(compact('productDetails', 'categories_drop_down', 'sleeveArray', 'patternArray'));
    }

    public function deleteProductImage($id = null)
    {
        //Get Product Image Name
        $productImage = Product::where(['id' => $id])->first();  //image name
        $large_image_path = 'images/backend_images/products/large/';
        $medium_image_path = 'images/backend_images/products/medium/';
        $small_image_path = 'images/backend_images/products/small/';

        //Delete Large Image if not exists inFolder
        if (file_exists($large_image_path . $productImage->image)) {
            unlink($large_image_path . $productImage->image);
        }
        //Delete Medium Image if not exists inFolder
        if (file_exists($medium_image_path . $productImage->image)) {
            unlink($medium_image_path . $productImage->image);
        }
        //Delete Small Image if not exists inFolder
        if (file_exists($small_image_path . $productImage->image)) {
            unlink($small_image_path . $productImage->image);
        }
        //Delete Image from Products
        if (!empty($id)) {
            Product::where(['id' => $id])->update(['image' => ""]);
            return redirect()->back()->with('flash_message_success', 'Product Image Deleted Successfully!');
        }
    }

    public function deleteProductVideo($id = null)
    {
        //Get Product Image Name
        $productVideo = Product::where(['id' => $id])->first();  //video name
        $video_path = 'videos/';

        //Delete Video if not exists inFolder
        if (file_exists($video_path . $productVideo->video)) {
            unlink($video_path . $productVideo->video);
        }
        //Delete Image from Products
        if (!empty($id)) {
            Product::where(['id' => $id])->update(['video' => ""]);
            return redirect()->back()->with('flash_message_success', 'Product Video Deleted Successfully!');
        }
    }

    public function delete($id = null)
    {
        if (!empty($id)) {
            Product::where(['id' => $id])->delete();
            //Product sildiğimizde attribute 'u da ekledik ki hem product hemde attribute silinsin!!!!!!
            ProductsAttribute::where(['product_id' => $id])->delete();
            return redirect()->back()->with('flash_message_success', 'Product Deleted Successfully!');
        }

    }

    public function view()
    {
        $products = Product::orderby('id', 'DESC')->get();
        $products = json_decode(json_encode($products));
        foreach ($products as $key => $val) {
            $category_name = Category::where(['id' => $val->category_id])->first();
            $products[$key]->category_name = $category_name->name;

        }
        return view('admin.products.view_products')->with(compact('products'));
    }

    public function createAttributes(Request $request, $id = null)
    {

        $productDetails = Product::with('attributes')->where(['id' => $id])->first();
        //$productDetails = json_decode(json_encode($productDetails));
        if ($request->isMethod('post')) {
            $data = $request->all();
            foreach ($data['sku'] as $key => $val) {
                if (!empty($val)) {
                    //SKU check
                    $attrCountSKU = ProductsAttribute::where('sku', $val)->count();
                    if ($attrCountSKU > 0) {
                        return redirect('/admin/add-attributes/' . $id)->with('flash_message_error', '"' . $val . '" SKU already exists!, Please add another SKU.');
                    }
                    //Prevent duplicate Size Check
                    $attrCountSizes = ProductsAttribute::where(['product_id' => $id, 'size' => $data['size'][$key]])->count();
                    if ($attrCountSizes > 0) {
                        return redirect('/admin/add-attributes/' . $id)->with('flash_message_error', '"' . $data['size'][$key] . '" Size already exists for this product, Please add another Size.');
                    }

                    $attribute = new ProductsAttribute;
                    $attribute->product_id = $id;
                    $attribute->sku = $val;
                    $attribute->size = $data['size'][$key];
                    $attribute->price = $data['price'][$key];
                    $attribute->stock = $data['stock'][$key];
                    $attribute->save();
                }
            }
            return redirect('/admin/add-attributes/' . $id)->with('flash_message_success', 'Product Attribute Added Successfully!');
        }
        return view('admin.products.add_attributes')->with(compact('productDetails'));
    }

    public function editAttributes(Request $request, $id = null)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            foreach ($data['idAttr'] as $key => $attr) {
                ProductsAttribute::where(['id' => $data['idAttr'][$key]])->update(['price' => $data['price'][$key], 'stock' => $data['stock'][$key]]);
            }
            return redirect()->back()->with('flash_message_success', 'Prices and Stocks are Updated Successfully!');
        }
    }

    public function addImages(Request $request, $id = null)
    {

        $productDetails = Product::with('attributes')->where(['id' => $id])->first();
        //$productDetails = json_decode(json_encode($productDetails));
        if ($request->isMethod('post')) {
            // Add Images
            $data = $request->all();
            if ($request->hasFile('image')) {
                $files = $request->file('image');
                foreach ($files as $file) {
                    //Upload Images after resize
                    $image = new ProductsImage;
                    $extension = $file->getClientOriginalExtension();
                    $fileName = rand(111, 99999) . '.' . $extension;
                    $large_image_pat = 'images/backend_images/products/large/' . $fileName;
                    $medium_image_pat = 'images/backend_images/products/medium/' . $fileName;
                    $small_image_pat = 'images/backend_images/products/small/' . $fileName;
                    Image::make($file)->save($large_image_pat);
                    Image::make($file)->resize(600, 600)->save($medium_image_pat);
                    Image::make($file)->resize(300, 300)->save($small_image_pat);
                    $image->image = $fileName;
                    $image->product_id = $data['product_id'];
                    $image->save();
                }
            }
            return redirect('admin/add-images/' . $id)->with('flash_message_success', 'Product Images Has Been Added Successfully!');
        }
        $productImages = ProductsImage::where(['product_id' => $id])->get();
        return view('admin.products.add_images')->with(compact('productDetails', 'productImages'));
    }

    public function deleteAltImage($id = null)
    {
        //Get Product Image Name
        $productImage = ProductsImage::where(['id' => $id])->first();  //image name
        $large_image_path = 'images/backend_images/products/large/';
        $medium_image_path = 'images/backend_images/products/medium/';
        $small_image_path = 'images/backend_images/products/small/';

        //Delete Large Image if not exists inFolder
        if (file_exists($large_image_path . $productImage->image)) {
            unlink($large_image_path . $productImage->image);
        }
        //Delete Medium Image if not exists inFolder
        if (file_exists($medium_image_path . $productImage->image)) {
            unlink($medium_image_path . $productImage->image);
        }
        //Delete Small Image if not exists inFolder
        if (file_exists($small_image_path . $productImage->image)) {
            unlink($small_image_path . $productImage->image);
        }
        //Delete Image from Products
        if (!empty($id)) {
            ProductsImage::where(['id' => $id])->delete();
            return redirect()->back()->with('flash_message_success', 'Alternate Image Deleted Successfully!');
        }
    }

    public function deleteAttribute($id = null)
    {
        if (!empty($id)) {
            ProductsAttribute::where(['id' => $id])->delete();
            return redirect()->back()->with('flash_message_success', 'Product Attribute Deleted Successfully!');
        }

    }

    public function products($url)
    {

        //Show 404 page if Category URL does not exist
        $countCategory = Category::where(['url' => $url, 'status' => 1])->count();
        if ($countCategory == 0) {
            abort(404);
        }

        $categories = Category::with('categories')->where(['parent_id' => 0])->get();

        $categoryDetails = Category::where(['url' => $url])->first();
        if ($categoryDetails->parent_id == 0) {
            //if url is main category url
            $subCategories = Category::where(['parent_id' => $categoryDetails->id])->get();
            foreach ($subCategories as $key => $subcat) {
                $cat_ids[] = $subcat->id;
            }
            $productsAll = Product::whereIn('products.category_id', $cat_ids)->where('products.status', 1)->orderBy('products.id', 'DESC');
            $breadcrumb = "<a href='/'>Home</a> / <a href='" . $categoryDetails->url . "'>" . $categoryDetails->name . "</a>";
        } else {
            //if url is sub category url
            $productsAll = Product::where(['products.category_id' => $categoryDetails->id])->where('products.status', 1)->orderBy('products.id', 'DESC');
            $mainCategory = Category::where('id', $categoryDetails->parent_id)->first();
            $breadcrumb = "<a href='/'>Home</a> / <a href='" . $mainCategory->url . "'>" . $mainCategory->name . "</a> / <a href='" . $categoryDetails->url . "'>" . $categoryDetails->name . "</a>";
        }
        if (!empty($_GET['color'])) {
            $colorArr = explode('-', $_GET['color']);
            $productsAll = $productsAll->whereIn('products.product_color', $colorArr);
        }
        if (!empty($_GET['sleeve'])) {
            $sleeveArray = explode('-', $_GET['sleeve']);
            $productsAll = $productsAll->whereIn('products.sleeve', $sleeveArray);
        }
        if (!empty($_GET['pattern'])) {
            $patternArray = explode('-', $_GET['pattern']);
            $productsAll = $productsAll->whereIn('products.pattern', $patternArray);
        }
        if (!empty($_GET['size'])) {
            $sizesArray = explode('-', $_GET['size']);
            $productsAll = $productsAll->join('products_attributes', 'products_attributes.product_id', '=', 'products.id')
                ->whereIn('products_attributes.size', $sizesArray);
        }
        $productsAll = $productsAll->paginate(3);

        /*$colorArr =array('siyah','mavi','Brown','Gold','Green','Orange','Pink','Purple',
            'Red','Silver','White','Yellow');*/
        $colorArr = Product::select('product_color')->groupBy('product_color')->get();
        $colorArr = Arr::flatten(json_decode(json_encode($colorArr), true));

        $sleeveArray = Product::select('sleeve')->where('sleeve', '!=', '')->groupBy('sleeve')->get();
        $sleeveArray = Arr::flatten(json_decode(json_encode($sleeveArray), true));

        $patternArray = Product::select('pattern')->where('pattern', '!=', '')->groupBy('pattern')->get();
        $patternArray = Arr::flatten(json_decode(json_encode($patternArray), true));

        $sizesArray = ProductsAttribute::select('size')->groupBy('size')->get();
        $sizesArray = Arr::flatten(json_decode(json_encode($sizesArray), true));

        $meta_title = $categoryDetails->meta_title;
        $meta_description = $categoryDetails->meta_description;
        $meta_keywords = $categoryDetails->meta_keywords;
        return view('products.listing')->with(compact('categories', 'productsAll', 'categoryDetails',
            'meta_title', 'meta_description', 'meta_keywords', 'url', 'colorArr', 'sleeveArray'
            , 'patternArray', 'sizesArray', 'breadcrumb'));
    }

    public function product($id = null)
    {
        //Show 404 page if Category URL does not exist
        $countProduct = Product::where(['id' => $id, 'status' => 1])->count();
        if ($countProduct == 0) {
            abort(404);
        }
        //Get Product Details
        $productDetails = Product::with('attributes')->where('id', $id)->first();
        //Related Product under as recomended item
        $relatedProducts = Product::where('id', '!=', $id)->where(['category_id' => $productDetails->category_id])->get();

        //Get All categories and sub categories
        $categories = Category::with('categories')->where(['parent_id' => 0])->get();
        $categoryDetails = Category::where(['id' => $productDetails->category_id])->first();
        if ($categoryDetails->parent_id == 0) {
            $breadcrumb = "<a href='/'>Home</a> / <a href='/products/" . $categoryDetails->url . "'>" . $categoryDetails->name . "</a> / " . $productDetails->product_name;
        } else {
            //if url is sub category url
            $mainCategory = Category::where('id', $categoryDetails->parent_id)->first();
            $breadcrumb = "<a href='/'>Home</a> / <a href='/products/" . $mainCategory->url . "'>" . $mainCategory->name . "</a>
           / <a href='/products/" . $categoryDetails->url . "'>" . $categoryDetails->name . "</a> / " . $productDetails->product_name;
        }

        //Get Alternate Images Details
        $productAltImages = ProductsImage::where(['product_id' => $id])->get();
        //Count Total Stock of products
        $total_stock = ProductsAttribute::where('product_id', $id)->sum('stock');

        $meta_title = $productDetails->product_name;
        $meta_description = $productDetails->description;
        $meta_keywords = $productDetails->product_name;

        return view('products.detail')->with(compact('productDetails', 'categories', 'productAltImages',
            'total_stock', 'relatedProducts', 'meta_title', 'meta_description', 'meta_keywords', 'breadcrumb'));
    }

    public function getProductPrice(Request $request)
    {
        $data = $request->all();
        $proArr = explode("-", $data['idSize']);
        $proAttr = ProductsAttribute::where(['product_id' => $proArr[0], 'size' => $proArr[1]])->first();
        $getCurrencyRates = Product::getCurrencyRates($proAttr->price);
        echo $proAttr->price . "-" . $getCurrencyRates['USD_Rate'] . "-" . $getCurrencyRates['EUR_Rate'] . "-" . $getCurrencyRates['GBP_Rate'];
        echo "#";
        echo $proAttr->stock;
    }

    public function addtocart(Request $request)
    {
        Session::forget('CouponAmount');
        Session::forget('CouponCode');
        $data = $request->all();

        //Check Product Stock is available or not
        $product_size = explode("-", $data['size']);
        $getProductStock = ProductsAttribute::where(['product_id' => $data['product_id'], 'size' => $product_size[1]])->first();
        if ($getProductStock->stock < $data['quantity']) {
            return redirect()->back()->with('flash_message_error', 'Required quantity is not available!');
        }

        if (empty(Auth::user()->email)) {
            $data['user_email'] = '';
        } else {
            $data['user_email'] = Auth::user()->email;
        }

        $session_id = Session::get('session_id');
        if (!isset($session_id)) {
            $session_id = Str::random(40);
            Session::put('session_id', $session_id);
        }
        $sizeIDArr = explode('-', $data['size']);
        $product_size = $sizeIDArr[1];

        if (empty(Auth::check())) {
            $countProducts = DB::table('carts')->where(['product_id' => $data['product_id'], 'product_color' => $data['product_color'],
                'size' => $product_size, 'session_id' => $session_id])->count();
            if ($countProducts > 0) {
                return redirect()->back()->with('flash_message_error', 'Product already exist in cart!');
            }
        } else {
            $countProducts = DB::table('carts')->where(['product_id' => $data['product_id'], 'product_color' => $data['product_color'],
                'size' => $product_size, 'user_email' => $data['user_email']])->count();
            if ($countProducts > 0) {
                return redirect()->back()->with('flash_message_error', 'Product already exist in cart!');
            }

        }

        $getSKU = ProductsAttribute::select('sku')->where(['product_id' => $data['product_id'], 'size' => $product_size])->first();

        DB::table('carts')->insert(['product_id' => $data['product_id'], 'product_name' => $data['product_name'], 'product_color' => $data['product_color'],
            'product_code' => $getSKU['sku'], 'price' => $data['price'], 'size' => $product_size, 'quantity' => $data['quantity']
            , 'user_email' => $data['user_email'], 'session_id' => $session_id]);

        return redirect('cart')->with('flash_message_success', 'Product has been added in Cart!');

    }

    public function cart()
    {
        // echo "<pre>"; print_r($productImage ); die;

        $session_id = Session::get('session_id');
        $userCart = DB::table('carts')->where(['session_id' => $session_id])->get();

        foreach ($userCart as $key => $product) {
            $productDetails = Product::where('id', $product->product_id)->first();
            $userCart[$key]->image = $productDetails->image;
        }
        $meta_title = "Shopping Cart - E-com Website";
        $meta_description = "View Shopping cart of E-com Website";
        $meta_keywords = "shopping cart, e-com Website";
        return view('products.cart')->with(compact('userCart', 'meta_title', 'meta_description', 'meta_keywords'));
    }

    public function deleteCartProduct($id = null)
    {
        Session::forget('CouponAmount');
        Session::forget('CouponCode');
        if (!empty($id)) {
            DB::table('carts')->where(['id' => $id])->delete();
            return redirect()->back()->with('flash_message_success', 'Product Deleted Successfully!');
        }
    }

    public function updateCartQuantity($id = null, $quantity = null)
    {
        Session::forget('CouponAmount');
        Session::forget('CouponCode');
        $getCartDetails = DB::table('carts')->where('id', $id)->first();
        $getAttributeStock = ProductsAttribute::where('sku', $getCartDetails->product_code)->first();
        $updated_quantity = $getCartDetails->quantity + $quantity;
        if ($getAttributeStock->stock >= $updated_quantity) {
            DB::table('carts')->where('id', $id)->increment('quantity', $quantity);
            return redirect('cart')->with('flash_message_success', 'Product Quantity Has Been Updated!');
        } else {
            return redirect('cart')->with('flash_message_error', 'Required Product Quantity is not available!');
        }
    }

    public function applyCoupon(Request $request)
    {
        $data = $request->all();
        $couponCount = Coupon::where('coupon_code', $data['coupon_code'])->count();
        if ($couponCount == 0) {
            return redirect()->back()->with('flash_message_error', 'Coupon does not exist!');
        } else {
            //with perform other checks like Enable/Disable, Expiry date..
            $couponDetails = Coupon::where('coupon_code', $data['coupon_code'])->first();

            //If coupon is Disable
            if ($couponDetails->status == 0) {
                return redirect()->back()->with('flash_message_error', 'This coupon is not active!');
            }
            //If coupon is Expired
            $expiry_date = $couponDetails->expiry_date;
            $current_date = date('Y-m-d');
            if ($expiry_date < $current_date) {
                return redirect()->back()->with('flash_message_error', 'This coupon is expired!');
            }
            //Coupon is Valid for Discount

            //Get Cart Total Amount

            if (Auth::check()) {
                $user_email = Auth::user()->email;
                $userCart = DB::table('carts')->where(['user_email' => $user_email])->get();

            } else {
                $session_id = Session::get('session_id');
                $userCart = DB::table('carts')->where(['session_id' => $session_id])->get();
            }
            $total_amount = 0;
            foreach ($userCart as $item) {
                $total_amount = $total_amount + ($item->price * $item->quantity);
            }

            //Check if amount type is Fixed or Percentage
            if ($couponDetails->amount_type == "Fixed") {
                $couponAmount = $couponDetails->amount;
            } else if ($couponDetails->amount_type == "Percentage") {
                $couponAmount = ($total_amount) * (($couponDetails->amount) / 100);
            }
            // Add Coupon Code & Amount in Session
            Session::put('CouponAmount', $couponAmount);
            Session::put('CouponCode', $data['coupon_code']);
            return redirect()->back()->with('flash_message_success', 'Coupon code successfully applied. You are availing discount!');
        }
    }

    public function checkout(Request $request)
    {
        $user_id = Auth::user()->id;
        $user_email = Auth::user()->email;
        $userDetails = User::find($user_id);
        $countries = Country::get();

        // If Cart is empty
        $session_id = Session::get('session_id');
        $cart_item = Cart::where(['session_id' => $session_id])->count();
        if ($cart_item == 0) {
            return redirect()->back()->with('flash_message_error', 'Please add a product to cart!');
        }

        //Check if Shipping Address exists
        $shippingCount = DeliveryAddress::where('user_id', $user_id)->count();
        $shippingDetails = array();
        if ($shippingCount > 0) {
            $shippingDetails = DeliveryAddress::where('user_id', $user_id)->first();
        }
        //Update cart table with user email
        $session_id = Session::get('session_id');
        DB::table('carts')->where(['session_id' => $session_id])->update(['user_email' => $user_email]);

        if ($request->isMethod('post')) {
            $data = $request->all();
            if (empty($data['billing_name']) || empty($data['billing_address']) ||
                empty($data['billing_city']) || empty($data['billing_state']) ||
                empty($data['billing_country']) || empty($data['billing_pincode']) ||
                empty($data['billing_mobile'])) {
                return redirect()->back()->with('flash_message_error', 'Please fill all fields to Checkout!');

            }
            //Update User datails
            User::where('id', $user_id)->update(['name' => $data['billing_name'],
                'address' => $data['billing_address'],
                'city' => $data['billing_city'],
                'state' => $data['billing_state'],
                'country' => $data['billing_country'],
                'pincode' => $data['billing_pincode'],
                'mobile' => $data['billing_mobile']]);
            if ($shippingCount > 0) {
                //Update Shipping Address
                DeliveryAddress::where('id', $user_id)->update(['name' => $data['shipping_name'],
                    'address' => $data['shipping_address'],
                    'city' => $data['shipping_city'],
                    'state' => $data['shipping_state'],
                    'country' => $data['shipping_country'],
                    'pincode' => $data['shipping_pincode'],
                    'mobile' => $data['shipping_mobile']]);
            } else {

                //Add New Shipping Address
                $shipping = new DeliveryAddress;
                $shipping->user_id = $user_id;
                $shipping->user_email = $user_email;
                $shipping->name = $data['shipping_name'];
                $shipping->address = $data['shipping_address'];
                $shipping->city = $data['shipping_city'];
                $shipping->state = $data['shipping_state'];
                $shipping->country = $data['shipping_country'];
                $shipping->pincode = $data['shipping_pincode'];
                $shipping->mobile = $data['shipping_mobile'];
                $shipping->save();
            }
            $pincodeCount = DB::table('pincodes')->where('pincode', $data['shipping_pincode'])->count();
            if ($pincodeCount == 0) {
                return redirect()->back()->with('flash_message_error', 'Your location is not available for delivery. Please enter another location.');
            }
            return redirect()->action('ProductsController@orderReview');
        }
        $meta_title = "Checkout - E-com Website";
        return view('products.checkout')->with(compact('userDetails', 'countries', 'shippingDetails',
            'meta_title'));
    }

    public function orderReview(Request $request)
    {

        $user_id = Auth::user()->id;
        $user_email = Auth::user()->email;
        $userDetails = User::where('id', $user_id)->first();
        $session_id = Session::get('session_id');
        $shippingDetails = DeliveryAddress::where('user_id', $user_id)->first();
        $userCart = DB::table('carts')->where(['session_id' => $session_id])->get();
        $total_weight=0;
        foreach ($userCart as $key => $product) {
            $productDetails = Product::where('id', $product->product_id)->first();
            $userCart[$key]->image = $productDetails->image;
            $total_weight = $total_weight + $productDetails->weight;
        }
        $codpincodeCount = DB::table('cod_pincodes')->where('pincode', $shippingDetails->pincode)->count();
        $prepaidpincodeCount = DB::table('prepaid_pincodes')->where('pincode', $shippingDetails->pincode)->count();

        //Shipping Charges
        $shippingCharges = Product::getShippingCharges($total_weight,$shippingDetails->country);
        Session::put('ShippingCharges',$shippingCharges);

        $meta_title = "Order Review - E-com Website";
        return view('products.order_review')->with(compact('userDetails', 'shippingDetails',
            'userCart', 'meta_title', 'codpincodeCount', 'prepaidpincodeCount','shippingCharges'));
    }

    public function placeOrder(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            $user_id = Auth::user()->id;
            $user_email = Auth::user()->email;

            //Prevent Out of Stock Products to ordering
            $userCart = DB::table('carts')->where('user_email', $user_email)->get();
            foreach ($userCart as $cart) {
                $getAttributeCount = Product::getAttributeCount($cart->product_id, $cart->size);
                if ($getAttributeCount == 0) {
                    Product::deleteCartProduct($cart->product_id, $user_email);
                    return redirect('/cart')->with('flash_message_error', 'One of the product is not available.
                   Please try again!');
                }
                $product_stock = Product::getProductStock($cart->product_id, $cart->size);
                if ($product_stock == 0) {
                    Product::deleteCartProduct($cart->product_id, $user_email);
                    return redirect('/cart')->with('flash_message_error', 'Sold Out product removed
                    from Cart. Please try again!');
                }
                if ($cart->quantity > $product_stock) {
                    return redirect('/cart')->with('flash_message_error', 'Reduce Product Stock and try again.');
                }
                $product_status = Product::getProductStatus($cart->product_id);
                if ($product_status == 0) {
                    Product::deleteCartProduct($cart->product_id, $user_email);
                    return redirect('/cart')->with('flash_message_error', 'Disabled product removed
                    from Cart. Please try again!');
                }
                $getCategoryId = Product::select('category_id')->where('id',$cart->product_id)->first();
                $category_status = Product::getCategoryStatus($getCategoryId->category_id);
                if ($category_status == 0) {
                    Product::deleteCartProduct($cart->product_id, $user_email);
                    return redirect('/cart')->with('flash_message_error', 'One of the product category is disabled.
                   Please try again!');
                }
            }
            //Get Shipping Address of User
            $shippingDetails = DeliveryAddress::where(['user_email' => $user_email])->first();

            $pincodeCount = DB::table('pincodes')->where('pincode', $shippingDetails->pincode)->count();
            if ($pincodeCount == 0) {
                return redirect()->back()->with('flash_message_error', 'Your location is not available for delivery. Please enter another location.');

            }
            if (empty(Session::get('CouponCode'))) {
                $coupon_code = "";
            } else {
                $coupon_code = Session::get('CouponCode');
            }
            if (empty(Session::get('CouponAmount'))) {
                $coupon_amount = "";
            } else {
                $coupon_amount = Session::get('CouponAmount');
            }
            //Shipping Charges
           /* $shippingCharges = Product::getShippingCharges($shippingDetails->country);*/

            $order = new Order;
            $order->user_id = $user_id;
            $order->user_email = $user_email;
            $order->name = $shippingDetails->name;
            $order->address = $shippingDetails->address;
            $order->city = $shippingDetails->city;
            $order->state = $shippingDetails->state;
            $order->country = $shippingDetails->country;
            $order->pincode = $shippingDetails->pincode;
            $order->mobile = $shippingDetails->mobile;
            $order->coupon_code = $coupon_code;
            $order->coupon_amount = $coupon_amount;
            $order->order_status = "New";
            $order->payment_method = $data['payment_method'];
            $order->shipping_charges = Session::get('ShippingCharges');
            $order->grand_total = $data['grand_total'];
            $order->save();

            $order_id = DB::getPdo()->lastInsertId();
            $cartProducts = DB::table('carts')->where(['user_email' => $user_email])->get();
            foreach ($cartProducts as $pro) {
                $cartPro = new OrdersProduct;
                $cartPro->order_id = $order_id;
                $cartPro->user_id = $user_id;
                $cartPro->product_id = $pro->product_id;
                $cartPro->product_code = $pro->product_code;
                $cartPro->product_name = $pro->product_name;
                $cartPro->product_size = $pro->size;
                $cartPro->product_price = $pro->price;
                $cartPro->product_color = $pro->product_color;
                $cartPro->product_qty = $pro->quantity;
                $cartPro->save();

                //Reduce Stock Script Starts
                $getProductStock = ProductsAttribute::where('sku', $pro->product_code)->first();
                /*echo "Original Stock: ".$getProductStock->stock;
                echo "Stock to reduce: ".$pro->quantity;*/
                $newStock = $getProductStock->stock - $pro->quantity;
                if ($newStock < 0) {
                    $newStock = 0;
                }
                ProductsAttribute::where('sku', $pro->product_code)->update(['stock' => $newStock]);
                //Reduce Stock Script Ends
            }
            Session::put('order_id', $order_id);
            Session::put('grand_total', $data['grand_total']);
            if ($data['payment_method'] == "COD") {
                $productDetails = Order::with('orders')->where('id', $order_id)->first();
                $userDetails = User::where('id', $user_id)->first();
                /*Code for Order Email Starts*/
                $email = $user_email;
                $messageData = [
                    'email' => $email,
                    'name' => $shippingDetails->name,
                    'order_id' => $order_id,
                    'productDetails' => $productDetails,
                    'userDetails' => $userDetails
                ];
                Mail::send('emails.order', $messageData, function ($message) use ($email) {
                    $message->to($email)->subject('Order Placed - E-com Website');
                });
                /*Code for Order Email Ends*/

                //COD-Redirect user to thanks page after saving order
                return redirect('/thanks');
            } else {
                //Paypal- Redirect user to paypal page after saving order
                return redirect('/paypal');
            }


        }
    }

    public function thanks(Request $request)
    {
        $user_email = Auth::user()->email;
        DB::table('carts')->where('user_email', $user_email)->delete();
        return view('orders.thanks');
    }

    public function paypal(Request $request)
    {
        return view('orders.paypal');
    }

    public function userOrders(Request $request)
    {
        $user_id = Auth::user()->id;
        $orders = Order::with('orders')->where('user_id', $user_id)->orderByDesc('id')->get();
        return view('orders.user_orders')->with(compact('orders'));
    }

    public function userOrderDetails($order_id)
    {
        $user_id = Auth::user()->id;
        $orderDetails = Order::with('orders')->where('id', $order_id)->first();
        return view('orders.user_order_details')->with(compact('orderDetails'));

    }

    public function viewOrders()
    {
        $orders = Order::with('orders')->orderBy('id', 'DESC')->get();
        return view('admin.orders.view_orders')->with(compact('orders'));
    }

    public function viewOrdersDetail($order_id)
    {

        $orderDetails = Order::with('orders')->where('id', $order_id)->first();
        $user_id = $orderDetails->user_id;
        $userDetails = User::where('id', $user_id)->first();
        return view('admin.orders.view_orders_details')->with(compact('orderDetails', 'userDetails'));
    }

    public function viewOrderInvoice($order_id)
    {

        $orderDetails = Order::with('orders')->where('id', $order_id)->first();
        $user_id = $orderDetails->user_id;
        $userDetails = User::where('id', $user_id)->first();
        return view('admin.orders.order_invoice')->with(compact('orderDetails', 'userDetails'));
    }

    public function updateOrderStatus(Request $request)
    {

        if ($request->isMethod('post')) {
            $data = $request->all();
            Order::where('id', $data['order_id'])->update(['order_status' => $data['order_status']]);
            return redirect()->back()->with('flash_message_success', 'Order Status has been updated successfully!');
        }
    }

    public function filter(Request $request)
    {
        $data = $request->all();
        $colorUrl = "";
        if (!empty($data['colorFilter'])) {
            foreach ($data['colorFilter'] as $color) {
                if (empty($colorUrl)) {
                    $colorUrl = "&color=" . $color;
                } else {
                    $colorUrl .= "-" . $color;
                }
            }
        }
        $sleeveUrl = "";
        if (!empty($data['sleeveFilter'])) {
            foreach ($data['sleeveFilter'] as $sleeve) {
                if (empty($sleeveUrl)) {
                    $sleeveUrl = "&sleeve=" . $sleeve;
                } else {
                    $sleeveUrl .= "-" . $sleeve;
                }
            }
        }
        $patternUrl = "";
        if (!empty($data['patternFilter'])) {
            foreach ($data['patternFilter'] as $pattern) {
                if (empty($patternUrl)) {
                    $patternUrl = "&pattern=" . $pattern;
                } else {
                    $patternUrl .= "-" . $pattern;
                }
            }
        }
        $sizesUrl = "";
        if (!empty($data['sizeFilter'])) {
            foreach ($data['sizeFilter'] as $size) {
                if (empty($sizesUrl)) {
                    $sizesUrl = "&size=" . $size;
                } else {
                    $sizesUrl .= "-" . $size;
                }
            }
        }
        $finalUrl = "products/" . $data['url'] . "?" . $colorUrl . $sleeveUrl . $patternUrl . $sizesUrl;
        return redirect::to($finalUrl);
    }

    public function searchProducts(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            $categories = Category::with('categories')->where(['parent_id' => 0])->get();
            $search_product = $data['product'];
            /* $productsAll = Product::where('product_name', 'like', '%' . $search_product . '%')->orwhere('product_code', $search_product)->where('status', 1)->paginate();*/
            $productsAll = Product::where(function ($query) use ($search_product) {
                $query->where('product_name', 'like', '%' . $search_product . '%')
                    ->orWhere('product_code', 'like', '%' . $search_product . '%')
                    ->orWhere('product_color', 'like', '%' . $search_product . '%')
                    ->orWhere('description', 'like', '%' . $search_product . '%')
                    ->orWhere('care', 'like', '%' . $search_product . '%');
            })->where('status', 1)->paginate();

            $breadcrumb = "<a href='/'>Home</a> /".$search_product;

            return view('products.listing')->with(compact('categories', 'productsAll'
                , 'search_product','breadcrumb'));
        }
    }

    public function checkPincode(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            return $pincodeCount = DB::table('pincodes')->where('pincode', $data['pincode'])->count();
        }
    }
}

