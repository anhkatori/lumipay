<h1>Check amount of processing order</h1>
<div class="endpoint"><strong>Endpoint:</strong> /api/v1/order/status</div>
<div class="method"><strong>Method:</strong> POST</div>
<h2>Request Params</h2>
<div class="table-responsive"><table>
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
    </tbody>
</table></div>
<h2>Response Params</h2>
<div class="table-responsive"><table>
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
            <td>processing | error</td>
        </tr>
        <tr>
            <td>total</td>
            <td>String</td>
            <td>Total amount of the order</td>
        </tr>
    </tbody>
</table></div>