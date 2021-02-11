# LaraPaymongo
---

- [Overview](#overview)
- [Features](#features)
- [Installation](#install)
- [Configuration](#config)
- [Integration](#integrate)
- [Testing](#testing)


<a name="overview"></a>
## Overview
LaraPaymongo is a [PayMongo](https://paymongo.com) integration with Laravel. Paymongo currently supports Credit/Debit Cards (Philippines only), GCash and GrabPay Payments. LaraPaymongo provides an easy way to integrate your Laravel website with Paymongo.

<a name="features"></a>
## Features
- Ready-made Payment Pages and Purchase Button UI component
- Laravel routes, controllers views are ready-made - almost plug-and-play.
- Developers just need to define their application's logic before and after payments are made.

<a name="install"></a>
## Installation
```
composer require peppertech/larapaymongo
```
<a name="config"></a>
## Configuration
### Environment variables

Variable | Required | Description | Default Value
--- | --- | --- | ---
MIX_PAYMONGO_API_URL | Yes | PayMongo API URL | https://api.paymongo.com/v1
MIX_PAYMONGO_PUBLIC_KEY | Yes | PayMongo Public Key. Values for Test or Live will be provided. | none
PAYMONGO_STATEMENT_DESCRIPTOR | Yes | The string that will appear on customer Billing Statement. This should be different per project. | none
PAYMONGO_SECRET_KEY | Yes | PayMongo Secret Key. Values for Test or Live will be provided. | none
PAYMONGO_PUBLIC_KEY | Yes | PayMongo Public Key. same as MIX_PAYMONGO_PUBLIC_KEY | none
PAYMONGO_WEBHOOK_SIG | NO | PayMongo Webhook Signature. This should be different per project. Generated using PayMongo API only once per project. | none

<a name="integrate"></a>
## Integration
- Run the followign command to copy VueJS files and the `LaraPaymongoIntegrator` class to the main app
```
php artisan vendor:publish --tag="larapaymongo"
```

### `LaraPaymongoIntegrator` class
The publish command will copy `LaraPaymongoIntegrator` in `/app` diectory. This class will contain the necessary logic of your application to be ran before and after payment is done by the user.
- `updateTransactionSourceId()` method is called by LaraPaymongo when Source ID needs to be save in database against the Transaction Reference ID (this could be the Order ID in your application).
- `getTransactionDetails()` method is called by LaraPaymongo when it needs the Transaction Details for the purchase. This method should query your database to retrive the information.
- `completeTransaction()` method is called by LaraPaymongo after the payment is successful.

### Views and Purchase Button
Vue Components are copied from this package to your app in `resources/js/components`.
### Purchase Button UI Component

The Purchase Button can be placed anywhere in your app and clicking this button will redirect user to `/payment/<referid>`. Where `referid` is the Transaction Reference ID.
```
<larapay-btn referid="{{ $referid }}"></larapay-btn>
```
### Ready-made Routes and Views
Here are the available routes and views for LaraPaymongo
- `/payment/<referid>` The Payment Page, where `referid` is the Transaction Reference ID.
- `/payment/source/{method}/{referId}` URL that gets called to generate a Source ID from Paymongo, when GCash/GrabPay payments are selected, where `method` can be `gcash|grab_pay` and `referId` is the Transaction Reference ID.
- `/payment/verify/{paymentIntentId}`, callback URL when Card Payment is successful. where `paymentIntentId` is Paymongo Payment Intent ID.
- `/payment/details/{referId}` callback URL for GCash and GrabPay payments. It can also be used to check the status of the Transaction.

**IMPORTANT:** The `Views/payment.blade.php` of this package extends from `view/layouts.app` of your app, so it should exist in your application's views.

<a name="testing"></a>
## Testing

- After Installation and Configuration, navigate to `http://<your app domain>/payment/111`, it should show the Payment Page. 
- Use the Test Credit Card numbers from [PayMongo Testing](https://developers.paymongo.com/docs/testing)

