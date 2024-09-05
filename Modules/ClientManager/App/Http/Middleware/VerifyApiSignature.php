<?php

namespace Modules\ClientManager\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use \Modules\ClientManager\App\Models\Client;

class VerifyApiSignature  
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $authorizationHeader = $request->header('Authorization');
        
        if (!$authorizationHeader || !preg_match('/client_id=(.*?)&signature=(.*)/', $authorizationHeader, $matches)) {
            return response()->json(['error' => 'Invalid Authorization Header'], 400);
        }
        
        $clientId = $matches[1];
        $signature = $matches[2];
        $params = $request->all();
        $client = Client::where('merchant_id', $clientId)->first();
        
        if(!$client || !$this->isValidSignature($client, $params, $signature)){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
    
    protected function isValidSignature($client, $params, $signature)
    {
        return hash_equals($signature, $this->generateSignature($client->public_key, $params));
    }

    private function generateSignature($secretKey, $params = [])
    {
        ksort($params);
        $signature = hash_hmac("sha256", base64_encode(json_encode($params)), $secretKey);

        return $signature;
    }

}
