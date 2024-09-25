<h1>Create Order</h1>
<div class="endpoint"><strong>Endpoint:</strong> /api/v1/order</div>
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
                <td>request_id</td>
                <td>true</td>
                <td>string</td>
                <td>Unique identifier for the payment request</td>
            </tr>
            <tr>
                <td>amount</td>
                <td>true</td>
                <td>string</td>
                <td>The amount of the payment</td>
            </tr>
            <tr>
                <td>email</td>
                <td>true</td>
                <td>string</td>
                <td>The customer's email address</td>
            </tr>
            <tr>
                <td>description</td>
                <td>true</td>
                <td>string</td>
                <td>A brief description of the payment</td>
            </tr>
            <tr>
                <td>cancel_url</td>
                <td>true</td>
                <td>string</td>
                <td>The URL to redirect to when the payment is cancelled</td>
            </tr>
            <tr>
                <td>return_url</td>
                <td>true</td>
                <td>string</td>
                <td>The URL to redirect to when the payment is successful</td>
            </tr>
            <tr>
                <td>notify_url</td>
                <td>true</td>
                <td>string</td>
                <td>The URL to send IPN to</td>
            </tr>
            <tr>
                <td>ip</td>
                <td>true</td>
                <td>string</td>
                <td>The customer's IP address</td>
            </tr>
            <tr>
                <td>method</td>
                <td>true</td>
                <td>string</td>
                <td>PAYPAL | CREDIT_CARD | CREDIT_CARD_2</td>
            </tr>
        </tbody>
    </table>
</div>
<h2>Response Params</h2>
<div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>Property</th>
                <th>Type</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>status</td>
                <td>String</td>
                <td>success | error</td>
            </tr>
            <tr>
                <td>payment_url</td>
                <td>String</td>
                <td>Redirect from merchant to payment link</td>
            </tr>
            <tr>
                <td>message</td>
                <td>String</td>
                <td>Error message if status is error</td>
            </tr>
        </tbody>
    </table>
</div>