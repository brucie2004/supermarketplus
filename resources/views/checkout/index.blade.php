<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | SuperMarketPlus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/newcss.css') }}">
    @vite(['resources/css/newcss.css'])
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
                                
                                <!-- Stripe Credit Card Payment -->
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="payment_method" id="credit_card" value="credit_card" checked>
                                    <label class="form-check-label" for="credit_card">
                                        Credit Card (Stripe)
                                    </label>
                                </div>

                                <!-- Stripe Card Elements -->
                                <div id="stripe-card-element" class="mb-3" style="display: none;">
                                    <div class="card">
                                        <div class="card-body">
                                            <div id="card-element">
                                                <!-- Stripe.js will inject the Card Element here -->
                                            </div>
                                            <div id="card-errors" class="text-danger mt-2" role="alert"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Buttons -->
                                <div id="stripe-payment-button" style="display: none;">
                                    <button type="button" id="submit-payment" class="btn btn-primary btn-lg w-100">
                                        <span id="payment-button-text">Pay ${{ number_format($grandTotal, 2) }}</span>
                                        <div id="payment-spinner" class="spinner-border spinner-border-sm ms-2" style="display: none;"></div>
                                    </button>
                                </div>

                                <!-- Alternative Payment Methods -->
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
                                        <a href="{{ route('product.show', $item['product']->slug) }}" class="text-decoration-none text-dark">
                                            <small><strong>{{ $item['product']->name }}</strong></small>
                                        </a>
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
    <script src="https://js.stripe.com/v3/"></script>

<script>
    // Initialize Stripe
    const stripe = Stripe('{{ config('services.stripe.key') }}');
    const elements = stripe.elements();
    
    // Create card element
    const cardElement = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#424770',
                '::placeholder': {
                    color: '#aab7c4',
                },
            },
        },
    });
    
    cardElement.mount('#card-element');
    
    // Handle real-time validation errors from the card Element
    cardElement.on('change', function(event) {
        const displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });
    
    // Show/hide Stripe elements based on payment method selection
    document.querySelectorAll('input[name="payment_method"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const stripeSection = document.getElementById('stripe-card-element');
            const stripeButton = document.getElementById('stripe-payment-button');
            
            if (this.value === 'credit_card') {
                stripeSection.style.display = 'block';
                stripeButton.style.display = 'block';
            } else {
                stripeSection.style.display = 'none';
                stripeButton.style.display = 'none';
            }
        });
    });
    
    // Trigger change event on page load to show Stripe for default selection
    document.getElementById('credit_card').dispatchEvent(new Event('change'));
    
    // Handle payment submission
    document.getElementById('submit-payment').addEventListener('click', async function() {
        const submitButton = this;
        const buttonText = document.getElementById('payment-button-text');
        const spinner = document.getElementById('payment-spinner');
        
        // Disable button and show spinner
        submitButton.disabled = true;
        buttonText.textContent = 'Processing...';
        spinner.style.display = 'inline-block';
        
        try {
            // Create payment intent
            const response = await fetch('{{ route("payment.create-intent") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    // We'll get the order_id after creating the order
                    // For now, we'll handle this in the next step
                })
            });
            
            const { clientSecret } = await response.json();
            
            // Confirm payment with Stripe
            const { error, paymentIntent } = await stripe.confirmCardPayment(clientSecret, {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: document.getElementById('shipping_first_name').value + ' ' + document.getElementById('shipping_last_name').value,
                        email: document.getElementById('shipping_email').value,
                        phone: document.getElementById('shipping_phone').value,
                        address: {
                            line1: document.getElementById('shipping_address').value,
                            city: document.getElementById('shipping_city').value,
                            state: document.getElementById('shipping_state').value,
                            postal_code: document.getElementById('shipping_postal_code').value,
                            country: document.getElementById('shipping_country').value,
                        }
                    }
                }
            });
            
            if (error) {
                // Show error to customer
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;
                
                // Re-enable button
                submitButton.disabled = false;
                buttonText.textContent = 'Pay ${{ number_format($grandTotal, 2) }}';
                spinner.style.display = 'none';
            } else {
                // Payment succeeded - submit the form
                document.getElementById('checkout-form').submit();
            }
        } catch (error) {
            console.error('Error:', error);
            document.getElementById('card-errors').textContent = 'An error occurred. Please try again.';
            
            // Re-enable button
            submitButton.disabled = false;
            buttonText.textContent = 'Pay ${{ number_format($grandTotal, 2) }}';
            spinner.style.display = 'none';
        }
    });
</script>
</body>
</html>