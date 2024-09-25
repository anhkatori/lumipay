<h1>Authentication</h1>
<p>Include the following parameters in the API header with each API call</p>
<div class="code-block">
    <pre>
{
    "Authorization": "client_id=PUBLIC_KEY&signature=SIGNATURE",
    "Content-Type": "application/json"
}
        </pre>
    <div class="copy-button">copy</div>
</div>
<h2>Signature generated</h2>
<div class="code-block">
    <pre>
function generateSignature($params = [], $secretKey)
{
    ksort($params);
    $signature = hash_hmac("sha256", base64_encode(json_encode($params)), $secretKey);
    return $signature;
}

$secretKey = SECRET_KEY;
//Request params of each API
$params = ["request_id" => "123456"];
$signature = generateSignature($params, $secretKey);
        </pre>
    <div class="copy-button">copy</div>
</div>