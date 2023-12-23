<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Order;
use Session;
use Stripe;


/*class ItemsController extends Controller
{
    public function index()
    {
       // return view('items');
       $items = Item::all();
        return view('items', compact('items'));
    }

    public function cart()
    {
        return view('cart');
    }
    public function addToCart($id)
    {
        $item = Item::find($id);
        if(!$item) {
            abort(404);
        }
        $cart = session()->get('cart');
        // if cart is empty then this the first item
        if(!$cart) {
            $cart = [
                    $id => [
                        "name" => $item->name,
                        "quantity" => 1,
                        "price" => $item->price,
                        "photo" => $item->photo
                    ]
            ];
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Item added to cart successfully!');
        }
        // if cart not empty then check if this item exist then increment quantity
        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Item added to cart successfully!');
        }
        // if item not exist in cart then add to cart with quantity = 1
        $cart[$id] = [
            "name" => $item->name,
            "quantity" => 1,
            "price" => $item->price,
            "photo" => $item->photo
        ];
        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'item added to cart successfully!');

    }
    public function update(Request $request)
    {
        if($request->id and $request->quantity)
        {
            $cart = session()->get('cart');

            $cart[$request->id]["quantity"] = $request->quantity;

            session()->put('cart', $cart);

            session()->flash('success', 'Cart updated successfully');
        }
    }

    public function remove(Request $request)
    {
        if($request->id) {

            $cart = session()->get('cart');

            if(isset($cart[$request->id])) {

                unset($cart[$request->id]);

                session()->put('cart', $cart);
            }

            session()->flash('success', 'Item removed successfully');
        }
    }
}*/
class ItemsController extends Controller
{
    public function index()
    {
        $items = Item::all();

        return view('items', compact('items'));
    }
    
    public function cart()
    {
        return view('cart');
    }
    public function addToCart($id)
    {
        $item = Item::find($id);

        if(!$item) {

            abort(404);

        }

        $cart = session()->get('cart');

        // if cart is empty then this the first product
        if(!$cart) {

            $cart = [
                $id => [
                    "name" => $item->name,
                    "quantity" => 1,
                    "price" => $item->price,
                    "photo" => $item->photo
                ]
            ];

            session()->put('cart', $cart);

            $htmlCart = view('_header_cart')->render();

            return response()->json(['msg' => 'Item added to cart successfully!', 'data' => $htmlCart]);

            //return redirect()->back()->with('success', 'Product added to cart successfully!');
        }

        // if cart not empty then check if this product exist then increment quantity
        if(isset($cart[$id])) {

            $cart[$id]['quantity']++;

            session()->put('cart', $cart);

            $htmlCart = view('_header_cart')->render();

            return response()->json(['msg' => 'Item added to cart successfully!', 'data' => $htmlCart]);

            //return redirect()->back()->with('success', 'Product added to cart successfully!');

        }

        // if item not exist in cart then add to cart with quantity = 1
        $cart[$id] = [
            "name" => $item->name,
            "quantity" => 1,
            "price" => $item->price,
            "photo" => $item->photo
        ];

        session()->put('cart', $cart);

        $htmlCart = view('_header_cart')->render();

        return response()->json(['msg' => 'Item added to cart successfully!', 'data' => $htmlCart]);

        //return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request)
    {
        if($request->id and $request->quantity)
        {
            $cart = session()->get('cart');

            $cart[$request->id]["quantity"] = $request->quantity;

            session()->put('cart', $cart);

            $subTotal = $cart[$request->id]['quantity'] * $cart[$request->id]['price'];

            $total = $this->getCartTotal();

            $htmlCart = view('_header_cart')->render();

            return response()->json(['msg' => 'Cart updated successfully', 'data' => $htmlCart, 'total' => $total, 'subTotal' => $subTotal]);

            //session()->flash('success', 'Cart updated successfully');
        }
    }

    public function remove(Request $request)
    {
        if($request->id) {

            $cart = session()->get('cart');

            if(isset($cart[$request->id])) {

                unset($cart[$request->id]);

                session()->put('cart', $cart);
            }

            $total = $this->getCartTotal();

            $htmlCart = view('_header_cart')->render();

            return response()->json(['msg' => 'Item removed successfully', 'data' => $htmlCart, 'total' => $total]);

            //session()->flash('success', 'Product removed successfully');
        }
    }


    /**
     * getCartTotal
     *
     *
     * @return float|int
     */
    private function getCartTotal()
    {
        $total = 0;

        $cart = session()->get('cart');

        foreach($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        return number_format($total, 2);
    }

    public function stripe($total)
    {


        return view('stripe',compact('total'));
    }

    public function stripePost(Request $request, $total)
    {
        
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    
        Stripe\Charge::create ([
                "amount" => $total * 100,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "Thanks for your payment!" 
        ]);

         $data=Item::get();
        foreach($data as $data)
         {
             $order=new order;
             $order->name=$data->name;
             $order->photo=$data->photo;
             $order->price=$data->price;
             $order->payment_status="Success";

             $order->save();

        //     $item_id=$data->id;
        //     $item=item::find('item_id');
        //     $item->delete();

            // $itemId = $itemdata->id;
            // $item = Item::find($itemId);

            // if ($item) {
            //     $item->delete();
         }
    
        //Session::flash('success', 'Payment successful!');
       session()->flash('success', 'Payment successful!');

              
        return back();
    }
}

