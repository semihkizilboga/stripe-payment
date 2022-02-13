<?php
require 'stripe-php-master/init.php';
\Stripe\Stripe::setApiKey('sk_test_51KBxy3Jk9sxiYOaj2CsUKmzl1CTn7AudTjevEDWjUEgOVcBmyLywcLldlK3kCb0Je5KbvJXbCFho5YAIjlNeIuVK007TtIMQFM');

$intent = \Stripe\PaymentIntent::create([
    'amount' => 5000,
    'currency' => 'usd',
    'metadata' => ['integration_check' => 'accept_a_payment'],
]);


?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css">
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Poppins" />
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <!-- Favicon -->
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
    <title>Stripe - Ödeme Pos</title>
    <script src="https://js.stripe.com/v3/"></script>

</head>
<style>
    .demoInputBox {
        padding: 10px;
        border: #d0d0d0 1px solid;
        border-radius: 4px;
        background-color: #FFF;
        width: 100%;
        margin-top: 5px;
        box-sizing: border-box;
    }

    button {
        float: left;
        display: block;
        background: #27324E;
        color: white;
        border-radius: 2px;
        border: 0;
        margin-top: 20px;
        font-size: 19px;
        font-weight: 400;
        width: 100%;
        height: 47px;
        line-height: 45px;
        outline: none;
    }

    button:focus {
        background: #24B47E;
    }

    button:active {
        background: #159570;
    }

    .main-amount {
        font-size: 20px;
        font-weight: bold;
        color: #27324E;
        letter-spacing: 2px;
        border: 1px solid green;
        background-color: #f9f9f9;
        padding: 10px;
        text-decoration: none;
        display: inline-block;
    }

    #card-element {
        padding: 10px;
        border: #d0d0d0 1px solid;
        border-radius: 4px;
        background-color: #FFF;
        width: 100%;
        margin-top: 5px;
        box-sizing: border-box;
    }
</style>

<body>
    <div class="container" style="margin-top:50px;">
        <h3 align="center">Stripe Ödeme Sistemi</h3>
        <div style="justify-content: center;display: flex; margin-top:50px;">
            <div class="col-lg-4">
                <!-- card element goes here-->
                <form id="payment-form" data-secret="<?= $intent->client_secret ?>">
                    <div class="field-row">
                        <label>Card Holder Name</label> <span id="card-holder-name-info" class="info"></span><br>
                        <input type="text" id="fullname" name="fullname" class="demoInputBox" required>
                    </div><br>
                    <div class="field-row">
                        <label>Email</label> <span id="email-info" class="info"></span><br>
                        <input type="email" id="email" name="email" class="demoInputBox" required>
                    </div>
                    <br>
                    <label>Kart Bilgileriniz</label><br>

                    <div id="card-element"> 
                    </div> 
                    <div id="card-errors" role="alert"></div>

                    <button id="card-button">Pay</button>
                </form> 
            </div>
        </div> 
    </div>

    </div>

    <script>
        // Set your publishable key: remember to change this to your live publishable key in production
        // See your keys here: https://dashboard.stripe.com/apikeys
        var stripe = Stripe('pk_test_51KBxy3Jk9sxiYOajzQuE345v283LXdHUWgXLvp7ZvDVAXSI2OhkZFXLILBBYQw0xdgqJ0Url9O0TfEchduvXmFF7006XGpIjtw');
        var elements = stripe.elements();

        // Set up Stripe.js and Elements to use in checkout form
        var style = {
            base: {
                color: "green",
            }
        };

        var card = elements.create("card", {
            style: style
        });
        card.mount("#card-element");

        card.on('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        var form = document.getElementById('payment-form');
        var fullname = document.getElementById('fullname');
        var email = document.getElementById('email');
        form.addEventListener('submit', function(ev) {
            ev.preventDefault();
            // If the client secret was rendered server-side as a data-secret attribute
            // on the <form> element, you can retrieve it here by calling `form.dataset.secret`
            stripe.confirmCardPayment(form.dataset.secret, {
                payment_method: {
                    card: card,
                    billing_details: {
                        name: fullname,
                        email: email
                    }
                }
            }).then(function(result) {
                if (result.error) {
                    // Show error to your customer (e.g., insufficient funds)
                    alert(result.error.message);
                } else {
                    // The payment has been processed!
                    if (result.paymentIntent.status === 'succeeded') {
                        // Show a success message to your customer
                        // There's a risk of the customer closing the window before callback
                        // execution. Set up a webhook or plugin to listen for the
                        // payment_intent.succeeded event that handles any business critical
                        // post-payment actions.
                        alert('The payment has been proccessed');
                        window.location.replace("http://localhost/stripe/v2/success.php");

                    }
                }
            });
        });
    </script>

</body>

</html>