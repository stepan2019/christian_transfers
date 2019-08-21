<?php
require_once('../stripe/init.php');

$stripe = array(
  "secret_key"      => "sk_test_BQokikJOvBiI2HlWgH4olfQ2",
  "publishable_key" => "pk_test_g6do5S237ekq10r65BnxO6S0"
);

\Stripe\Stripe::setApiKey($stripe['secret_key']);
?>