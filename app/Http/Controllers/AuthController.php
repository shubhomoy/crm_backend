<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use App\Http\Requests;

class AuthController extends Controller
{
	// access token generator method
	public function generateAccessToken() {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < 32; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}

	// admin signup method
    public function adminSignup(Request $request) {
    	$name = $request->input('name');
    	$email = $request->input('email');
    	try{
    		$admin = \App\Admin::where('email', $email)->firstOrFail();
    		return response()->json(['msg' => 'already exists', 'code' =>  '2']);
    	}catch(ModelNotFoundException $e) {
    		$admin = new \App\Admin;
    		$admin->name = $name;
    		$admin->email = $email;
    		$admin->password = Hash::make($request->input('password'));
    		$admin->save();
    		return response()->json(['msg' => 'success', 'code' => '1', 'data' => $admin]);	// code 1 indicates a new admin has been made
    	}
    }

    // admin login method
    public function adminSignin(Request $request) {
    	$email = $request->email;
    	$password = $request->password;
    	try{
    		$admin = \App\Admin::where('email', $email)->firstOrFail();
    		if(Hash::check($password, $admin->password)) {
    			// generate an access token
    			$randomString = $this->generateAccessToken();
				$accessToken = new \App\AdminToken(['token' => $randomString]);
				$admin->tokens()->save($accessToken);
				$admin->token = $randomString;
				return response()->json(['msg' => 'logged in', 'code' => '1', 'data' => $admin]);
    		}else{
    			return response()->json(['msg' => 'invalid', 'code' => '0']);
    		}
    	}catch(ModelNotFoundException $e) {
    		return response()->json(['msg' => 'invalid', 'code' => '0']);
    	}
    } 


    // customer signup method
    public function customerSignup(Request $request) {
    	$name = $request->input('name');
    	$email = $request->input('email');
    	try{
    		$customer = \App\Customer::where('email', $email)->firstOrFail();
    		return response()->json(['msg' => 'already exists', 'code' =>  '2']);
    	}catch(ModelNotFoundException $e) {
    		$customer = new \App\Customer;
    		$customer->name = $name;
    		$customer->email = $email;
    		$customer->password = Hash::make($request->input('password'));
    		$customer->save();
    		return response()->json(['msg' => 'success', 'code' => '1', 'data' => $customer]);	// code 1 indicates a new admin has been made
    	}
    }


    // customer login method
    public function customerSignin(Request $request) {
    	$email = $request->email;
    	$password = $request->password;
    	try{
    		$customer = \App\Customer::where('email', $email)->firstOrFail();
    		if(Hash::check($password, $customer->password)) {
    			// generate an access token
    			$randomString = $this->generateAccessToken();
				$accessToken = new \App\CustomerToken(['token' => $randomString]);
				$customer->tokens()->save($accessToken);
				$customer->token = $randomString;
				return response()->json(['msg' => 'logged in', 'code' => '1', 'data' => $customer]);
    		}else{
    			return response()->json(['msg' => 'invalid', 'code' => '0']);
    		}
    	}catch(ModelNotFoundException $e) {
    		return response()->json(['msg' => 'invalid', 'code' => '0']);
    	}
    } 
}
