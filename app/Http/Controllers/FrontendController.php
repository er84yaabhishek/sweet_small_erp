<?php

namespace App\Http\Controllers;

use App\Models\DemoRequest;
use App\Models\Setting;
use App\Models\FeaturedProduct;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        $featuredProducts = FeaturedProduct::with('item')->where('is_active', true)->orderBy('display_order')->get();
        $testimonials = Testimonial::where('is_active', true)->orderBy('display_order')->get();
        return view('frontend.index', compact('settings', 'featuredProducts', 'testimonials'));
    }

    public function submitDemo(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'phone' => 'required|string|max:15',
            'shop_name' => 'nullable|string|max:150',
            'message' => 'nullable|string',
        ]);
        DemoRequest::create($request->all());
        return back()->with('success', 'Demo request submitted successfully!');
    }
}
