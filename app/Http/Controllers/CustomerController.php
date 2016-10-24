<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests;

class CustomerController extends Controller
{
	// View profile
    public function profile(Request $request) {
    	$customer = \App\Customer::find($request->header('id'));
    	return response()->json(['msg' => 'valid', 'code' => 1, 'data' => $customer]);
    }	

    // Create a new contract
    public function createContract(Request $request) {
    	$contract = new \App\Contract(['description' => $request->description, 'title' => $request->title]);
    	$customer = \App\Customer::find($request->header('id'));
    	$customer->contracts()->save($contract);
    	return response()->json(['msg' => 'contract added', 'code' => 1, 'data' => $contract]);
    } 

    // show all contracts created by this customer
    public function showContracts(Request $request) {
    	$customer = \App\Customer::with('contracts.admin')->find($request->header('id'));
    	return response()->json(['msg' => 'valid', 'code' => 1, 'data' => $customer->contracts]);
    }

    // view specific contract created by this customer
    public function viewContract(Request $request, $id) {
    	try{
    		$contract = \App\Contract::with('admin', 'customer', 'emailQueries')->where('c_id', $request->header('id'))->where('id', $id)->firstOrFail();
    		return response()->json(['msg' => 'found', 'code' => 1, 'data' => $contract]);
    	}catch(ModelNotFoundException $e) {
    		return response()->json(['msg' => 'no contract found', 'code' => 0]);
    	}
    }

    // create an email query
    public function emailQuery(Request $request, $id) {
    	try{
    		$contract = \App\Contract::with('admin')->where('c_id', $request->header('id'))->where('id', $id)->firstOrFail();
    		$emailQuery = new \App\EmailQuery(['remarks' => $request->remarks, 'contract_id' => $id]);
    		$customer = \App\Customer::find($request->header('id'));
    		$customer->emailqueries()->save($emailQuery);
    		return response()->json(['msg' => 'email query sent', 'code' => 1, 'data' => $emailQuery]);
    	}catch(ModelNotFoundException $e) {
    		return response()->json(['msg' => 'no contract found', 'code' => 0]);
    	}
    }
}
