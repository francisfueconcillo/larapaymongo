<?php

namespace App;

class LaraPaymongoIntegrator
{

  /**
   * When user selects GCash or GrabPay, the generated Source Resource ID must be saved to the database
   * for later retrieval, when the Source becomes chargeable.
   * https://developers.paymongo.com/docs/accepting-gcash-payments
   * 
   * @param  String $referId Transaction Reference ID. examples: Order ID or Item ID. (Required)
   * @param  String $sourceId Source Resource ID when user selects GCash or GrabPay. (Required)
   */
  public static function updateTransactionSourceId(String $referId, String $sourceId)
  {

    // IMPLEMENT THIS
  }


  /**
   * Payment Page will call this to get the Transaction Details for payment.
   * 
   * @param  String $referId Transaction Reference ID. examples: Order ID or Item ID
   *
   * @return AssociativeArray Transaction details.
   *   id              String. Transaction Reference ID. (Required)
   *   name            String. Short summary of the transaction. (Required)
   *   description     String. Long description of the transaction,
   *   price           Float.  Total price of the transaction. 2 decimal places. (Required)
   *   currency        String. Only 'PHP' is supported at the moment.
   *   status          String. Payment status. Either 'paid' or 'unpaid'
   *   source_id       String. When GCash or Grabpay is selected, this is the ID of the Source Resource
   *                           that is saved in the database thru updateTransactionSourceId()
   *                           https://developers.paymongo.com/reference#the-sources-object
   */
  public static function getTransactionDetails(String $referId)
  {

    // IMPLEMENT THIS
    return [
      'id' => $referId,
      'name' => 'Order Number: '.$referId,
      'description' => 'Order summary here.',
      'price' => 100.00,
      'currency' => 'PHP',
      'status' => 'unpaid',
      'source_id' => null,
    ];
  }


  /**
   * After verified successfull payment, this method gets called for application 
   * to proceed with the transaction, like closing an order.
   * 
   * @param  String $referId Transaction Reference ID. examples: Order ID or Item ID (Required)
   *
   */
  public static function completeTransaction(String $referId)
  {
    // IMPLEMENT THIS
  }


}