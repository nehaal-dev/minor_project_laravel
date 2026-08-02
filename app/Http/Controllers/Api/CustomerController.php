<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
 

    public function index(){

        // $data=Customer::paginate(5);

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Customer Data Fetched Successfully' ,
        //     'data' => $data
        // ],200); for simple data send  without using resource 
        

        return response()->json([
            'success' => true,
            'message' => 'Customer Data Fetched Successfully' ,
            'data' => CustomerResource::collection(Customer::paginate(5)) 
        ],200);
    }

    public function store(Request $request){

       $validator=validator($request->all(),[
            'name' => 'required|string|min:3' ,
            'country' => 'required|string' ,
            'gender' => 'required|string' ,
            'image' =>'required|file|max:2048' ,
            'payment' =>'required|array'
        ]);


        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Validation Failed' ,
                'errors' => $validator->errors()

            ],422);
        };


        $path=$request->file('image')->store('picture','public') ;

        $customer_data=Customer::create([
            'name' => $request->name,
            'country' =>$request->country ,
            'gender' =>$request->gender ,
            'image' => $path ,
            'payment' => $request->payment

        ]);
        // return response()->json([
        //     'success'=>true,
        //     'message' =>'Customer created successfully.',
        //     'data' =>$customer_data 
        // ] , 201);  for simple data send  without using resource 
        return response()->json([
            'success' => true ,
            'message' => 'Customer Data Created' ,
            'data' => new CustomerResource($customer_data)

        ],201);  // for using resources 
 
    }
}
