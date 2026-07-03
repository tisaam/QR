<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::where('business_id', Auth::user()->business->id)
            ->orderBy('last_visit', 'desc')
            ->paginate(15);

        return view('customers.index', compact('customers'));
    }

    public function show(Customer $customer)
    {
        if ($customer->business_id !== Auth::user()->business->id) abort(403);
        
        $reviews = $customer->reviews()->latest()->get(); // Assuming Customer hasMany Reviews
        
        return view('customers.show', compact('customer', 'reviews'));
    }
}