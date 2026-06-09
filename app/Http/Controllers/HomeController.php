<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\Cart;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $userEmail = $request->session()->get('user_email');
        $cartCount = 0;
        $user = null;
        
        if ($userEmail) {
            $user = User::where('email', $userEmail)->first();
            if ($user) {
                $cartCount = Cart::where('user_id', $user->id)->sum('quantity');
            }
        }
        
        $products = Product::all();
        
        // Passing the slide images dynamically as requested
        $slides = [
            'nooisefoto/slide1.png',
            'nooisefoto/slide2.png',
            'nooisefoto/slide3.png',
            'nooisefoto/slide4.png',
            'nooisefoto/slide5.png',
            'nooisefoto/slide6.png',
        ];

        return view('home', compact('cartCount', 'products', 'slides', 'user'));
    }
}
