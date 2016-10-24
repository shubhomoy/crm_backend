<?php

namespace App\Http\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Closure;

class CustomerAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->header('accessToken') && $request->header('id')) {
            $accessToken = $request->header('accessToken');
            $id = $request->header('id');
            try{
                $customer = \App\Customer::with(['tokens' => function($q) use ($accessToken){
                    $q->where(\DB::raw('BINARY `token`'), $accessToken);
                }])->where('id', $id)->firstOrFail();
                $customer = json_decode($customer);
                if(sizeof($customer->tokens) == 0) {
                    return response()->json(['msg' => 'unauthorized', 'code' => '500']);
                }
            }catch(ModelNotFoundException $e) {
                return response()->json(['msg' => 'unauthorized', 'code' => '500']);
            }
        }else{
            return response()->json(['msg' => 'unauthorized', 'code' => '500']);
        }
        return $next($request);
    }
}
