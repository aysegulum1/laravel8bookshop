<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use App\Models\Cargoes\CargoCompanies;
use App\Models\Product\ProductImage;
use App\Models\Product\ProductTags;
use App\Models\Product\Tags;
use App\Models\Showcase\ProductShowcase;
use App\Models\Showcase\Showcase;
use App\Models\StockMovements\StockMovement;
use App\Models\Supplier\Supplier;
use App\Models\Settings\SettingNecessaryCardProduct;
use Illuminate\Http\Request;
use App\Classes\MyResponseClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Product\Product;
use App\Models\Product\ProductExtra;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductProperty;
use App\Models\Product\ProductProductCategory;
use App\Models\Product\ProductVariantPrice;
use App\Models\Variants\Variant;
use App\Models\Variants\VariantOptions;
use App\Models\Variants\ProductVariant;
use App\Models\Variants\ProductVariantOption;
use Illuminate\Support\Facades\Response;
use App\Models\Brand\Brand;
use App\Models\Warehouse\Warehouse;
use App\Models\Warehouse\Racks;
use App\Models\Warehouse\StockedProducts;
use Exception;
use Illuminate\Support\Facades\Storage;
use Svg\Tag\Image;

class ProductController extends Controller
{

    //Products listeme fonksiyonu
    public function ProductsList()
    {
        return view('admin.products.list');
    }

    //Products görüntüleme fonksiyonu
    public function ProductsGetId(Request $request, $id)
    {
        $product = Product::where('id',$id)->first();
        $variant_color_id = Variant::where('slug','renk')->pluck('id');
        $pvp = ProductVariantPrice::where('product_id', $id)->pluck('id');
        $stockMovement = StockMovement::whereIn('product_variant_price_id', $pvp)->where('type', 0)->get();
        $productExtra = ProductExtra::where('product_id',$id)->first();
        $product_property= ProductProperty::where('product_id',$id)->get();
        $product_combined = explode(",", $productExtra->combined_product);
        $product_recommend = explode(",", $productExtra->recommend_product);
        $product_showcase = explode(",", $productExtra->showcase_order);
        $product_list = Product::all();
        $productImages = ProductImage::where('product_id',$id)->where('option_id',NULL)->where('deleted_at',NULL)->get();
        $variantImages = ProductImage::where('product_id',$id)->where('option_id', '!=', Null)->where('deleted_at',NULL)->get();
        $variantsList = Variant::all();
        $brandList = Brand::all();
        $suppliersEdit = Supplier::where('is_status',1)->get();
        $productCategories = ProductCategory::whereNull('top_category_id')->get();
        $productCategory = ProductProductCategory::where('product_id', $id)->get();
        $productVariant = ProductVariant::where('product_id',$id)->first();
        $ProductVariantPrices = ProductVariantPrice::where('product_id',$id)->get();
        $last_id = ProductVariantPrice::orderBy('id', 'DESC')->pluck('id')->first();
        $showcases = Showcase::where('is_status', 1)->get();
        $variantsOptColor = VariantOptions::where('variant_id', $variant_color_id)->get();
        $exist_image_variants = ProductImage::where('product_id', $id)->pluck('option_id');
        $exist_color_variants = ProductVariantPrice::where('product_id', $id)->get();
        $setNecCardProd = SettingNecessaryCardProduct::first();
        $smNull = StockMovement::where('product_variant_price_id', $ProductVariantPrices[0]->id)->first();

        $exist_color_ids = [];
        foreach ($exist_color_variants as $key => $value) {
           foreach ($variantsOptColor as $key1 => $value1){
                    if ($value->option1 == $value1->id) {
                        array_push($exist_color_ids,$value->option1);
                    }
                    if ($value->option2 == $value1->id) {
                        array_push($exist_color_ids,$value->option2);
                    }
                    if ($value->option3 == $value1->id) {
                        array_push($exist_color_ids,$value->option3);
                    }
                    if ($value->option4 == $value1->id) {
                        array_push($exist_color_ids,$value->option4);
                    }
                    if ($value->option5 == $value1->id) {
                        array_push($exist_color_ids,$value->option5);
                    }
            }
        }
        $exist_options_buttons = VariantOptions::findMany($exist_color_ids);
        $exist_options = VariantOptions::findMany($exist_image_variants);
        $variantControl = ProductVariantPrice::where('product_id', $id)->whereNotNull('option1')->first();
        if ($variantControl == null) {
            $pvp_id = ProductVariantPrice::where('product_id', $id)->first();
            isset($pvp_id) ? $baslangicStok = stock($pvp_id->id) : $baslangicStok = null;
        }
        else {
            $baslangicStok = null;
        }

        if (isset($productVariant)) {
            $variantsOptionList = VariantOptions::where('variant_id',$productVariant->variant_id)->get();
        }else{
            $variantsOptionList = null;
        }

        if ((($product->price2 == null) || ($product->price2 == '0.00')) && (($product->price3 == null) || ($product->price3 == '0.00')) && (($product->price4 == null) || ($product->price4 == '0.00')) 
          && (($product->price5 == null) || ($product->price5 == '0.00')) && ($product->purchase_price == '0.00')
          && ($product->market_price == '0.00') && ($product->rate_remittance == '0.00') && ($productExtra->number_installment == null)
           ) {
            $price_struct = 0;
         }else{
            $price_struct = 1;
        }

        $getAllTags=Tags::get();
        $sizeAllTags=count($getAllTags);
        $getTags= ProductTags::where('product_id',$id)->get();
        $selectedTags=[];
        $size=count($getTags);
        for($i=0; $i<$size; $i++)
        {
            $getTag=Tags::where('id',$getTags[$i]->tag_id)->first();
            $selectedTags[$i]=$getTag;
        }

        $sizeSelectedTags=count($selectedTags);
        return view('admin.products.edit',compact('product','productExtra','productCategories','last_id', 'productCategory', 'showcases',
            'variantsList','variantsOptionList','productVariant','brandList','productImages', 'variantControl', 'baslangicStok','product_property',
            'ProductVariantPrices','product_list','product_recommend','product_combined','product_showcase','suppliersEdit', 'stockMovement','variantsOptColor',
            'getAllTags','selectedTags','sizeAllTags','sizeSelectedTags','variantImages', 'exist_options_buttons','exist_options','price_struct','setNecCardProd', 'smNull'));
    }

    //Products ekleme sayfası çağırma fonksiyonu
    public function ProductsCreate()
    {
        $variantsList= Variant::all();
        $productCategories = ProductCategory::whereNull('top_category_id')->get();
        $brandList = Brand::all();
        $setNecCardProd = SettingNecessaryCardProduct::first();
        $suppliers = Supplier::where('is_status',1)->get();
        $variantsOptColor = VariantOptions::where('variant_id',1)->get();
        $last_id = ProductVariantPrice::orderBy('id', 'DESC')->pluck('id')->first();
        $showcases = Showcase::where('is_status', 1)->orderBy('order')->get();
        $getAllTags=Tags::get();

        return view('admin.products.create',compact('getAllTags','productCategories','variantsList','brandList','variantsOptColor' ,'suppliers', 'last_id', 'showcases','setNecCardProd'));
    }


    public function ProductDeleteImage(Request $request)
    {
        $id = $request->id;
        $productImage = ProductImage::where('id',$id)->first();
        $remaining_count = ProductImage::where('product_id',$productImage->product_id)->where('option_id',$productImage->option_id)->get();
        if (isset($productImage)) {
            $productImage->delete();
            return response()->json(['status' => true,'message' => __('messages.deleteImage'),'remaining_count'=>$remaining_count]);
        }else{
            return response()->json(['status' => false,'message' => __('messages.No photo found')]);
        }
    }




    //Products ekleme fonksiyonu
    public function ProductsSave(Request $request)
    {
        //ini_set('memory_limit', '-1'); //@sunucu işlemleri  için
        $images = $request->file('product_images');
        $auth = Auth::user();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'image|mimes:jpg,jpeg,png,webp'
        ]);

        $dublicateControl1= Product::where('stock_code',$request->stock_code)->first();
        $dublicateControl2= Product::where('barcode',$request->barcode)->first();
        if (isset($dublicateControl1)) {
            return Response::json(array(
                'status' => false,
                'messages' =>__('messages.The Stock Code You Entered Already Exists'),
                'reply' => __('messages.Entering Same Data')
            ));
        }

        if (isset($dublicateControl2)) {
            return Response::json(array(
                'status' => false,
                'messages' => __('messages.The Barcode You Entered Already Exists'),
                'reply' =>  __('messages.Entering Same Data')
            ));
        }


        if ($validator->fails()) {
            return  MyResponseClass::error(__('messages.Errors'));
        }else{
            if (isset($request->is_active)) {
                $is_active = $request->is_active;
            }else{
                $is_active = 0;
            }
            $trimmed_vat_rate = rtrim($request->vat_rate, '%');
            $product_discount =  $this->floatVal(explode('%', $request->discount)[0]);

            $newProducts = new Product();
            $newProducts->name = $request->name;
            $newProducts->slug = Str::slug($request->name.'-'.$request->stock_code);
            $newProducts->is_active = $is_active;
            $newProducts->stock_code = $request->stock_code;
            $newProducts->stock_unit = $request->stock_unit;
            $newProducts->discount = explode('%', $request->discount);
            $newProducts->discount_type = $request->discount_type;
            $newProducts->barcode = $request->barcode;
            $newProducts->money_unit = $request->money_unit;
            $newProducts->vat_status = $request->vat_status;
            $newProducts->vat_rate = $trimmed_vat_rate;
            $newProducts->critical_stock_limit = $request->critical_stock_limit;
            $newProducts->customer_stock_limit = $request->maximum_buy_limit;

            
            for($m = 1; $m <= 5; $m++){
                $prC="price".$m;
                $activePriceVal="activePriceVal".$m;
                $basic_price="basic_price".$m;
                $calculated_vat_price="calculated_vat_price".$m;
                $currentPrice = floatVal(str_replace(".", "", $request->$prC));
                if (isset($request->$prC) && ($request->$prC > 0)) {
                    if ($request->discount_type == "ORAN") {
                        $activePriceVal = $request->vat_status == 1 ? $currentPrice-$currentPrice*$product_discount/100 : $currentPrice * (1+$trimmed_vat_rate/100)-$currentPrice * (1+$trimmed_vat_rate/100)*$product_discount/100;
                        if ($request->vat_status == 1) {
                            $newProducts->$calculated_vat_price = $activePriceVal-($activePriceVal/(1+$trimmed_vat_rate/100)); 
                        } else if($request->vat_status == 0) {
                            $newProducts->$calculated_vat_price = $activePriceVal - ($currentPrice-$currentPrice*$product_discount/100); 
                        }
                    }else if($request->discount_type == "TL"){
                        $activePriceVal = $request->vat_status == 1 ? $currentPrice-$product_discount: ($currentPrice-$product_discount) * (1+$trimmed_vat_rate/100);
                        if ($request->vat_status == 1) {
                            $newProducts->$calculated_vat_price = $activePriceVal-($activePriceVal/(1+$trimmed_vat_rate/100));
                        } else if($request->vat_status == 0) {
                            $newProducts->$calculated_vat_price = $activePriceVal - ($currentPrice-$product_discount);
                        }
                    }
                    $newProducts->$prC = $activePriceVal;
                    $newProducts->$basic_price = $currentPrice;
                }
            }

            $newProducts->purchase_price = $this->floatVal(str_replace(".", "",$request->purchase_price));
            $newProducts->market_price = $this->floatVal(str_replace(".", "",$request->market_price));
            $newProducts->brand_id = $request->brand_id;
            $newProducts->language_id = 1;
            $newProducts->author_id = $auth->id;

            $decimal_remittance = explode('%', $request->rate_remittance);
            $newProducts->rate_remittance = $this->floatVal($decimal_remittance[0]);

            $decimal_discount = explode('%', $request->discount);
            $newProducts->discount = $this->floatVal($decimal_discount[0]);



            DB::beginTransaction();



            $path = storage_path('app/public/product/quality/');
            if(!\File::isDirectory($path)){
                \File::makeDirectory($path, 0777, true, true);
            }
            $path = storage_path('app/public/product/thumb/');
            if(!\File::isDirectory($path)){
                \File::makeDirectory($path, 0777, true, true);
            }
            $path = storage_path('app/public/product/card/');
            if(!\File::isDirectory($path)){
                \File::makeDirectory($path, 0777, true, true);
            }
            $path = storage_path('app/public/product/zoom/');
            if(!\File::isDirectory($path)){
                \File::makeDirectory($path, 0777, true, true);
            }



            if (isset($images[0])){
                $image = $images[0];

                $imageName = time().'-'.'-'.rand(1,999999).'-'.Str::slug($request->name).'.'.$image->extension();

                $newProducts->image = $imageName;
                $img = \Image::make($images[0]->path());
                try {
                    $img->save(storage_path('app/public/product/quality/'.$imageName));
                    $img = \Image::make($images[0]->path());
                    $img->resize(120, 120);
                    $img->save(storage_path('app/public/product/thumb/'.$imageName));
                    $cardImage = \Image::make($images[0]->path());
                    $cardImage->resize(450, 510);
                    $cardImage->save(storage_path('app/public/product/card/'.$imageName));
                    $zoomImg = \Image::make($images[0]->path());
                    $zoomImg->resize(1400, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $zoomImg->save(storage_path('app/public/product/zoom/'.$imageName));
                }catch(\Exception $e){
                    DB::rollback();
                    return Response::json(array(
                        'status' => false,
                        'messages' => $e->getMessage(),
                        'reply' => __('messages.Error Adding Main Image')
                    ));
                }
            }else{
                return response()->json(__('messages.You cannot leave the product main image blank'),400);
            }

            try {
                $newProducts->save();
            }catch(\Exception $e)
            {
                DB::rollback();
                return Response::json(array(
                    'status' => false,
                    'messages' => $e->getMessage(),
                    'reply' =>__('myTr.admin.Product Add Error')
                ));
            }


            try {

                $productShowcaseImage = new ProductImage();
                $productShowcaseImage->product_id = $newProducts->id;
                $productShowcaseImage->image = $imageName;
                $productShowcaseImage->save();

            }catch(\Exception $e)
            {
                DB::rollback();
                return Response::json(array(
                    'status' => false,
                    'messages' => $e->getMessage(),
                    'reply' =>__('messages.Error Adding Main Image')
                ));
            }



            //-----------------------Product Images Operations
            $newProductID = $newProducts->id;

            foreach ($images as $k => $image){
                if ($k>0){
                    $imageName = time().'-'.rand(1,999999).'-'.Str::slug($newProducts->name).'.'.$image->extension();
                    $productImage = new ProductImage();
                    $productImage->product_id = $newProductID;
                    $productImage->image = $imageName;
                    try {
                        $productImage->save();
                        $zoomImg = \Image::make($images[$k]->path());
                        $zoomImg->resize(null,1400, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        $zoomImg->save(storage_path('app/public/product/zoom/'.$imageName));
                        $img = \Image::make($images[$k]->path());
                        $img->save(storage_path('app/public/product/quality/'.$imageName));
                        $img = \Image::make($images[$k]->path());
                        $img->resize(120, 120);
                        $img->save(storage_path('app/public/product/thumb/'.$imageName));
                        $cardImage = \Image::make($images[$k]->path());
                        $cardImage->resize(450, 510);
                        $cardImage->save(storage_path('app/public/product/card/'.$imageName));
                        $upload = $image;
                        $upload->move(storage_path('app/public/product/'),$imageName);

                    }catch(\Exception $e){
                        DB::rollback();
                        return Response::json(array(
                            'status' => false,
                            'messages' => $e->getMessage(),
                            'reply' => __('messages.Error Adding Main Image')
                        ));
                    }
                }
            }

            //save varyant image
            $image_variants = VariantOptions::whereHas('variant', function ($q){
                $q->where('slug', 'renk');
            })->get();

            foreach ($image_variants as $row)
            {
                $name = "variant_images".($row->id);

                if (isset($request->$name))
                {
                    foreach ($request->$name as $item)
                    {
                        $imageName = time().'-'.rand(1,999999).'-'.Str::slug($newProducts->name).'_variant_image'.'.'.$item->extension();
                        $productImage = new ProductImage();
                        $productImage->product_id = $newProductID;
                        $productImage->image = $imageName;
                        $productImage->option_id = ($row->id);
                        try {
                            $productImage->save();
                            $img = \Image::make($item->path());
                            $img->save(storage_path('app/public/product/quality/'.$imageName));
                            $img = \Image::make($item->path());
                            $img->resize(120, 120);
                            $img->save(storage_path('app/public/product/thumb/'.$imageName));
                            $cardImage = \Image::make($item->path());
                            $cardImage->resize(450, 510);
                            $cardImage->save(storage_path('app/public/product/card/'.$imageName));
                            $zoomImg = \Image::make($item->path());
                            $zoomImg->resize(null,1400, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                            $zoomImg->save(storage_path('app/public/product/zoom/'.$imageName));
                            $item->move(storage_path('app/public/product/'),$imageName);
                        }catch(\Exception $e){
                            DB::rollback();
                            return Response::json(array(
                                'status' => false,
                                'messages' => $e->getMessage(),
                                'reply' => __('messages.Error Adding Variant Image')
                            ));
                        }
                    }
                }
            }



            //save property
            if (isset($request->properties) &&  0 < count($request->properties)) {
                foreach ($request->properties as $properval) {
                    $newProperty = new ProductProperty();
                    $newProperty->product_id  = $newProductID;
                    $newProperty->key =  $properval['key'];
                    $newProperty->value = $properval['value'];

                    try {
                        $newProperty->save();
                    }catch(\Exception $e){
                        DB::rollback();
                        return Response::json(array(
                            'status' => false,
                            'messages' => __('messages.Product Feature Adding Error Text'),
                            'reply' => __('messages.Product Feature Adding Error')
                        ));
                    }

                }
            }

            //ends property

            // save tags
            $getTags=$request->tag;
            if(isset($getTags)){
                foreach ($getTags as $tag)
                {
                    $issetTag=Tags::where('name',$tag)->first();
                    if(isset($issetTag))
                    {
                        $issetControl=ProductTags::where('product_id',$newProducts->id)->where('tag_id',$issetTag->id)->first();
                        if(! isset($issetControl)){

                            $newRelationshipTag = new ProductTags();
                            $newRelationshipTag->product_id =$newProducts->id;
                            $newRelationshipTag->tag_id =$issetTag->id;
                            try {
                                $newRelationshipTag->save();

                            }catch(\Exception $e){
                                DB::rollback();
                                return Response::json(array(
                                    'status' => false,
                                    'messages' => $e->getMessage(),
                                    'reply' => __('messages.Error Assigning Label to Product')
                                ));
                            }
                        }
                    }
                    else{
                        $newTags= new Tags();
                        $newTags->name = $tag;
                        $newTags->slug = Str::slug($tag);
                        try {
                            $newTags->save();

                        }catch(\Exception $e)
                        {
                            DB::rollback();
                            return Response::json(array(
                                'status' => false,
                                'messages' => $e->getMessage(),
                                'reply' => __('messages.Tag Add Error')
                            ));
                        }

                        $issetControl=ProductTags::where('product_id',$newProducts->id)->where('tag_id',$newTags->id)->first();
                        if(!isset($issetControl))
                        {
                            $newRelationshipTag = new ProductTags();
                            $newRelationshipTag->product_id =$newProducts->id;
                            $newRelationshipTag->tag_id =$newTags->id;
                            try {
                                $newRelationshipTag->save();
                            }catch(\Exception $e)
                            {
                                DB::rollback();
                                return Response::json(array(
                                    'status' => false,
                                    'messages' => $e->getMessage(),
                                    'reply' => __('messages.Error Assigning Label to Product')
                                ));
                            }
                        }

                    }
                }
            }
            //end save tags

            //--------------------------------------------------

            if (isset($newProductID)) {
                if ($request->product_details != null || $request->number_installment != null || $request->information_area != null ||
                    $request->cargo_cost != null ||  $request->cargo_weight != null  || $request->warranty_period != null
                    || $request->gift_text != null ||  $request->meta_title != null || $request->meta_desc != null
                    || $request->meta_key != null
                ) {
                    $newProductExtra = new ProductExtra();
                    $newProductExtra->product_id = $newProductID;
                    $newProductExtra->product_details = $request->product_details;
                    $newProductExtra->content = $request->productcontent;
                    $newProductExtra->number_installment  = $request->number_installment;
                    $newProductExtra->information_area  = $request->information_area;

                    if ($request->free_cargo == 1)
                    {
                        $cargo = 0;
                    }
                    else if($request->special_cargo_status == "cargo_agirligiID")
                    {
                        $cargo_company = CargoCompanies::where('status', 1)->first();
                        if (isset($cargo_company))
                        {
                            $cargo = cargoDeciPrice($cargo_company->id, $request->cargo_weight);
                        }
                        else
                        {
                            $cargo = null;
                        }
                    }
                    else if($request->special_cargo_status == "cargo_priceID")
                    {
                        $cargo = $request->cargo_cost;
                    }
                    else
                    {
                        $cargo = null;
                    }

                    $newProductExtra->cargo_cost  = $cargo;
                    $newProductExtra->cargo_weight  = $request->cargo_weight;
                    $newProductExtra->warranty_period = $request->warranty_period;
                    $newProductExtra->persons_supplier = $request->persons_supplier;
                    if (isset($request->recommend_product)) {
                        $newProductExtra->recommend_product = implode(',', $request->recommend_product);
                    }
                    if (isset($request->combined_product)) {
                        $newProductExtra->combined_product = implode(',', $request->combined_product);
                    }


                    $newProductExtra->gift_text   = $request->gift_text;
                    $newProductExtra->meta_title  = $request->meta_title;
                    $newProductExtra->meta_desc  = $request->meta_desc;
                    $newProductExtra->meta_key  = $request->meta_key;

                    try {
                        $newProductExtra->save();
                    }catch(\Exception $e)
                    {
                        DB::rollback();
                        return Response::json(array(
                            'status' => false,
                            'messages' => $e->getMessage(),
                            'reply' => __('messages.Error Adding Product Details')
                        ));
                    }


                }

                $showcases = Showcase::findMany($request->showcase_order);
                $newProducts->showcases()->attach($showcases);

                $categories = ProductCategory::findMany($request->productCategory);
                $newProducts->productCategories()->attach($categories);

                if (isset($request->variant_id))
                {

                    $variantoptionlist =[];

                    if(empty($request->get('variant_option'))) {
                        return Response::json(array(
                            'status' => false,
                            'messages' => '  {{ __("myTr.admin.Variant Image Cannot Be Added Without Selecting Variant") }}',
                            'reply' => ' {{ __("myTr.admin.Warning") }}'
                        ));
                    }

                    foreach($request->get('variant_option') as $m => $item3){

                        array_push($variantoptionlist,$request->get('variant_option')[$m]);
                    }

                    foreach($request->variant_id as $i => $item)
                    {
                        $newProductVariant = new ProductVariant();
                        $newProductVariant->product_id = $newProductID;
                        $newProductVariant->variant_id= $request->get('variant_id')[$i];

                        try {
                            $newProductVariant->save();

                        }catch(\Exception $e)
                        {
                            DB::rollback();
                            return Response::json(array(
                                'status' => false,
                                'messages' => $e->getMessage(),
                                'reply' => __('messages.Error Adding Variant')
                            ));
                        }


                        if (ProductVariant::where('product_id', $newProductID)->where('variant_id',$request->get('variant_id')[$i])->exists()) {
                            $productVariant = ProductVariant::where('product_id', $newProductID)->where('variant_id',$request->get('variant_id')[$i])->first();

                            foreach($variantoptionlist as $j => $item2)
                            {
                                foreach($variantoptionlist[$j] as $t=>$item4){
                                    if (VariantOptions::where('variant_id',$request->get('variant_id')[$i])->where('id',$variantoptionlist[$j][$t])->exists()) {
                                        $newProductVariantOption = new ProductVariantOption();
                                        $newProductVariantOption->product_variant_id  = $productVariant->id;
                                        $newProductVariantOption->option_id = $variantoptionlist[$j][$t];
                                        try {
                                            $newProductVariantOption->save();
                                        }catch(\Exception $e)
                                        {
                                            DB::rollback();
                                            return Response::json(array(
                                                'status' => false,
                                                'messages' => $e->getMessage(),
                                                'reply' => __('messages.Error Adding Variant Option')
                                            ));
                                        }
                                    }
                                }
                            }
                        }

                    }
                    foreach($request->product_variant_name as $key => $value)
                    {
                        isset($request->product_variant_status[$key]) && $request->product_variant_status[$key] == true ? $status = 1 : $status = 0;

                        $name = 'variant_price_option'.($key+1);
                        $priceOption = explode('_', $request->$name);
                        $productVariantPrice = new ProductVariantPrice();
                        $productVariantPrice->product_id = $newProducts->id;
                        $productVariantPrice->stock_code = variantCode($newProductID, explode("-", $request->product_variant_name[$key]));
                        $productVariantPrice->name = $request->product_variant_name[$key];
                       

                        //variant eklenme sıralaması
                        $variantShortList = [];
                        foreach($variantoptionlist as $inc=>$val){
                            array_push($variantShortList,VariantOptions::where('id',$val[0])->first()->variant_id);

                        }
                        $productVariantPrice->short_variant_ids =  implode(",", $variantShortList);

                         
                        for($i = 1; $i <= 5; $i++)
                        {
                            $prc = "price".$i;
                            $pvp = "product_variant_".$prc;
                            $activePriceVariantVal="activePriceVariantVal".$i;
                            $basic_price="basic_price".$i;
                            $calculated_vat_price="calculated_vat_price".$i;
                            $currentVariantPrice = floatVal(str_replace(".", "", $request->$pvp[$key]));
                            if (isset($request->$pvp[$key]) && ($request->$pvp[$key] > 0)) {
                                if ($request->variant_discount_type[$key] == "ORAN") {
                                    $activePriceVariantVal = $request->vat_variant_status[$key] == 1 ? $currentVariantPrice-$currentVariantPrice*$this->floatVal(rtrim($request->variant_discount[$key], '%'))/100 : $currentVariantPrice * (1+$trimmed_vat_rate/100)-$currentVariantPrice * (1+$trimmed_vat_rate/100)*$this->floatVal(rtrim($request->variant_discount[$key], '%'))/100;
                                    if ($request->vat_variant_status[$key] == 1) {
                                        $productVariantPrice->$calculated_vat_price = $activePriceVariantVal-($activePriceVariantVal/(1+$trimmed_vat_rate/100)); 
                                    } else if($request->vat_variant_status[$key] == 0) {
                                        $productVariantPrice->$calculated_vat_price = $activePriceVariantVal - ($currentVariantPrice-$currentVariantPrice*$product_discount/100); 
                                    }
                                }else if($request->variant_discount_type[$key] == "TL"){
                                    $activePriceVariantVal = $request->vat_variant_status[$key] == 1 ? $currentVariantPrice-$this->floatVal(rtrim($request->variant_discount[$key], '%')): ($currentVariantPrice-$this->floatVal(rtrim($request->variant_discount[$key], '%'))) * (1+$trimmed_vat_rate/100);
                                    if ($request->vat_variant_status[$key] == 1) {
                                        $productVariantPrice->$calculated_vat_price = $activePriceVariantVal-($activePriceVariantVal/(1+$trimmed_vat_rate/100));
                                    } else if($request->vat_variant_status[$key] == 0) {
                                        $productVariantPrice->$calculated_vat_price = $activePriceVariantVal - ($currentVariantPrice-floatVal(rtrim($request->variant_discount[$key], '%')));
                                    }
                                }
                                $productVariantPrice->$prc = $activePriceVariantVal;
                                $productVariantPrice->$basic_price = $currentVariantPrice;
                            }

                        }


                        $productVariantPrice->supplier_id = $request->persons_supplier ?? null;
                        $productVariantPrice->status = $status;
                        $productVariantPrice->vat_status = $request->vat_variant_status[$key];

                        

                        $productVariantPrice->discount_type = $request->variant_discount_type[$key];
                        $productVariantPrice->discount = rtrim($request->variant_discount[$key], '%');
                        $productVariantPrice->purchase_price = $this->floatVal(str_replace(".", "",$request->variant_purchase_price[$key]));
                        $productVariantPrice->deci = $request->variant_cargo_weight[$key];

                        $productVariantOptionId = VariantOptions::whereIn('name', explode("-" ,$request->product_variant_name[$key]))->get();

                        for ($i = 0; $i < count($productVariantOptionId); $i++)
                        {
                            $opt = "option".($i+1);
                            $productVariantPrice->$opt = $productVariantOptionId[$i]->id;
                        }

                        try {
                            $productVariantPrice->save();
                        }catch(\Exception $e)
                        {
                            DB::rollback();
                            return Response::json(array(
                                'status' => false,
                                'messages' => $e->getMessage(),
                                'reply' =>__('messages.Error Adding to Product Value Table')
                            ));
                        }
                        //stok hareketleri
                        if ($productVariantPrice->save())
                        {
                            $stockMovement = new StockMovement();
                            $stockMovement->product_variant_price_id = $productVariantPrice->id;
                            $stockMovement->type = 0;
                            $stockMovement->amount = $request->product_variant_stock[$key] ?? null;
                            $stockMovement->purchase_price = $this->floatVal($request->variant_purchase_price[$key]) ?? null;
                            $stockMovement->vat = $trimmed_vat_rate;
                            $stockMovement->billing_no = $request->billing_no ?? null;
                            $stockMovement->billing_date = $request->billing_date ?? null;
                            $stockMovement->desc = $request->desc ?? null;
                            $stockMovement->supplier_id = $request->persons_supplier ?? null;

                            try {
                                $stockMovement->save();
                            }catch(\Exception $e)
                            {
                                DB::rollback();
                                return Response::json(array(
                                    'status' => false,
                                    'messages' => $e->getMessage(),
                                    'reply' => __('messages.Error Adding Inventory Transactions')
                                ));
                            }
                        }
                    }
                }else{
                    $trimmed_vat_rate_variant_price = rtrim($request->vat_rate, '%');
                    $product_discount_variant_price =  $this->floatVal(explode('%', $request->discount)[0]);
                    $productVariantPrice = new ProductVariantPrice();
                    $productVariantPrice->product_id = $newProducts->id;
                    $productVariantPrice->name = $request->name;
                    $productVariantPrice->stock_code = $request->stock_code;
                    $productVariantPrice->vat_status = $request->vat_status;
                    $productVariantPrice->discount_type = $request->discount_type;
                    $productVariantPrice->discount = rtrim($request->discount, '%');
                  

                    

                    for($n = 1; $n <= 5; $n++){
                        $prc="price".$n;
                        $activePriceVal="activePriceVal".$n;
                        $basic_price="basic_price".$n;
                        $calculated_vat_price="calculated_vat_price".$n;
                        $currentPrice = floatVal(str_replace(".", "", $request->$prc));
                        if (isset($request->$prc) && ($request->$prc > 0)) {
                            if ($request->discount_type == "ORAN") {
                                $activePriceVal = $request->vat_status == 1 ? $currentPrice-$currentPrice*$product_discount/100 : $currentPrice * (1+$trimmed_vat_rate/100)-$currentPrice * (1+$trimmed_vat_rate/100)*$product_discount/100;
                                if ($request->vat_status == 1) {
                                    $productVariantPrice->$calculated_vat_price = $activePriceVal-($activePriceVal/(1+$trimmed_vat_rate/100)); 
                                } else if($request->vat_status == 0) {
                                    $productVariantPrice->$calculated_vat_price = $activePriceVal - ($currentPrice-$currentPrice*$product_discount/100); 
                                }
                            }else if($request->discount_type == "TL"){
                                $activePriceVal = $request->vat_status == 1 ?  $currentPrice-$product_discount: ($currentPrice-$product_discount) * (1+$trimmed_vat_rate/100);
                                if ($request->vat_status == 1) {
                                    $productVariantPrice->$calculated_vat_price = $activePriceVal-($activePriceVal/(1+$trimmed_vat_rate/100));
                                } else if($request->vat_status == 0) {
                                    $productVariantPrice->$calculated_vat_price = $activePriceVal - ($currentPrice-$product_discount);
                                }
                            }
                            $productVariantPrice->$prc = $activePriceVal;
                            $productVariantPrice->$basic_price = $currentPrice;
                        }
                    }

                    $productVariantPrice->supplier_id = $request->persons_supplier ?? null;
                    $productVariantPrice->status = 1;
                    $productVariantPrice->deci = $request->cargo_weight ?? null;
                    $productVariantPrice->purchase_price = $this->floatVal($request->purchase_price) ?? null;

                    try {
                        $productVariantPrice->save();
                    }catch(\Exception $e){
                        DB::rollback();
                        return Response::json(array(
                            'status' => false,
                            'messages' => $e->getMessage(),
                            'reply' => __('messages.Error Adding Inventory Transactions')
                        ));
                    }

                    if ($productVariantPrice->save())
                    {
                        $stockMovement = new StockMovement();
                        $stockMovement->product_variant_price_id = $productVariantPrice->id;
                        $stockMovement->type = 0;
                        $stockMovement->amount = $request->stock;
                        $stockMovement->vat = $trimmed_vat_rate;
                        $stockMovement->purchase_price = $this->floatVal($request->purchase_price);
                        $stockMovement->billing_no = $request->billing_no ?? null;
                        $stockMovement->billing_date = $request->billing_date ?? null;
                        $stockMovement->desc = $request->desc ?? null;
                        $stockMovement->supplier_id = $request->persons_supplier ?? null;
                        try {
                            $stockMovement->save();
                        }catch(\Exception $e){
                            DB::rollback();
                            return Response::json(array(
                                'status' => false,
                                'messages' => $e->getMessage(),
                                'reply' => __('messages.Error Adding Inventory Transactions')
                            ));
                        }
                    }
                }
            }


            DB::commit();

            $success =  MyResponseClass::success(__('messages.SuccessAdd'));
            return response()->json($success);
        }

    }




    //Products güncelleme fonksiyonu

    public function ProductsUpdate(Request $request)
    {
        //ini_set('memory_limit', '-1'); //@sunucu işlemleri  için
        $id = $request->id;
        $auth = Auth::user();
        $images = $request->file('product_images');
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'image|mimes:jpg,jpeg,png,webp'
        ]);

        $dublicateControl1= Product::where('stock_code',$request->stock_code)->where('id','!=',$id)->first();
        $dublicateControl2= Product::where('barcode',$request->barcode)->where('id','!=',$id)->first();

        if (isset($dublicateControl1)) {
            return Response::json(array(
                'status' => false,
                'messages' => __('messages.The Stock Code You Entered Already Exists'),
                'reply' => __('messages.Entering Same Data')
            ));
        }

        if (isset($dublicateControl2)) {
            return Response::json(array(
                'status' => false,
                'messages' => __('messages.The Stock Code You Entered Already Exists'),
                'reply' => __('messages.Entering Same Data')
            ));
        }

        $trimmed_vat_rate = $request->vat_rate;
        if ($validator->fails()) {
            return  MyResponseClass::error(__('messages.ErrorsMessages'));
        }else{
            $products= Product::where('id',$id)->first();

            if (! isset($products)) {
                return  MyResponseClass::resultNull();
            }
            $products->name = $request->name;
            $products->slug = Str::slug($request->name.'-'.$request->stock_code);
            $products->is_active = $request->is_active;
            $products->stock_code = $request->stock_code;
            $products->stock_unit = $request->stock_unit;
            $products->discount_type = $request->discount_type;
            $products->barcode = $request->barcode;
            $products->money_unit = $request->money_unit;
            $products->vat_status = $request->vat_status;
            $products->vat_rate = $trimmed_vat_rate;
            $products->critical_stock_limit = $request->critical_stock_limit;
            $products->customer_stock_limit = $request->maximum_buy_limit;

            $trimmed_vat_rate = rtrim($request->vat_rate, '%');
            $product_discount =  $this->floatVal(explode('%', $request->discount)[0]);
  
            for($m = 1; $m <= 5; $m++){
                $prC="price".$m;
                $activePriceVal="activePriceVal".$m;
                $basic_price="basic_price".$m;
                $calculated_vat_price="calculated_vat_price".$m;
                $currentPrice = floatVal(str_replace(".", "", $request->$prC));
                if (isset($request->$prC) && ($request->$prC > 0)) {
                    if ($request->discount_type == "ORAN") {
                        $activePriceVal = $request->vat_status == 1 ? $currentPrice-$currentPrice*$product_discount/100 : $currentPrice * (1+$trimmed_vat_rate/100)-$currentPrice * (1+$trimmed_vat_rate/100)*$product_discount/100;
                        if ($request->vat_status == 1) {
                            $products->$calculated_vat_price = $activePriceVal-($activePriceVal/(1+$trimmed_vat_rate/100)); 
                        } else if($request->vat_status == 0) {
                            $products->$calculated_vat_price = $activePriceVal - ($currentPrice-$currentPrice*$product_discount/100); 
                        }
                    }else if($request->discount_type == "TL"){
                        $activePriceVal = $request->vat_status == 1 ? $currentPrice-$product_discount: ($currentPrice-$product_discount) * (1+$trimmed_vat_rate/100);
                        if ($request->vat_status == 1) {
                            $products->$calculated_vat_price = $activePriceVal-($activePriceVal/(1+$trimmed_vat_rate/100));
                        } else if($request->vat_status == 0) {
                            $products->$calculated_vat_price = $activePriceVal - ($currentPrice- $product_discount);
                        }
                    }
                    $products->$prC = $activePriceVal;
                    $products->$basic_price = $currentPrice;
                }else{
                    $products->$calculated_vat_price = null;
                    $products->$prC = null;
                    $products->$basic_price = null;
                }
            }
          
            $products->purchase_price = $this->floatVal(str_replace(".", "",$request->purchase_price));
            $products->market_price = $this->floatVal(str_replace(".", "",$request->market_price));

            $products->brand_id = $request->brand_id;
            $products->language_id = 1;
            $products->author_id = $auth->id;

            $decimal_remittance = explode('%', $request->rate_remittance);
            $products->rate_remittance = $this->floatVal($decimal_remittance[0]);

            $decimal_discount = explode('%', $request->discount);
            $products->discount = $this->floatVal($decimal_discount[0]);

            DB::beginTransaction();

            if (isset($images[0])){
                $image = $images[0];
                $imageName = time().'-'.'-'.rand(1,999999).'-'.Str::slug($request->name).'.'.$image->extension();
                $image->storeAs('product',$imageName,'public');

                if ($products->image == null) {
                    $products->image = $imageName;
                    try {
                        $productShowcaseImage = new ProductImage();
                        $productShowcaseImage->product_id = $id;
                        $productShowcaseImage->image = $imageName;
                        $productShowcaseImage->save();

                    }catch(\Exception $e)
                    {
                        DB::rollback();
                        return Response::json(array(
                            'status' => false,
                            'messages' => $e->getMessage(),
                            'reply' =>__('messages.Error Adding Main Image')
                        ));
                    }
                }else{
                    $productImage = new ProductImage();
                    $productImage->product_id = $id;
                    $productImage->image = $imageName;
                    $productImage->save();
                }

                $zoomImg = \Image::make($images[0]->path());
                $zoomImg->resize(1400, null, function ($constraint) {
                    $constraint->aspectRatio();
                });



                try {
                    $zoomImg->save(storage_path('app/public/product/zoom/'.$imageName));
                    $img = \Image::make($images[0]->path());
                    $img->save(storage_path('app/public/product/quality/'.$imageName));
                    $img = \Image::make($images[0]->path());
                    $img->resize(120, 120);
                    $img->save(storage_path('app/public/product/thumb/'.$imageName));

                    $cardImage = \Image::make($images[0]->path());
                    $cardImage->resize(450, 510);
                    $cardImage->save(storage_path('app/public/product/card/'.$imageName));
                }catch(\Exception $e)
                {
                    DB::rollback();
                    return Response::json(array(
                        'status' => false,
                        'messages' => $e->getMessage(),
                        'reply' => __('messages.Error Adding Main Image')
                    ));
                }


            }
            if ($products->image == null) {
                return response()->json(['status' => false,'message' => __('messages.Product image is required')], 400);
            }

            try {
                $products->save();
            }catch(\Exception $e)
            {
                DB::rollback();
                return Response::json(array(
                    'status' => false,
                    'messages' => $e->getMessage(),
                    'reply' => __('messages.Product Update Error')
                ));
            }


            if (isset($images)) {
                foreach ($images as $k => $image){
                    if ($k>0){

                        $imageName = time().'-'.rand(1,999999).'-'.Str::slug($products->name).'.'.$image->extension();

                        $productImage = new ProductImage();
                        $productImage->product_id = $id;
                        $productImage->image = $imageName;
                        try {
                            $productImage->save();
                            $zoomImg = \Image::make($images[$k]->path());
                            $zoomImg->resize(1400, null, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                            $zoomImg->save(storage_path('app/public/product/zoom/'.$imageName));
                            $img = \Image::make($images[$k]->path());
                            $img->save(storage_path('app/public/product/quality/'.$imageName));
                            $img = \Image::make($images[$k]->path());
                            $img->resize(120, 120);
                            $img->save(storage_path('app/public/product/thumb/'.$imageName));
                            $cardImage = \Image::make($images[$k]->path());
                            $cardImage->resize(450, 510);
                            $cardImage->save(storage_path('app/public/product/card/'.$imageName));

                        }catch(\Exception $e)
                        {
                            DB::rollback();
                            return Response::json(array(
                                'status' => false,
                                'messages' => $e->getMessage(),
                                'reply' => __('messages.Image Update Error')
                            ));
                        }
                    }
                }
            }

            //save varyant image
            $image_variants = VariantOptions::whereHas('variant', function ($q){
                $q->where('slug', 'renk');
            })->get();
        
           
            foreach ($image_variants as $key=>$row)
            {
                $name = "variant_images".($row->id);
              
                if (isset($request->$name))
                {
                    foreach ($request->$name as $item)
                    {
                        $imageName = time().'-'.rand(1,999999).'-'.Str::slug($products->name).'_variant_image'.'.'.$item->extension();
                        $productImage = new ProductImage();
                        $productImage->product_id = $products->id;
                        $productImage->image = $imageName;
                        $productImage->option_id = $row->id;
                        try {
                            $productImage->save();
                            $img = \Image::make($item->path());
                            $img->save(storage_path('app/public/product/quality/'.$imageName));
                            $img = \Image::make($item->path());
                            $img->resize(120, 120);
                            $img->save(storage_path('app/public/product/thumb/'.$imageName));
                            $cardImage = \Image::make($item->path());
                            $cardImage->resize(450, 510);
                            $cardImage->save(storage_path('app/public/product/card/'.$imageName));
                            $zoomImg = \Image::make($item->path());
                            $zoomImg->resize(null,1400, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                            $zoomImg->save(storage_path('app/public/product/zoom/'.$imageName));
                            $item->move(storage_path('app/public/product/'),$imageName);
                        }catch(\Exception $e){
                            DB::rollback();
                            return Response::json(array(
                                'status' => false,
                                'messages' => $e->getMessage(),
                                'reply' => __('messages.Error Adding Variant Image')
                            ));
                        }
                    }
                }
            }


            //save property
            if (isset($request->properties) &&  0 < count($request->properties)) {
                foreach ($request->properties as $properval) {
                    $newProperty = new ProductProperty();
                    $newProperty->product_id  = $id;
                    $newProperty->key =  $properval['key'];
                    $newProperty->value = $properval['value'];

                    try {
                        $newProperty->save();
                    }catch(\Exception $e){
                        DB::rollback();
                        return Response::json(array(
                            'status' => false,
                            'messages' => __('messages.Product Feature Adding Error Text'),
                            'reply' =>__('messages.Product Feature Adding Error')
                        ));
                    }

                }
            }

            //ends property

            if (isset($products)) {
                if ($request->product_details != null || $request->number_installment != null || $request->information_area != null ||
                    $request->cargo_cost != null ||  $request->cargo_weight != null  || $request->warranty_period != null
                    || $request->gift_text != null ||  $request->meta_title != null || $request->meta_desc != null
                    || $request->meta_key != null
                ) {
                    $productExtra = ProductExtra::where('product_id', $id)->first();
                    if (isset($productExtra)) {
                        $productExtra->product_id = $id;
                        $productExtra->product_details = $request->product_details;
                        $productExtra->content = $request->productcontent;
                        $productExtra->number_installment  = $request->number_installment;
                        $productExtra->information_area  = $request->information_area;
                        $productExtra->cargo_cost  = $request->free_cargo == 1 ? 0 : $request->cargo_cost;
                        $productExtra->cargo_weight  = $request->cargo_weight;
                        $productExtra->warranty_period = $request->warranty_period;
                        $productExtra->persons_supplier = $request->persons_supplier;
                        if (isset($request->recommend_product)) {
                            $productExtra->recommend_product = implode(', ', $request->recommend_product);
                        }
                        if (isset($request->combined_product)) {
                            $productExtra->combined_product = implode(',', $request->combined_product);
                        }

                        //showcase varsa
                        $products->showcases()->sync($request->showcase_order);

                        $productExtra->gift_text   = $request->gift_text;
                        $productExtra->meta_title  = $request->meta_title;
                        $productExtra->meta_desc  = $request->meta_desc;
                        $productExtra->meta_key  = $request->meta_key;
                        try {
                            $productExtra->save();

                        }catch(\Exception $e)
                        {
                            DB::rollback();
                            return Response::json(array(
                                'status' => false,
                                'messages' => $e->getMessage(),
                                'reply' => __('messages.Product Detail Update Error')
                            ));
                        }

                    }else{
                        $newProductExtra = new ProductExtra();
                        $newProductExtra->product_id = $products->id;
                        $newProductExtra->product_details = $request->product_details;
                        $newProductExtra->content = $request->productcontent;
                        $newProductExtra->number_installment  = $request->number_installment;
                        $newProductExtra->information_area  = $request->information_area;
                        if ($request->free_cargo == 1)
                        {
                            $cargo = 0;
                        }
                        else if($request->special_cargo_status == "cargo_agirligiID")
                        {
                            $cargo_company = CargoCompanies::where('status', 1)->first();
                            $cargo = cargoDeciPrice($cargo_company->id, $request->cargo_weight);
                        }
                        else if($request->special_cargo_status == "cargo_priceID")
                        {
                            $cargo = $request->cargo_cost;
                        }
                        $newProductExtra->cargo_cost  = $cargo;
                        $newProductExtra->cargo_weight  = $request->cargo_weight;
                        $newProductExtra->warranty_period = $request->warranty_period;
                        if (isset($request->recommend_product)) {
                            $newProductExtra->recommend_product = implode(', ', $request->recommend_product);
                        }
                        if (isset($request->combined_product)) {
                            $newProductExtra->combined_product = implode(',', $request->combined_product);
                        }

                        //showcase yoksa
                        $showcases = Showcase::findMany($request->showcase_order);
                        $products->showcases()->attach($showcases);

                        $newProductExtra->gift_text   = $request->gift_text;
                        $newProductExtra->meta_title  = $request->meta_title;
                        $newProductExtra->meta_desc  = $request->meta_desc;
                        $newProductExtra->meta_key  = $request->meta_key;
                        try {
                            $newProductExtra->save();

                        }catch(\Exception $e)
                        {
                            DB::rollback();
                            return Response::json(array(
                                'status' => false,
                                'messages' => $e->getMessage(),
                                'reply' => __('messages.Product Detail Update Error')
                            ));
                        }

                    }
                }

                $products->productCategories()->sync($request->productCategory);


                if (isset($request->variantOptionid))
                {
                    foreach($request->variantOptionid as $key => $value)
                    {
                        $name = 'variant_price_option'.($key+1);
                        $priceOption = explode('_', $request->$name);
                        $productVariantPrice = ProductVariantPrice::where('id',$request->variantOptionid[$key])->first();
                        $productVariantPrice->stock_code = variantCode($id, explode("-", $request->variant_optionName[$key]));
                        $productVariantPrice->name = $request->variant_optionName[$key];

                        $stat = "variant_optionStatus".$productVariantPrice->id;
                        $status = $request->$stat ?? 0;


             
                        for($i = 1; $i <= 5; $i++)
                        {
                            $prc = "price".$i;
                            $pvp = "product_variant_".$prc;
                            $activePriceVariantVal="activePriceVariantVal".$i;
                            $basic_price="basic_price".$i;
                            $calculated_vat_price="calculated_vat_price".$i;
                            $currentVariantPrice = floatVal(str_replace(".", "", $request->$pvp[$key]));
                                if ($request->variant_discount_type[$key] == "ORAN") {
                                    $activePriceVariantVal = $request->vat_variant_status[$key] == 1 ? $currentVariantPrice-$currentVariantPrice*$this->floatVal(rtrim($request->variant_discount[$key], '%'))/100 : $currentVariantPrice * (1+$trimmed_vat_rate/100)-$currentVariantPrice * (1+$trimmed_vat_rate/100)*$this->floatVal(rtrim($request->variant_discount[$key], '%'))/100;
                                    if ($request->vat_variant_status[$key] == 1) {
                                        $productVariantPrice->$calculated_vat_price = $activePriceVariantVal-($activePriceVariantVal/(1+$trimmed_vat_rate/100)); 
                                    } else if($request->vat_variant_status[$key] == 0) {
                                        $productVariantPrice->$calculated_vat_price = $activePriceVariantVal - ($currentVariantPrice-$currentVariantPrice*$product_discount/100); 
                                    }
                                }else if($request->variant_discount_type[$key] == "TL"){
                                    $activePriceVariantVal = $request->vat_variant_status[$key] == 1 ? $currentVariantPrice-$this->floatVal(rtrim($request->variant_discount[$key], '%')): ($currentVariantPrice-$this->floatVal(rtrim($request->variant_discount[$key], '%'))) * (1+$trimmed_vat_rate/100);
                                    if ($request->vat_variant_status[$key] == 1) {
                                        $productVariantPrice->$calculated_vat_price = $activePriceVariantVal-($activePriceVariantVal/(1+$trimmed_vat_rate/100));
                                    } else if($request->vat_variant_status[$key] == 0) {
                                        $productVariantPrice->$calculated_vat_price = $activePriceVariantVal - ($currentVariantPrice-floatVal(rtrim($request->variant_discount[$key], '%')));
                                    }
                                }
                                $productVariantPrice->$prc = $activePriceVariantVal;
                                $productVariantPrice->$basic_price = $currentVariantPrice;
                            

                        }

                        $productVariantPrice->supplier_id = $request->persons_supplier ?? null;
                        $productVariantPrice->status = $status;
                        $productVariantPrice->vat_status = $request->vat_variant_status[$key];
                        $productVariantPrice->discount_type = $request->variant_discount_type[$key];
                        $productVariantPrice->discount = $request->variant_discount[$key];
                        $productVariantPrice->purchase_price = $this->floatVal(str_replace(".", "",$request->variant_purchase_price[$key]));
                        $productVariantPrice->deci = $request->variant_deci[$key];
                        $productVariantOptionId = VariantOptions::whereIn('name', explode("-" ,$request->variant_optionName[$key]))->get();

                        for ($i = 1; $i < count($productVariantOptionId); $i++)
                        {
                            $opt = "option".($i+1);
                            $productVariantPrice->$opt = $productVariantOptionId[$i]->id;
                        }

                        try {
                            $productVariantPrice->update();

                        }catch(\Exception $e)
                        {
                            DB::rollback();
                            return Response::json(array(
                                'status' => false,
                                'messages' => $e->getMessage(),
                                'reply' => __('messages.Error Adding to Product Value Table')
                            ));
                        }


                        $stockMovement = StockMovement::where('product_variant_price_id', $productVariantPrice->id)->where('type', 0)->first();
                        if (isset($stockMovement))
                        {
                            $stockMovement->amount = $request->variant_stock[$key];
                            $stockMovement->purchase_price = $this->floatVal($request->variant_purchase_price[$key]);
                            $stockMovement->vat = $trimmed_vat_rate;
                            $stockMovement->billing_no = $request->billing_no ?? null;
                            $stockMovement->desc = $request->desc ?? null;
                            $stockMovement->supplier_id = $request->persons_supplier ?? null;
                            try {
                                $stockMovement->update();

                            }catch(\Exception $e)
                            {
                                DB::rollback();
                                return Response::json(array(
                                    'status' => false,
                                    'messages' => $e->getMessage(),
                                    'reply' =>__('messages.Stock Movements Update Error')
                                ));
                            }

                        }
                    }
                }
                else
                {
                    $productVariantPrice = ProductVariantPrice::where('product_id', $id)->whereNull('option1')->first();
                    if (isset($productVariantPrice))
                    {
                        $trimmed_vat_rate_variant_price = rtrim($request->vat_rate, '%');
                        $product_discount_variant_price =  $this->floatVal(explode('%', $request->discount)[0]);
                        $productVariantPrice->name = $request->name;
                        $productVariantPrice->stock_code = $request->stock_code;
                        $productVariantPrice->vat_status = $request->vat_status;
                        $productVariantPrice->discount_type = $request->discount_type;
                        $productVariantPrice->discount = rtrim($request->discount, '%');
                      
                       

                        for($i = 1; $i <= 5; $i++)
                        {
                            $prc = "price".$i;
                            $productVariantPrice->$prc = $request->vat_status == 1 ?
                                $this->floatVal(str_replace(".", "",$request->$prc)) :
                                $this->floatVal(str_replace(".", "",$request->$prc)) * (1 + $trimmed_vat_rate / 100);
                        }

                        for ($n=1; $n <= 5 ; $n++) {
                            $prc="price".$n;
                            $activePriceVal="activePriceVal".$n;
                            $basic_price="basic_price".$n;
                            $calculated_vat_price="calculated_vat_price".$n;
                            $currentPrice = floatVal(str_replace(".", "", $request->$prc));
                                if ($request->discount_type == "ORAN") {
                                    $activePriceVal = $request->vat_status == 1 ? $currentPrice-$currentPrice*$product_discount/100 : $currentPrice * (1+$trimmed_vat_rate/100)-$currentPrice * (1+$trimmed_vat_rate/100)*$product_discount/100;
                                    if ($request->vat_status == 1) {
                                        $productVariantPrice->$calculated_vat_price = $activePriceVal-($activePriceVal/(1+$trimmed_vat_rate/100)); 
                                    } else if($request->vat_status == 0) {
                                        $productVariantPrice->$calculated_vat_price = $activePriceVal - ($currentPrice-$currentPrice*$product_discount/100); 
                                    }
                                }else if($request->discount_type == "TL"){
                                    $activePriceVal = $request->vat_status == 1 ?  $currentPrice-$product_discount: ($currentPrice-$product_discount) * (1+$trimmed_vat_rate/100);
                                    if ($request->vat_status == 1) {
                                        $productVariantPrice->$calculated_vat_price = $activePriceVal-($activePriceVal/(1+$trimmed_vat_rate/100));
                                    } else if($request->vat_status == 0) {
                                        $productVariantPrice->$calculated_vat_price = $activePriceVal - ($currentPrice-$product_discount);
                                    }
                                }
                                $productVariantPrice->$prc = $activePriceVal;
                                $productVariantPrice->$basic_price = $currentPrice;
                        }


                        $productVariantPrice->supplier_id = $request->persons_supplier ?? null;
                        $productVariantPrice->status = 1;
                        $productVariantPrice->deci = $request->cargo_weight;
                        $productVariantPrice->purchase_price = $this->floatVal(str_replace(".", "",$request->purchase_price));
                        try {

                            $productVariantPrice->update();
                        }catch(\Exception $e)
                        {
                            DB::rollback();
                            return Response::json(array(
                                'status' => false,
                                'messages' => $e->getMessage(),
                                'reply' =>__('messages.Error Adding to Product Value Table')
                            ));
                        }


                        $stockMovement = StockMovement::where('product_variant_price_id', $productVariantPrice->id)->where('type', 0)->first();

                        if (isset($stockMovement))
                        {
                            $stockMovement->amount = $request->stock;
                            $stockMovement->purchase_price = $this->floatVal($request->purchase_price);
                            $stockMovement->vat = $trimmed_vat_rate;
                            $stockMovement->billing_no = $request->billing_no ?? null;
                            $stockMovement->desc = $request->desc ?? null;
                            $stockMovement->supplier_id = $request->persons_supplier ?? null;
                            try {
                                $stockMovement->update();
                            }catch(\Exception $e)
                            {
                                DB::rollback();
                                return Response::json(array(
                                    'status' => false,
                                    'messages' => $e->getMessage(),
                                    'reply' => __('messages.Stock Movements Update Error')
                                ));
                            }

                        }
                    }
                }
            }

            // update tags

            $getFindTags=ProductTags::where('product_id',$request->id)->first();
            if(isset($getFindTags))
            {
                $getFindTags=ProductTags::where('product_id',$request->id)->delete();
                $getTags=$request->tag;
                if(isset($getTags)){
                    foreach ($getTags as $tag)
                    {
                        $issetTag=Tags::where('name',$tag)->first();
                        if(isset($issetTag))
                        {
                            $issetControl=ProductTags::where('product_id',$request->id)->where('tag_id',$issetTag->id)->first();
                            if(isset($issetControl))
                            {
                            }
                            else{
                                $newRelationshipTag = new ProductTags();
                                $newRelationshipTag->product_id =$request->id;
                                $newRelationshipTag->tag_id =$issetTag->id;
                                try {
                                    $newRelationshipTag->save();

                                }catch(\Exception $e)
                                {
                                    DB::rollback();
                                    return Response::json(array(
                                        'status' => false,
                                        'messages' => $e->getMessage(),
                                        'reply' => 'barcode'
                                    ));
                                }
                            }
                        }
                        else{
                            $newTags= new Tags();
                            $newTags->name = $tag;
                            $newTags->slug = Str::slug($tag);
                            try {
                                $newTags->save();

                            }catch(\Exception $e)
                            {
                                DB::rollback();
                                return Response::json(array(
                                    'status' => false,
                                    'messages' => $e->getMessage(),
                                    'reply' => __('messages.Tag Add Error')
                                ));
                            }

                            $issetControl=ProductTags::where('product_id',$request->id)->where('tag_id',$newTags->id)->first();
                            if(isset($issetControl))
                            {
                            }
                            else{
                                $newRelationshipTag = new ProductTags();
                                $newRelationshipTag->product_id =$request->id;
                                $newRelationshipTag->tag_id =$newTags->id;
                                try {
                                    $newRelationshipTag->save();

                                }catch(\Exception $e)
                                {
                                    DB::rollback();
                                    return Response::json(array(
                                        'status' => false,
                                        'messages' => $e->getMessage(),
                                        'reply' =>  __('messages.Error Assigning Label to Product')
                                    ));
                                }

                            }

                        }
                    }
                }
                // end save tags
            }
            else{
                $getTags=$request->tag;
                if(isset($getTags)) {
                    foreach ($getTags as $tag) {
                        $issetTag = Tags::where('name', $tag)->first();
                        if (isset($issetTag)) {
                            $issetControl = ProductTags::where('product_id', $request->id)->where('tag_id', $issetTag->id)->first();
                            if (isset($issetControl)) {
                            } else {
                                $newRelationshipTag = new ProductTags();
                                $newRelationshipTag->product_id = $request->id;
                                $newRelationshipTag->tag_id = $issetTag->id;
                                try {

                                    $newRelationshipTag->save();
                                }catch(\Exception $e)
                                {
                                    DB::rollback();
                                    return Response::json(array(
                                        'status' => false,
                                        'messages' => $e->getMessage(),
                                        'reply' =>  __('messages.Error Assigning Label to Product')
                                    ));
                                }

                            }
                        } else {
                            $newTags = new Tags();
                            $newTags->name = $tag;
                            $newTags->slug = Str::slug($tag);
                            try {
                                $newTags->save();

                            }catch(\Exception $e)
                            {
                                DB::rollback();
                                return Response::json(array(
                                    'status' => false,
                                    'messages' => $e->getMessage(),
                                    'reply' =>  __('messages.Tag Add Error')
                                ));
                            }

                            $issetControl = ProductTags::where('product_id', $request->id)->where('tag_id', $newTags->id)->first();
                            if (isset($issetControl)) {
                            } else {
                                $newRelationshipTag = new ProductTags();
                                $newRelationshipTag->product_id = $request->id;
                                $newRelationshipTag->tag_id = $newTags->id;
                                try {
                                    $newRelationshipTag->save();

                                }catch(\Exception $e)
                                {
                                    DB::rollback();
                                    return Response::json(array(
                                        'status' => false,
                                        'messages' => $e->getMessage(),
                                        'reply' => __('messages.Error Assigning Label to Product')
                                    ));
                                }
                            }
                        }
                    }
                }
            }
            // end update tags


            DB::commit();
            $success =  MyResponseClass::success(__('messages.SuccessMessages'));
            return response()->json($success);
        }

    }




    public function ProductStatus(Request $request)
    {
        $product = Product::find($request->id);

        if ($product->image == null)
        {
            return response()->json(['status' => false, 'message' => __("myTr.admin.The product cannot image")]);
        }

        $product->is_active = $request->is_active;

        if ($product->update()) {
            return response()->json(['status' => true, 'message' => __("myTr.admin.The Product status changed")]);
        } else {
            return response()->json(['status' => true, 'message' =>  __("myTr.admin.Error") ]);
        }
    }


    public function productShow($id)
    {
        $product = Product::findOrFail($id);
        $productPrices = ProductVariantPrice::where('product_id', $id)->whereNull('option1')->first();

        return view('admin.products.show', compact('product', 'productPrices'));
    }


    public function ProductDetail(Request $request, $id){

        $variantsList = Variant::all();
        $exist_image_variants = ProductImage::where('product_id', $id)->pluck('option_id');
        $exist_options = VariantOptions::findMany($exist_image_variants);
        $product = Product::where('id',$id)->first();
        $productExtra = ProductExtra::where('product_id',$id)->first();
        $getProductVariant  = ProductVariantPrice::where('product_id', $id)->first();



        $productVariantArray = explode(",", $getProductVariant->short_variant_ids);
        $getVariant=[];
        foreach($productVariantArray as $key=>$value){
            $getVariant[$key]=Variant::where('id', $value)->first();
        }

        return view('admin.products.detail', compact('product','variantsList','exist_options','getVariant','productExtra'));
    }

    public function setNecCardProduct(Request $request){

        $setNecCardProd = SettingNecessaryCardProduct::first();
        if (isset($request->setNecproperties)) {
            $setNecCardProd->properties_system = $request->setNecproperties;
        }

        if (isset($request->setNecBillInfo)) {
            $setNecCardProd->bill_info = $request->setNecBillInfo;
        }

        if (isset($request->setNecVarSystem)) {
            $setNecCardProd->variant_system = $request->setNecVarSystem;
        }

        if (isset($request->setNecWarPeriod)) {
            $setNecCardProd->warranty_period = $request->setNecWarPeriod;
        }

        if (isset($request->setNecSeoInfo)) {
            $setNecCardProd->seo_information = $request->setNecSeoInfo;
        }

        if (isset($request->setNecShowOrder)) {
            $setNecCardProd->showcase_order = $request->setNecShowOrder;
        }

        if (isset($request->setNecRecProder)) {
            $setNecCardProd->recommended_products = $request->setNecRecProder;
        }

        if (isset($request->setNecComProder)) {
            $setNecCardProd->combined_products = $request->setNecComProder;
        }


        if (isset($request->setNecSupSystem)) {
            $setNecCardProd->supplier_system = $request->setNecSupSystem;
        }

        if (isset($request->setNecTagSystem)) {
            $setNecCardProd->tag_system = $request->setNecTagSystem;
        }

        $setNecCardProd->update();

    }

    public function ProductVariantEdit(Request $request, $id){


        $productVariantPrices = ProductVariantPrice::find($id);
        $trimmed_vat_rate = rtrim($request->vat_rate, '%');
        $product_discount = floatVal(explode('%', $request->discount)[0]);
        

        for ($n=1; $n <= 5 ; $n++) {
            $prc="price".$n;
            $activePriceVal="activePriceVal".$n;
            $basic_price="basic_price".$n;
            $calculated_vat_price="calculated_vat_price".$n;
            $currentPrice = floatVal(str_replace(".", "",$request->$prc));
            if (isset($request->$prc) && ($request->$prc > 0)) {
                if ($request->discount_type == "ORAN") {
                    $activePriceVal = $request->vat_status == 1 ? $currentPrice-$currentPrice*$product_discount/100 : $currentPrice * (1+$trimmed_vat_rate/100)-$currentPrice * (1+$trimmed_vat_rate/100)*$product_discount/100;
                    if ($request->vat_status == 1) {
                        $productVariantPrices->$calculated_vat_price = $activePriceVal-($activePriceVal/(1+$trimmed_vat_rate/100)); 
                    } else if($request->vat_status == 0) {
                        $productVariantPrices->$calculated_vat_price = $activePriceVal - ($currentPrice-$currentPrice*$product_discount/100); 
                    }
                }else if($request->discount_type == "TL"){
                    $activePriceVal = $request->vat_status == 1 ?  $currentPrice - $product_discount: ($currentPrice-$product_discount) * (1+$trimmed_vat_rate/100);
                    if ($request->vat_status == 1) {
                        $productVariantPrices->$calculated_vat_price = $activePriceVal-($activePriceVal/(1+$trimmed_vat_rate/100));
                    } else if($request->vat_status == 0) {
                        $productVariantPrices->$calculated_vat_price = $activePriceVal - ($currentPrice-$product_discount);
                    }
                }
                $productVariantPrices->$prc = $activePriceVal;
                $productVariantPrices->$basic_price = $currentPrice;
            }
        }

        if ($productVariantPrices->save()) {
            return response()->json(__('myTr.admin.Update Product Variant Success'), 201);//(code)
        } else {
            return response()->json(__('myTr.admin.An Error Occurred'), 400); //(code)
        }

    }

    public function ProductVariantAction($id)
    {
        $suppliers = Supplier::where('is_status',1)->get();
        $variants= ProductVariantPrice::where('id', $id)->first();
        $product_name = Product::where('id',$variants->product_id)->first();
        $warehouses = Warehouse::all();

        return view('admin.products.productVariant.detail', compact('variants','product_name','suppliers','warehouses'));

    }

    
    public function ProductVariantActionEdit(Request $request,$id)
    {
        $stock_movement_get = StockMovement::find($id);
        $product_price = ProductVariantPrice::where('id',$stock_movement_get->product_variant_price_id)->first();
        if(isset($stock_movement_get)){
            $stock_movement_get->type = $request->type;
            $stock_movement_get->amount = $request->amount;
            $stock_movement_get->billing_no = $request->billing_no;
            $stock_movement_get->billing_date = $request->billing_date;
            $stock_movement_get->purchase_price = $request->purchase_price;
            $stock_movement_get->desc = $request->desc;
            if ($stock_movement_get->save()) {
                    //---------------------------varsa depo işlem yapacak
                    $stockedProduct = StockedProducts::where('stock_code',$product_price->stock_code)->first();
                    if (isset($stockedProduct)) {
                        $stockedProduct->amount = stock($stock_movement_get->product_variant_price_id);
                        $stockedProduct->update();
                    }
                    //-------------------------------------
                return response()->json(__('myTr.admin.Update Product Variant Action Success'), 201);
            } else {
                return response()->json(__('myTr.admin.An Error Occurred'), 400);
            }
        }
        else{
            return response()->json(__('myTr.admin.An Error Occurred'), 400);
        }
        return view('admin.products.productVariant.detail');

    }



    public function ProductVariantStockAdd(Request $request )
    {
        //ini_set('memory_limit', '-1'); //@sunucu işlemleri  için

        $stockMovement = new StockMovement();
        $stockMovement->product_variant_price_id = $request->pvp_id;
        $stockMovement->type = 1;
        $stockMovement->amount = $request->amount;
        $stockMovement->purchase_price = $request->purchase_price;
        $stockMovement->billing_no = $request->billing_no;
        $stockMovement->billing_date = $request->billing_date;
        $stockMovement->desc = $request->desc;
        $stockMovement->supplier_id = $request->persons_supplier;
        $stockMovement->save();

        $hasProductStocked = StockedProducts::where('stock_code',$request->stock_code)->first();

        if ($hasProductStocked != null) {
            $totalAmount = intval($request->amount_warehouse)+intval($request->amount);
            $hasProductStocked->amount = $totalAmount; 
            $hasProductStocked->update(); 
        }

        if ($stockMovement->save())
        {
            return response()->json(['status'=>true]);
        }
        else
        {
            return  MyResponseClass::error('{{ __("myTr.admin.Unable to Add Record") }}');
        }

    }

    public function getSuppliers()
    {
        $suppliers = Supplier::get();
        return response()->json($suppliers);
    }

    public function productSubCategories(Request $request)
    {
        $subCats = ProductCategory::where('top_category_id', $request->cat_id)->get();
        return response()->json($subCats);
    }

    public function floatVal($val)
    {
        $val = str_replace(",",".",$val);
        $val = preg_replace('/\.(?=.*\.)/', '', $val);

        return floatval($val);
    }

    public function addProductVariantFromDetail(Request $request, $id)
    {
        if (count(explode('-',$request->product_variant_name[0])) != count(explode(',',ProductVariantPrice::where('product_id',$id)->first()->short_variant_ids))) {
            return Response::json(array(
                'status' => false,
                'messages' => " {{__('myTr.admin.There are inputs that are not entered')}}",
            ));
        }
        $getProductItems = Product::where('id',$id)->first();
        $trimmed_vat_rate = rtrim($getProductItems->vat_rate, '%');
        foreach ($request->variant_option as $row)
        {
            foreach ($row as $item)
            {
                $option = VariantOptions::find($item);
                $variant_ids[] = $option->variant->id;
                $product_variant = ProductVariant::where('product_id', $getProductItems->id)->where('variant_id', $option->variant->id)->first();
                ProductVariantOption::firstOrCreate(['option_id' => $option->id], ['product_variant_id' => $product_variant->id]);
            }
        }

        foreach ($request->product_variant_name as $key => $value)
        {
            isset($request->product_variant_status[$key]) && $request->product_variant_status[$key] == true ? $status = 1 : $status = 0;


            $productVariantPrice = new ProductVariantPrice();
            $productVariantPrice->product_id = $id;
            $productVariantPrice->stock_code = variantCode($getProductItems->id, explode("-", $request->product_variant_name[$key]));
            $productVariantPrice->name = $request->product_variant_name[$key];
            $productVariantPrice->short_variant_ids = implode(',', $variant_ids);
            $productVariantPrice->discount_type = $request->variant_discount_type[$key];
            $product_discount = rtrim($request->variant_discount[$key], '%');
            $productVariantPrice->discount = $product_discount;

            for($i = 1; $i <= 5; $i++)
            {
                $prc = "price".$i;
                $pvp = "product_variant_".$prc;
                $activePriceVariantVal="activePriceVariantVal".$i;
                $basic_price="basic_price".$i;
                $calculated_vat_price="calculated_vat_price".$i;
                if (isset($request->$pvp[$key]) && ($request->$pvp[$key] > 0)) {
                    if ($request->variant_discount_type[$key] == "ORAN") {
                        $activePriceVariantVal = $request->vat_variant_status[$key] == 1 ? $this->floatVal($request->$pvp[$key])-$this->floatVal($request->$pvp[$key])*$this->floatVal(rtrim($request->variant_discount[$key], '%'))/100 : $this->floatVal($request->$pvp[$key]) * (1+$trimmed_vat_rate/100)-$this->floatVal($request->$pvp[$key]) * (1+$trimmed_vat_rate/100)*$this->floatVal(rtrim($request->variant_discount[$key], '%'))/100;
                        if ($request->vat_variant_status[$key] == 1) {
                            $productVariantPrice->$calculated_vat_price = $activePriceVariantVal-($activePriceVariantVal/(1+$trimmed_vat_rate/100)); 
                        } else if($request->vat_variant_status[$key] == 0) {
                            $productVariantPrice->$calculated_vat_price = $activePriceVariantVal - (floatVal($request->$pvp[$key])-floatVal($request->$pvp[$key])*$product_discount/100); 
                        }
                    }else if($request->variant_discount_type[$key] == "TL"){
                        $activePriceVariantVal = $request->vat_variant_status[$key] == 1 ? $this->floatVal($request->$pvp[$key])-$this->floatVal(rtrim($request->variant_discount[$key], '%')): $this->floatVal($request->$pvp[$key]) * (1+$trimmed_vat_rate/100)-$this->floatVal(rtrim($request->variant_discount[$key], '%'));
                        if ($request->vat_variant_status[$key] == 1) {
                            $productVariantPrice->$calculated_vat_price = $activePriceVariantVal-($activePriceVariantVal/(1+$trimmed_vat_rate/100));
                        } else if($request->vat_variant_status[$key] == 0) {
                            $productVariantPrice->$calculated_vat_price = $activePriceVariantVal - (floatVal($request->$pvp[$key])-floatVal(rtrim($request->variant_discount[$key], '%')));
                        }
                    }
                    $productVariantPrice->$prc = $activePriceVariantVal;
                    $productVariantPrice->$basic_price = floatVal($request->$pvp[$key]);
                }

               
            }


            $productVariantPrice->supplier_id = $request->persons_supplier ?? null;
            $productVariantPrice->status = $status;
            $productVariantPrice->vat_status = $request->vat_variant_status[$key];
            $productVariantPrice->purchase_price = $this->floatVal($request->variant_purchase_price[$key]);
            $productVariantPrice->deci = $request->variant_cargo_weight[$key];

            $productVariantOptionId = VariantOptions::whereIn('name', explode("-", $request->product_variant_name[$key]))->get();

            for ($i = 0; $i < count($productVariantOptionId); $i++) {
                $opt = "option" . ($i + 1);
                $productVariantPrice->$opt = $productVariantOptionId[$i]->id;
            }
            $productVariantPrice->save();

            if ($productVariantPrice->save())
            {
                $stockMovement = new StockMovement();
                $stockMovement->product_variant_price_id = $productVariantPrice->id;
                $stockMovement->type = 0;
                $stockMovement->amount = $request->product_variant_stock[$key] ?? null;
                $stockMovement->purchase_price = $this->floatVal($request->variant_purchase_price[$key]) ?? null;
                $stockMovement->vat = $trimmed_vat_rate;
                $stockMovement->billing_no = $request->billing_no ?? null;
                $stockMovement->billing_date = $request->billing_date ?? null;
                $stockMovement->desc = $request->desc ?? null;
                $stockMovement->supplier_id = $request->persons_supplier ?? null;
                $stockMovement->save();
            }
        }

        $image_variants = VariantOptions::whereHas('variant', function ($q){
            $q->where('slug', 'renk');
        })->get();

        foreach ($image_variants as $key => $row)
        {
            $name = "variant_images".($key+1);

            if (isset($request->$name))
            {
                foreach ($request->$name as $item)
                {
                    $imageName = time().'-'.rand(1,999999).'-'.Str::slug($getProductItems->name).'_variant_image'.'.'.$item->extension();
                    $productImage = new ProductImage();
                    $productImage->product_id = $getProductItems->id;
                    $productImage->image = $imageName;
                    $productImage->option_id = ($key+1);
                    try {
                        $productImage->save();
                        $img = \Image::make($item->path());
                        $img->save(storage_path('app/public/product/quality/'.$imageName));
                        $img = \Image::make($item->path());
                        $img->resize(120, 120);
                        $img->save(storage_path('app/public/product/thumb/'.$imageName));
                        $cardImage = \Image::make($item->path());
                        $cardImage->resize(450, 510);
                        $cardImage->save(storage_path('app/public/product/card/'.$imageName));
                        $zoomImg = \Image::make($item->path());
                        $zoomImg->resize(null,1400, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        $zoomImg->save(storage_path('app/public/product/zoom/'.$imageName));
                        $item->move(storage_path('app/public/product/'),$imageName);
                    }catch(\Exception $e){
                        DB::rollback();
                        return Response::json(array(
                            'status' => false,
                            'messages' => $e->getMessage(),
                            'reply' => __('messages.Error Adding Variant Image')
                        ));
                    }
                }
            }
        }


        return response()->json(__(' {{ __("myTr.admin.Variant Added") }} '), 201);
    }


    public function productVariantControl(Request $request)
    {
        $variantControl = ProductVariantPrice::where('product_id', $request->productIdControl)->whereNotNull('option1')->first();
        isset($variantControl) ? $control = 1 : $control = 0;
        return response()->json(['control' => $control], 200);
    }





    /////////////////////////////////////////////////////////////////////////////////ALL DELETE METODS////////////////////////





    //Products silme fonksiyonu
    public function ProductsDelete(Request $request, $id)
    {

        $products = Product::where('id',$id)->first();
        $productExtra = ProductExtra::where('product_id',$id)->first();
        $productCategoryCompareTable = ProductProductCategory::where('product_id', $id)->first();
        $productVariant= ProductVariant::where('product_id',$id)->get();
        $productVariantPrice = ProductVariantPrice::where('product_id',$id)->get();
        $productImage= ProductImage::where('product_id',$id)->get();
        $pvpids = $productVariantPrice->pluck('id');
        $stockm = StockMovement::whereIn('product_variant_price_id', $pvpids)->whereIn('type', [1, 2, 3])->get();
        $productWarehouse = StockedProducts::whereIn('stock_code',$productVariantPrice->pluck('stock_code'))->get();

        if (isset($stockm) && !$stockm->isEmpty())
        {
            return Response::json(array(
                'status' => false,
                'message' => __('myTr.admin.Product Passive Description') 
            ));
        }else{
             StockMovement::where('type', 0)->whereIn('product_variant_price_id', $pvpids)->delete();

        }




        DB::beginTransaction();

        if (isset($productVariant)) {
            foreach($productVariant as  $h => $item8){
                $productVariantOption = ProductVariantOption::where('product_variant_id',$productVariant[$h]->id)->get();
                foreach ($productVariantOption as $g => $item9) {
                    try {
                        $productVariantOption[$g]->delete();
                    }catch(\Exception $e)
                    {
                        DB::rollback();
                        return Response::json(array(
                            'status' => false,
                            'messages' => $e->getMessage(),
                            'reply' => __('messages.Product Variant Option Deletion Error')
                        ));
                    }

                }
                try {
                    $productVariant[$h]->delete();
                }catch(\Exception $e)
                {
                    DB::rollback();
                    return Response::json(array(
                        'status' => false,
                        'messages' => $e->getMessage(),
                        'reply' => __('messages.Product Variant Deletion Error')
                    ));
                }

            }
            if (isset($productVariantPrice)) {  
                foreach ($productVariantPrice as $s => $item10) {
                    try {
                        $productVariantPrice[$s]->delete();
                    }catch(\Exception $e)
                    {
                        DB::rollback();
                        return Response::json(array(
                            'status' => false,
                            'messages' => $e->getMessage(),
                            'reply' => __('messages.Product Deletion Error')
                        ));
                    }

                }
            }
        }


        if (isset($productCategoryCompareTable)) {
            try {
                $productCategoryCompareTable->delete();
            }catch(\Exception $e)
            {
                DB::rollback();
                return Response::json(array(
                    'status' => false,
                    'messages' => $e->getMessage(),
                    'reply' => __('messages.Product Category Deletion Error')
                ));
            }
        }

        if (isset($productImage)) {
            foreach ($productImage as $i => $item10) {
                try {
                    $productImage[$i]->delete();
                }catch(\Exception $e)
                {
                    DB::rollback();
                    return Response::json(array(
                        'status' => false,
                        'messages' => $e->getMessage(),
                        'reply' => __('messages.Product Image Deletion Error')
                    ));
                }
            }
        }

        if (isset($productExtra)) {
            try {
                $productExtra->delete();
            }catch(\Exception $e)
            {
                DB::rollback();
                return Response::json(array(
                    'status' => false,
                    'messages' => $e->getMessage(),
                    'reply' => __('messages.Product Detail Page Deletion Error')
                ));
            }
        }

        if (isset($productWarehouse)) {
            foreach ($productWarehouse as $r => $item20) {
                try {
                    $item20->delete();
                }catch(\Exception $e)
                {
                    DB::rollback();
                    return Response::json(array(
                        'status' => false,
                        'messages' => $e->getMessage(),
                        'reply' => __('messages.Product Warehouse Deletion Error')
                    ));
                }
            }
        }

        if (isset($products)) {
            try {
                $products->delete();
            }catch(\Exception $e)
            {
                DB::rollback();
                return Response::json(array(
                    'status' => false,
                    'messages' => $e->getMessage(),
                    'reply' => __('messages.Product Deletion Error')
                ));
            }

            DB::commit();
            return response()->json(['status' => true,'message' => __('messages.DeleteMessages')]);
        }
    }

    //Ürün vitrin mesajı silme fonksiyonu
    public function ProductsMainImageDelete(Request $request)
    {

        $id = $request->id;
        $product = Product::where('id',$id)->first();
        if (isset($product->image)) {
            $productImage = ProductImage::where('image',$product->image)->first();
            if (isset($productImage)) {
                $productImage->delete();
            }
        }

        $product->image = null;
        $product->update();

        if ($product->update()) {
            return Response::json(array(
                'status' => true,
                'messages' => 'update main image',
                'data' => ''
            ));
        }

    }



    public function ProductDeleteVariant(Request $request)
    {
        $id = $request->id;
        $productVariantPrice = ProductVariantPrice::where('id',$id)->first();

        if (isset($productVariantPrice))
        {
            $productVariantPrice->delete();
            return response()->json(['status' => true,'message' => __('messages.Variant deleted')]);
        }else{
            return response()->json(['status' => false,'message' =>__('messages.No variant found')]);
        }
    }


    public function propertyDelete(Request $request){

        $id =  $request->id;

        if (isset($id)) {
            $property = ProductProperty::where('id',$id)->first();

            if (isset($property)) {
                $property->delete();
                return Response::json(array(
                    'status' => true,
                    'messages' => __('messages.Success'),
                    'reply' => __('messages.Deletion successful')
                ));
            }else{
                return Response::json(array(
                    'status' => false,
                    'messages' => __('messages.ErrorAlert'),
                    'reply' => __('messages.No data to be deleted')
                ));
            }

        }else{
            return Response::json(array(
                'status' => false,
                'messages' => __('messages.ErrorAlert'),
                'reply' => __('messages.Missing parameter error')
            ));
        }


    }



    public function ProductVariantDelete($id)
    {

        $deleteVariantPrice= ProductVariantPrice::where('id',$id)->first();

        if (isset($deleteVariantPrice) && ($deleteVariantPrice->short_variant_ids!= null)) //$deleteVariantPrice->short_variant_ids sebebi eğer varyantı yoksa kendini silmesini engellemesi için
        {
            $deleteVariantPrice->forceDelete();
            return response()->json(['status' => true,'message' => __('messages.DeleteMessages')]);
        }
        else
        {
            return  MyResponseClass::error('',500);
        }
    }


    public function ProductVariantActionDelete($id)
    {
       
        $deleteStockMovement = StockMovement::where('id',$id)->first();
       
        if (isset($deleteStockMovement))
        {   $productvariantprice = ProductVariantPrice::where('id',$deleteStockMovement->product_variant_price_id)->first();
            if (isset($productvariantprice)) {
                $stock_prod = StockedProducts::where('stock_code',$productvariantprice->stock_code)->first();
                if ($deleteStockMovement->type == 1) {
                    $tempStock =  intval($stock_prod->amount) - intval($deleteStockMovement->amount);
                }else if($deleteStockMovement->type==2){
                    $tempStock =  intval($stock_prod->amount) + intval($deleteStockMovement->amount);
                }
                
                $stock_prod->amount = $tempStock;
                $stock_prod->update();
            }
            $deleteStockMovement->delete();
            return response()->json(['status' => true,'message' => __('messages.DeleteMessages')]);
        }
        else
        {
            return  MyResponseClass::error('',500);
        }
    }


}
