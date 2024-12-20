<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\products;
use App\Models\orders;
use App\Models\products_details;

class dashboardController extends Controller
{
    public function index(){
    return view('dashboard.index');
    }

    public function products(){
        // return view('dashboard.products');
        $product=products::all(); // Like : select * from tableName;
        return view( 'dashboard.products',['product'=>$product]);
    }

    public function create_products(Request $request){
         //Validation 
         $request->validate([
            'productname'=>['required'],
            'productdesc'=>['required'],

        ]);

        $product=products::create([
            'name'=> $request->productname,
            'description'=> $request->productdesc,
        ]);

        $product->save();
        return redirect()->route('products');
    }

    public function display_products(){
        $product=products::all(); // Like : select * from tableName;
        return view( 'dashboard.products',['product'=>$product]);
    }

    public function delete(){
        $id=$_GET['id'];
        $product=products::find($id); // find method used for search, will take all record asso with id
        $product->delete();
        // return redirect()->route('products');
    }

        //Update, First step, show selected record 
        public function edit($id){
            $product=products::find($id);
            return view('dashboard.edit',['product'=>$product]);
        }
    
        //Update, Second step
        public function update(Request $request){
            products::where('id',$request->id) //where like where in sql: WHERE id == $request->id
            ->update(['name'=> $request->name,'description'=> $request->description]);
            return redirect()->route('products');
        }



        public function add_product_details(Request $request){
            $image_name = $request->file('img')->getClientOriginalName();
            $request->file('img')->storeAs('books_images/', $image_name);

            //Validation 
            $request->validate([
                'id'=>['required'],
                'productID'=>['required'],
                'price'=>['required'],
                'gty'=>['required'],
                'img'=>['required'],
                'desc'=>['required'],
                'desctwo'=>['required'],

            ]);
            
            $product=products_details::create([
                'id'=>$request->id,
                'id_product'=> $request->productID,
                'price'=> $request->price,
                'gty'=>$request->gty,
                'image'=> $image_name,
                'description'=> $request->desc,
                'desc'=> $request->desctwo,
                
            ]);
            $product->save();
            return redirect()->route('details');

        }


        public function Products_details(){
            $product=products::all();   
            $producdetails=DB::table('products')
            ->join('products_details','products.id','=','products_details.id_product')
            ->get();
            return view('dashboard.product_details',['product'=>$product],['producdetails'=>$producdetails]);

        }

        public function delete_product_details(){
            $id=$_GET['id'];
            $product=products_details::find($id);
            $product->delete();
        }

        
        //Update, First step, show selected record 
        public function edit_details($id){
            $productx=products_details::find($id);
            // dd($productx);
            return view('dashboard.edit_details',['productx'=>$productx]);
        }
    
        //Update, Second step
        public function update_details(Request $request){  
            products_details::where('id','=',$request->id)
            ->update(['price'=> $request->price, 'gty'=> $request->gty, 'description'=> $request->desc,'desc'=>$request->desctwo]);
            return redirect()->route('details');
        }

        public function orders(){

            // $orders = orders::with('customer_id') 
            // ->get()
            // ->groupBy('customer_id');


            $orders = DB::table("orders")
            ->join('products_details', 'orders.product_id', '=', 'products_details.id')
            ->join('products', 'products_details.id_product', '=', 'products.id')
            ->join('Users', 'orders.customer_id', '=', 'Users.id')
            ->select('orders.*','Users.email', 'products_details.price', 'products.name') 
            ->get();
            // dd($orders);    

            return view('dashboard.order', ['orders'=>$orders]);
            

        }

        public function bills(){
            $bills = DB::table('invoices')->get();
            
        //     $invoices=[];

        //     foreach($bills as $counter=>$bill){
        //         if(!in_array($bill->customer_id, $invoices)) { 
        //             $invoices[$counter] = $bill->customer_id;
            
        // }}

        // $bi = DB::table('invoices')->
        // where('customer_id','=','')->get();
        //     dd($bi);  

             return view('dashboard.bills',['invoice'=>$bills]);

        }



        
}
