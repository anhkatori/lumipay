<?php

namespace Modules\PayPalManager\Helper;

class Data
{
    protected static $baseUrl = 'https://api-m.sandbox.paypal.com';
    public static function getAccessToken($client_key, $secret_key)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => static::$baseUrl ."/v1/oauth2/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_USERPWD => $client_key.":".$secret_key,
            CURLOPT_POSTFIELDS => "grant_type=client_credentials",
            CURLOPT_HTTPHEADER => [
                "Accept: application/json",
                "Accept-Language: en_US"
            ],
        ]);

        $result= curl_exec($curl);
        $result=json_decode($result, true); 
        $token= isset($result['access_token']) ? $result['access_token']: '';
        return $token;
    }

    public static function createWebhook($token)
    {
        $curl = curl_init(static::$baseUrl."/v1/notifications/webhooks");
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer " . $token
        ]);
        $result = curl_exec($curl);
        $result = json_decode($result, true);
        $canCreateWebhooks = true;
        foreach ($result['webhooks'] as $webhook) {
            if (!$canCreateWebhooks) {
                break;
            }
            if ($webhook['url'] == route('paypal.webhook')) {
                foreach ($webhook['event_types'] as $event_type) {
                    if ($event_type['name'] == "INVOICING.INVOICE.PAID") {
                        $canCreateWebhooks = false;
                        break;
                    }
                }
            }                    
        }
        if ($canCreateWebhooks) {
            $curl = curl_init(static::$baseUrl."/v1/notifications/webhooks");
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode([
                'url' => route('paypal.webhook'),
                'event_types' => [
                    [
                        'name' => 'INVOICING.INVOICE.PAID'
                    ]
                ]
            ]));
            curl_setopt($curl, CURLOPT_HTTPHEADER, [
                "Content-Type: application/json",
                "Authorization: Bearer " . $token
            ]);
            $result = curl_exec($curl);
        }
    }

    public static function verifyWebhook($args, $token){
        $curl = curl_init(static::$baseUrl."/v1/notifications/verify-webhook-signature");
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($args));
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer " . $token
        ]);

        $result = curl_exec($curl);
        $result=json_decode($result, true); 
    
        
        
        if (isset($result['verification_status'] ) && $result['verification_status'] == 'SUCCESS') {
            return true;
        }
        
        return false;
    
    }

    public static function createAndSendInvoice($data, $token)
    {
        $curl = curl_init(static::$baseUrl."/v2/invoicing/invoices");
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer " . $token
        ]);

        $result = curl_exec($curl);
        $result=json_decode($result, true); 
        $curl = curl_init($result['href'].'/send');
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode([
            "subject"=> "<The subject of the email that is sent as a notification to the recipient.>",
            "note" => "<A note to the payer.>",
            'send_to_recipient' => true
        ]));
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer " . $token
        ]);
        $result = curl_exec($curl);
        $result=json_decode($result, true); 
        return $result['href'];
    }
}