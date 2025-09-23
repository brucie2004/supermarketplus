<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | SuperMarketPlus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    @include('partials.navbar')
    
    <div class="container my-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Cart</a></li>
                <li class="breadcrumb-item active" aria-current="page">Checkout</li>
            </ol>
        </nav>

        <h1 class="mb-4">Checkout</h1>
        
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Checkout Process</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form">
                            @csrf
                            
                            <!-- Shipping Address -->
                            <div class="mb-4">
                                <h5 class="border-bottom pb-2">Shipping Information</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="shipping_first_name" class="form-label">First Name *</label>
                                        <input type="text" class="form-control" id="shipping_first_name" name="shipping_first_name" required value="{{ old('shipping_first_name', Auth::user()->name ?? '') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="shipping_last_name" class="form-label">Last Name *</label>
                                        <input type="text" class="form-control" id="shipping_last_name" name="shipping_last_name" required value="{{ old('shipping_last_name') }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="shipping_email" class="form-label">Email *</label>
                                        <input type="email" class="form-control" id="shipping_email" name="shipping_email" required value="{{ old('shipping_email', Auth::user()->email ?? '') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="shipping_phone" class="form-label">Phone *</label>
                                        <input type="tel" class="form-control" id="shipping_phone" name="shipping_phone" required value="{{ old('shipping_phone') }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="shipping_address" class="form-label">Address *</label>
                                    <input type="text" class="form-control" id="shipping_address" name="shipping_address" required value="{{ old('shipping_address') }}">
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="shipping_city" class="form-label">City *</label>
                                        <input type="text" class="form-control" id="shipping_city" name="shipping_city" required value="{{ old('shipping_city') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="shipping_state" class="form-label">State *</label>
                                        <input type="text" class="form-control" id="shipping_state" name="shipping_state" required value="{{ old('shipping_state') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="shipping_postal_code" class="form-label">Postal Code *</label>
                                        <input type="text" class="form-control" id="shipping_postal_code" name="shipping_postal_code" required value="{{ old('shipping_postal_code') }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="shipping_country" class="form-label">Country *</label>
                                    <input type="text" class="form-control" id="shipping_country" name="shipping_country" required value="{{ old('shipping_country') }}">
                                </div>
                            </div>

                            <!-- Billing Address (Optional) -->
                            <div class="mb-4">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="same_as_shipping">
                                    <label class="form-check-label" for="same_as_shipping">
                                        Billing address same as shipping
                                    </label>
                                </div>
                                
                                <h5 class="border-bottom pb-2">Billing Information</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="billing_first_name" class="form-label">First Name</label>
                                        <input type="text" class="form-control" id="billing_first_name" name="billing_first_name" value="{{ old('billing_first_name') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="billing_last_name" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" id="billing_last_name" name="billing_last_name" value="{{ old('billing_last_name') }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="billing_email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="billing_email" name="billing_email" value="{{ old('billing_email') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="billing_phone" class="form-label">Phone</label>
                                        <input type="tel" class="form-control" id="billing_phone" name="billing_phone" value="{{ old('billing_phone') }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="billing_address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="billing_address" name="billing_address" value="{{ old('billing_address') }}">
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="billing_city" class="form-label">City</label>
                                        <input type="text" class="form-control" id="billing_city" name="billing_city" value="{{ old('billing_city') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="billing_state" class="form-label">State</label>
                                        <input type="text" class="form-control" id="billing_state" name="billing_state" value="{{ old('billing_state') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="billing_postal_code" class="form-label">Postal Code</label>
                                        <input type="text" class="form-control" id="billing_postal_code" name="billing_postal_code" value="{{ old('billing_postal_code') }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="billing_country" class="form-label">Country</label>
                                    <input type="text" class="form-control" id="billing_country" name="billing_country" value="{{ old('billing_country') }}">
                                </div>
                            </div>

                            <!-- Payment Method -->
                            <div class="mb-4">
                                <h5 class="border-bottom pb-2">Payment Method</h5>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" id="credit_card" value="credit_card" checked>
                                    <label class="form-check-label" for="credit_card">
                                        Credit Card
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" id="paypal" value="paypal">
                                    <label class="form-check-label" for="paypal">
                                        PayPal
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="payment_method" id="cash_on_delivery" value="cash_on_delivery">
                                    <label class="form-check-label" for="cash_on_delivery">
                                        Cash on Delivery
                                    </label>
                                </div>
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="agree_terms" name="agree_terms" required>
                                    <label class="form-check-label" for="agree_terms">
                                        I agree to the <a href="#" target="_blank">Terms and Conditions</a>
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100">Place Order</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span>${{ number_format($shipping, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax:</span>
                            <span>${{ number_format($tax, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong>${{ number_format($grandTotal, 2) }}</strong>
                        </div>
                        
                        <h6 class="mt-4">Items in Cart:</h6>
                        @foreach($cartItems as $item)
                            @if(isset($item['product']))
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <small>{{ $item['product']->name }}</small>
                                        <br>
                                        <small class="text-muted">Qty: {{ $item['quantity'] }}</small>
                                    </div>
                                    <small>${{ number_format($item['product']->price * $item['quantity'], 2) }}</small>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @include('partials.footer')
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Copy shipping address to billing address if checkbox is checked
        document.getElementById('same_as_shipping').addEventListener('change', function() {
            if (this.checked) {
                document.getElementById('billing_first_name').value = document.getElementById('shipping_first_name').value;
                document.getElementById('billing_last_name').value = document.getElementById('shipping_last_name').value;
                document.getElementById('billing_email').value = document.getElementById('shipping_email').value;
                document.getElementById('billing_phone').value = document.getElementById('shipping_phone').value;
                document.getElementById('billing_address').value = document.getElementById('shipping_address').value;
                document.getElementById('billing_city').value = document.getElementById('shipping_city').value;
                document.getElementById('billing_state').value = document.getElementById('shipping_state').value;
                document.getElementById('billing_postal_code').value = document.getElementById('shipping_postal_code').value;
                document.getElementById('billing_country').value = document.getElementById('shipping_country').value;
            } else {
                document.getElementById('billing_first_name').value = '';
                document.getElementById('billing_last_name').value = '';
                document.getElementById('billing_email').value = '';
                document.getElementById('billing_phone').value = '';
                document.getElementById('billing_address').value = '';
                document.getElementById('billing_city').value = '';
                document.getElementById('billing_state').value = '';
                document.getElementById('billing_postal_code').value = '';
                document.getElementById('billing_country').value = '';
            }
        });
    </script>
</body>
</html>