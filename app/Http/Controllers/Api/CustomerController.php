<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
 

    public function index(){

        $data=Customer::all() ;
        return response()->json([
            'status' => 'Customer Data Fetched Successfully' ,
            'data' => $data
        ],200);
    }

    public function store(Request $request){

        $request->validate([
            'name' => 'required|string|min:3' ,
            'country' => 'required|string' ,
            'gender' => 'required|string' ,
            'image' =>'required|file|max:2048' ,
            'payment' =>'required|array'
        ]);

        $path=$request->file('image')->store('picture','public') ;

        $customer_data=Customer::create([
            'name' => $request->name,
            'country' =>$request->country ,
            'gender' =>$request->gender ,
            'image' => $path ,
            'payment' => $request->payment

        ]);
        return response()->json([
            'status' =>'Customer created successfully.',
            'data' =>$customer_data 
        ] , 201);

    }
}
