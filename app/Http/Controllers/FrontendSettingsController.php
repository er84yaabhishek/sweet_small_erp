<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\FeaturedProduct;
use App\Models\Testimonial;
use App\Models\DemoRequest;
use App\Models\Item;
use Illuminate\Http\Request;

class FrontendSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        $featuredProducts = FeaturedProduct::with('item')->orderBy('display_order')->get();
        $testimonials = Testimonial::orderBy('display_order')->get();
        $demoRequests = DemoRequest::orderByDesc('created_at')->paginate(10);
        $allItems = Item::where('is_active', true)->where('is_sellable', true)->get();
        return view('admin.frontend_settings', compact('settings', 'featuredProducts', 'testimonials', 'demoRequests', 'allItems'));
    }

    public function updateSettings(Request $request)
    {
        $settings = $request->except('_token');
        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        return back()->with('success', 'Settings updated!');
    }

    public function addFeaturedProduct(Request $request)
    {
        $request->validate(['item_id' => 'required|exists:items,id']);
        FeaturedProduct::create($request->all());
        return back()->with('success', 'Featured product added!');
    }

    public function removeFeaturedProduct($id)
    {
        FeaturedProduct::findOrFail($id)->delete();
        return back()->with('success', 'Removed!');
    }

    public function addTestimonial(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string',
            'message' => 'required|string',
            'rating' => 'integer|min:1|max:5',
        ]);
        Testimonial::create($request->all());
        return back()->with('success', 'Testimonial added!');
    }

    public function deleteTestimonial($id)
    {
        Testimonial::findOrFail($id)->delete();
        return back()->with('success', 'Deleted!');
    }

    public function updateDemoStatus(Request $request, $id)
    {
        $demo = DemoRequest::findOrFail($id);
        $demo->update(['status' => $request->status]);
        return back()->with('success', 'Status updated!');
    }
}
