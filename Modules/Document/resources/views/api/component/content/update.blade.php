<h1>Update status of order</h1>
<div class="endpoint"><strong>Endpoint:</strong> /api/v1/order/status/update</div>
<div class="method"><strong>Method:</strong> POST</div>
<h2>Request Params</h2>
<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>Property</th>
                <th>Required</th>
                <th>Type</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>id</td>
                <td>true</td>
                <td>string</td>
                <td>Unique identifier for the order</td>
            </tr>
            <tr>
                <td>signature</td>
                <td>true</td>
                <td>string</td>
                <td>Signature for authentication</td>
            </tr>
        </tbody>
    </table>
</div>
<h2>Signature generated</h2>
<div class="code-block">
    <pre>
function generateSignature($params = [], $secretKey)
{
    $signature = hash_hmac("sha256", base64_encode(json_encode($params)), $secretKey);
    return $signature;
}

$secretKey = SECRET_KEY;
$id = $order['id'];
$signature = generateSignature($id, $secretKey);
        </pre>
    <div class="copy-button">copy</div>
</div>