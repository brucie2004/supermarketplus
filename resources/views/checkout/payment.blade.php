<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Payment | SuperMarketPlus</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/newcss.css') }}">
    @vite(['resources/css/newcss.css'])
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    @include('partials.navbar')
    
    <div class="container my-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Cart</a></li>
                <li class="breadcrumb-item"><a href="{{ route('checkout') }}">Checkout</a></li>
                <li class="breadcrumb-item active" aria-current="page">Payment</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Complete Your Payment</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-4 text-center">
                            <h5>Order Total: ${{ number_format($order->total_amount, 2) }}</h5>
                            <p class="text-muted">Order #{{ $order->id }}</p>
                        </div>

                        <!-- Stripe Card Element -->
                        <div class="mb-3">
                            <label for="card-element" class="form-label">Credit Card Details</label>
                            <div id="card-element" class="form-control">
                                <!-- Stripe.js will inject the Card Element here -->
                            </div>
                            <div id="card-errors" class="text-danger mt-2" role="alert"></div>
                        </div>

                        <!-- Payment Button -->
                        <button id="submit-payment" class="btn btn-primary btn-lg w-100">
                            <span id="button-text">Pay ${{ number_format($order->total_amount, 2) }}</span>
                            <span id="payment-spinner" class="spinner-border spinner-border-sm ms-2" style="display: none;"></span>
                        </button>

                        <div class="mt-3 text-center">
                            <small class="text-muted">Your payment is secured with Stripe</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @include('partials.footer')
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        const stripe = Stripe('{{ $stripeKey }}');
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
        
        // Handle real-time validation errors
        cardElement.on('change', function(event) {
            const displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });
        
        // Handle form submission
        document.getElementById('submit-payment').addEventListener('click', async function() {
            const submitButton = this;
            const buttonText = document.getElementById('button-text');
            const spinner = document.getElementById('payment-spinner');
            
            // Disable button and show spinner
            submitButton.disabled = true;
            buttonText.textContent = 'Processing...';
            spinner.style.display = 'inline-block';
            
            try {
                // Confirm payment with Stripe
                const { error, paymentIntent } = await stripe.confirmCardPayment(
                    '{{ $clientSecret }}',
                    {
                        payment_method: {
                            card: cardElement,
                        }
                    }
                );
                
                if (error) {
                    // Show error to customer
                    const errorElement = document.getElementById('card-errors');
                    errorElement.textContent = error.message;
                    
                    // Re-enable button
                    submitButton.disabled = false;
                    buttonText.textContent = 'Pay ${{ number_format($order->total_amount, 2) }}';
                    spinner.style.display = 'none';
                } else {
                    // Payment succeeded - redirect to confirmation
                    window.location.href = '{{ route("checkout.payment.process", $order->id) }}?payment_intent_id=' + paymentIntent.id;
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('card-errors').textContent = 'An error occurred. Please try again.';
                
                // Re-enable button
                submitButton.disabled = false;
                buttonText.textContent = 'Pay ${{ number_format($order->total_amount, 2) }}';
                spinner.style.display = 'none';
            }
        });
    </script>
</body>
</html>