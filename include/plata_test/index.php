<?php require_once('./config.php'); ?>

<form action="charge.php" method="post">
  <script
    src="https://checkout.stripe.com/checkout.js" class="stripe-button"
    data-key="pk_test_g6do5S237ekq10r65BnxO6S0"
    data-amount="999"
    data-currency="GBP"
    data-email="asd@dsadadStripe.com"
    data-name="Stripe.com"
    data-description="Widget"
    data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
    data-locale="auto">
  </script>
</form>
<? /*
<script src="https://checkout.stripe.com/checkout.js"></script>

<button id="customButton">Purchase</button>

<script>
var handler = StripeCheckout.configure({
  key: 'pk_test_g6do5S237ekq10r65BnxO6S0',
  image: 'https://www.christiantransfers.eu/images/logo1.png',
  locale: 'auto',
  token: function(token) {
	  console.log(token);
    // You can access the token ID with `token.id`.
    // Get the token ID to your server-side code for use.
  }
});

document.getElementById('customButton').addEventListener('click', function(e) {
  // Open Checkout with further options:
  handler.open({
    name: 'booking No 207375',
    description: 'Bucharest to Constanta',
    zipCode: false,
    currency: 'GBP',
    allowRememberMe: false,
    email: 'gigel@gigel.ro',
    amount: 2000
  });
  e.preventDefault();
});

// Close Checkout on page navigation:
window.addEventListener('popstate', function() {
  handler.close();
});
</script>*/ ?>