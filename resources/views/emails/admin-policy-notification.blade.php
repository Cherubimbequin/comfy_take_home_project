<h1>Policy Purchased Notification</h1>
<p>A new policy has been purchased with the following details:</p>
<ul>
    <li><strong>Policy Number:</strong> {{ $policyNumber }}</li>
    <li><strong>Buyer:</strong> {{ $buyerName }}</li>
    <li><strong>Premium Amount:</strong> GH₵{{ number_format($policyAmount, 2) }}</li>
</ul>
<p>Please log in to the admin panel for more details.</p>
