<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests;
use App\Mail\EmailQuery;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
	// sview profile
    public function profile(Request $request) {
    	$admin = \App\Admin::find($request->header('id'));
    	return response()->json(['msg' => 'valid', 'code' => 1, 'data' => $admin]);
    }

    // show all customers
    public function showCustomers(Request $request) {
    	$customers = \App\Customer::with('contracts', 'emailQueries')->get();
    	return response()->json(['msg' => 'valid', 'code' => 1, 'data' => $customers]);
    }

    // View particular customer
    public function viewCustomer(Request $request, $id) {
    	try{
    		$customer = \App\Customer::with('contracts.admin', 'contracts.emailQueries')->where('id', $id)->firstOrFail();
    		return response()->json(['msg' => 'valid', 'code' => 1, 'data' => $customer]);
    	}catch(ModelNotFoundException $e) {
    		return response()->json(['msg' => 'not found', 'code' => 0]);
    	}
    }

    // show all contracts
    public function showContracts(Request $request) {
    	$contracts = \App\Contract::with('customer', 'admin', 'emailQueries')->get();
    	return response()->json(['msg' => 'valid', 'code' => 1, 'data' => $contracts]);
    }

    // view specific contract
    public function viewContract(Request $request, $id) {
    	try{
    		$contract = \App\Contract::with('customer', 'admin', 'emailQueries')->where('id', $id)->firstOrFail();
    		return response()->json(['msg' => 'found', 'code' => 1, 'data' => $contract]);
    	}catch(ModelNotFoundException $e) {
    		return response()->json(['msg' => 'no contract found', 'code' => 0]);
    	}
    }

    // assign the contract with this admin
    public function assign(Request $request, $id) {
    	try{
    		$contract = \App\Contract::with('customer')->where('id', $id)->firstOrFail();
    		$contract->a_id = $request->header('id');
    		$contract->status = 1;
    		$admin = \App\Admin::find($request->header('id'));
    		$contract->save();
    		$contract->admin = $admin;
    		return response()->json(['msg' => 'contract has been assigned', 'code' => '1', 'data' => $contract]);
    	}catch(ModelNotFoundException $e) {
    		return response()->json(['msg' => 'no contract found', 'code' => 0]);	
    	} 
    }

    // delete contract
    public function deleteContract(Request $request, $id) {
    	try{
    		$contract = \App\Contract::where('id', $id)->firstOrFail();
    		$contract->delete();
    		return response()->json(['msg' => 'contract deleted', 'code' => 1, 'data' => $contract]);
    	}catch(ModelNotFoundException $e) {
    		return response()->json(['msg' => 'contract not found', 'code' => '0']);
    	}
    }

    // Update contract
    public function updateContract(Request $request, $id) {
    	try{
    		$contract = \App\Contract::with('customer', 'admin')->where('id', $id)->firstOrFail();
    		$contract->status = $request->status;
    		$contract->title = $request->title;
    		$contract->description = $request->description;
    		$contract->expires_in = $request->expires_in;
    		$contract->save();
    		return response()->json(['msg' => 'contract updated', 'code' => 1, 'data' => $contract]);
    	}catch(ModelNotFoundException $e) {
    		return response()->json(['msg' => 'contract not found', 'code' => '0']);
    	}	
    }

    // View all email queries
    public function emailQueries(Request $request) {
    	$emailQueries = \App\EmailQuery::with('contract', 'customer')->get();
    	return response()->json(['msg' => 'valid', 'code' => 1, 'data' => $emailQueries]);
    }

    // send mail
    public function sendmail(Request $request) {
    	$to = $request->to;
    	$subject = $request->subject;
    	$body = $request->body;
    	$contract_id = $request->contract_id;
    	try{
    		$admin = \App\Admin::find($request->header('id'));
    		Mail::to($to)->send(new EmailQuery($admin, $body, $subject));
    		\App\EmailQuery::where('contract_id', $contract_id)->delete();
    		return response()->json(['msg' => 'sent', 'code' => 1]);
    	}catch(ModelNotFoundException $e) {
    		return response()->json(['msg' => 'not found', 'code' => '0']);
    	}
    }

    // create mail list
    public function createMailList(Request $request) {
    	$name = $request->name;
    	$mailList = new \App\MailList(['name' => $name]);
    	$mailList->save();
    	$customers = $request->selected;
    	foreach($customers as $customer) {
    		$mailList->customers()->attach($customer);
    	}
    	$mailList->customers;
    	return response()->json(['msg' => 'mail list created', 'code' => 1, 'data' => $mailList]);
    }

    // get all maillists
    public function getMailLists(Request $request) {
    	$mailLists = \App\MailList::with('customers')->get();
    	return response()->json(['msg' => 'valid', 'code' => 1, 'data' => $mailLists]);
    }

    // view a maillist
    public function viewMailList(Request $request, $id) {
    	try{
    		$mailList = \App\MailList::with('customers')->where('id', $id)->firstOrFail();
    		return response()->json(['msg' => 'valid', 'code' => 1, 'data' => $mailList]);
    	}catch(ModelNotFoundException $e){
    		return response()->json(['msg' => 'no mail list found', 'code' => 0]);
    	}
    }

    public function sendMailToMaillist(Request $request) {
    	try{
    		$admin = \App\Admin::find($request->header('id'));
    		$mailList = \App\MailList::with('customers')->where('id', $request->id)->firstOrFail();
    		foreach ($mailList->customers as $customer) {
    			Mail::to($customer->email)->send(new EmailQuery($admin, $request->body, $request->subject));
    		}
    		return response()->json(['msg' => 'mail sent', 'code' => 1]);
    	}catch(ModelNotFoundException $e) {
    		return response()->json(['msg' => 'mail list not found', 'code' => 0]);
    	}
    }

    public function deleteMaillist(Request $request, $id) {
    	try{
    		$mailList = \App\MailList::with('customers')->where('id', $id)->delete();
    		return response()->json(['msg' => 'mail list deleted', 'coed' => 1]);
    	}catch(ModelNotFoundException $e) {
    		return response()->json(['msg' => 'mail list not found', 'code' => 0]);	
    	}
    }
}
