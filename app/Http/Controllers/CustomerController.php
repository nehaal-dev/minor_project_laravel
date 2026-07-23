<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{


    public function index()
    {

        $customer = Customer::paginate(5);
        return view('customers.index', compact('customer'));
    }


    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'gender' => 'required|string|max:150',
            'payment' => 'required|array|max:250',
            'country' => 'required|string|max:255',
            'image' => 'required|image|file|max:2048'
        ]);

        $path = $request->file('image')->store('picture', 'public');

        Customer::create([
            'name' => $request->name,
            'gender' => $request->gender,
            'payment' => $request->payment,
            'country' => $request->country,

            'image' => $path

        ]);

        return redirect()->route('customers.index')->with('success', 'Data stored successfully');
    }

    public function show(Customer $customer)
    {

        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Customer $customer, Request $request)
    {

        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'gender' => 'required|string|max:150',
            'payment' => 'required|array|max:250',
            'country' => 'required|string|max:255',
            'image' => 'image|file|max:2048'
        ]);

        $data = [
            'name' => $request->name,
            'gender' => $request->gender,
            'payment' => $request->payment,
            'country' => $request->country,

        ];

        if ($request->hasFile('image')) {

            Storage::disk('public')->delete($customer->image);
            $path = $request->file('image')->store('picture', 'public');
            $data['image'] = $path;
        }


        $customer->update($data);

        return redirect()->route('customers.index')->with('success', 'Updated Successfull');
    }


    public function destroy(Customer $customer)
    {

        Storage::disk('public')->delete($customer->image);

        $customer->delete();
        return redirect()->route('customers.index')->with('sucess', 'Customer Data Deleted Successfully');
    }

    public function restore($id)
    {
        $customer = Customer::onlyTrashed()->findOrFail($id);
        $customer->restore();

        return redirect()->route('customers.index')->with('success', 'data restored');
    }

    public function trashed()
    {
        $deletedCustomers = Customer::onlyTrashed()->get();
        return view('customers.trashed', compact('deletedCustomers'));
    }


    public function search(Request $request)
    {

        $search_data = trim($request->search);
        if ($search_data !== '') {
            //dd($request->search);
                 
            $paymentMap = [
                'cash' => 'Cash',
                'card' => 'Card',
                'upi'  => 'UPI',
                'cheque' =>'Cheque'
            ];
            
            $payment = $paymentMap[strtolower($search_data)] ?? $search_data;
            $customer = Customer::where('id', 'LIKE', '%' . $search_data . '%')
                ->orWhere('name', 'LIKE', '%' . $search_data . '%')
                ->orWhere('gender', 'LIKE', '%' . $search_data . '%')
                ->orWhereJsonContains('payment', $payment)
                ->orWhere('country', 'LIKE', '%' . $search_data . '%')
                //->get()
                ->paginate(5);  
                // dd($customer->count());          
            return view('customers.index', compact('customer'));
        } else {
            return redirect()->route('customers.index')
            ->with('error','please enter search input');
        }
    }
}
