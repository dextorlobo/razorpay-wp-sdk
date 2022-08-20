# RazorPay WP SDK plugin

## Steps to use plugin
`composer install`

## Endpoints
* GET `wp-json/razorpaywp/payments?count={count}&skip={skip}`
* GET `wp-json/razorpaywp/payments/pay_{payment_id}`
* GET `wp-json/razorpaywp/insert-payments?count=10&skip=0`
* POST `wp-json/razorpaywp/payments-webhook`
