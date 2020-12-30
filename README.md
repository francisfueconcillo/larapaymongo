# LaraPaymongo

LaraPaymongo is a [PayMongo](https://paymongo.com) integration with Laravel.


## What the library can do
- Do provide Vue Components that can be used in a Laravel blade view template of the main app.
- Do Handle frontend and backend calls to PayMongo APIs
- Do provide API endpoints for PayMongo Webhooks. Used fro GCash and GrabPay payments (not yet implemented)

## What the library won't do
- Won't provide e-commerce functionalities like Shopping Cart, Order management, Item Inventory management. This should be handled by the main app.


## Available Vue Components
### larapay-btn
Purchase Button. Clicking this button will redirect user to /purchase/<itemid>. The route, view and controller for the Purchase Page must be created in the main app. Check [Implementation Section](#implementation)
```
<larapay-btn itemid="{{ $itemId }}"></larapay-btn>
```
where `itemid` is the Item ID to purchase. This can also be an Order ID, where 

### larapay-card
Credit/Debit Card Payment Form.
```
<larapay-card clientkey="{{ $clientKey }}"></larapay-card>
```
where `clientkey` is the PayMongo [Payment Intent](https://developers.paymongo.com/reference#retrieve-a-paymentintent) client_key. 

### larapay-gcash
GCash Payment Form (not yet implemented)
### larapay-grab
GrabPay Payment Form (not yet implemented)


## Installation
```
composer require peppertech/larapaymongo "0.1.*" 
composer dump-autoload
php artisan vendor:publish --tag="vue-components"
```
Note:

## Configuration
- Set the following environment variables

Variable | Required | Description | Default Value
--- | --- | --- | ---
MIX_PAYMONGO_API_URL | Yes | PayMongo API URL | https://api.paymongo.com/v1
MIX_PAYMONGO_PUBLIC_KEY | Yes | PayMongo Public Key. Values for Test or Live will be provided. | none
MIX_PAYMENT_CALLBACK_URL | Yes | MUST CHANGE THIS! Callback URL to be called when payment is successful, to perform more backend task like closing an order or sending payment confirmation by email to customer | /api/samplepaymentcallback (this default endpoint is only available in `local` environment.
PAYMONGO_STATEMENT_DESCRIPTOR | Yes | The string that will appear on customer Billing Statement. This should be different per project. | Peppertech
PAYMONGO_SECRET_KEY | Yes | PayMongo Secret Key. Values for Test or Live will be provided. | none
PAYMONGO_PUBLIC_KEY | Yes | PayMongo Public Key. same as MIX_PAYMONGO_PUBLIC_KEY | none
PAYMONGO_WEBHOOK_SIG | NO for now | PayMongo Webhook Signature. This should be different per project. Generated using PayMongo API only once per project. | none


## Implementation
- Create a Route for Purchase Page at [your project url]/purchase/[item-id]
- Create a View for Purchase Page based from `src/views/samplepurchase.blade.php` file of this package
- Create a Controller for Purchase Page based from `src/SamplePurchaseController.php` file of this package


## Testing

Go to URL path [your project url]/samplepurchase/1 . A Sample Purchase Page should appear.


