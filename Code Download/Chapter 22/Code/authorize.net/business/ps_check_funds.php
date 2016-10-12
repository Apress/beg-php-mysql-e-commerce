<?php
class PsCheckFunds implements IPipelineSection
{
  public function Process($processor)
  {
    // Audit
    $processor->CreateAudit('PsCheckFunds started.', 20100);

    $order_total_cost = $processor->mOrderInfo['total_amount'];
    $order_total_cost += $processor->mOrderInfo['shipping_cost'];
    $order_total_cost +=
      round((float)$order_total_cost *
            (float)$this->mOrderInfo['tax_percentage'], 2) / 100.00;

    $exp_date = str_replace('/', '',
      $processor->mCustomerInfo['credit_card']->ExpiryDate);


    $transaction =
      array (
        'x_invoice_num' => $processor->mOrderInfo['order_id'],
        'x_amount' => $order_total_cost, // Amount to charge
        'x_card_num' => $processor->mCustomerInfo['credit_card']->CardNumber,
        'x_exp_date' => $exp_date, // Expiry (MMYY)
        'x_method' => 'CC',
        'x_type' => 'AUTH_ONLY');

    // Process Transaction
    $request = new AuthorizeNetRequest(AUTHORIZE_NET_URL);
    $request->SetRequest($transaction);

    $response = $request->GetResponse();

    $response = explode('|', $response);

    if ($response[0] == 1)
    {
      $processor->SetAuthCodeAndReference($response[4], $response[6]);

      // Audit
      $processor->CreateAudit('Funds available for purchase.', 20102);

      // Update order status
      $processor->UpdateOrderStatus(2);

      // Continue processing
      $processor->mContinueNow = true;
    }
    else
    {
      // Audit
      $processor->CreateAudit('Funds not available for purchase.', 20103);

      throw new Exception('Credit card check funds failed for order ' .
                          $processor->mOrderInfo['order_id'] . ".\n\n" .
                          'Data exchanged:' . "\n" .
                          var_export($transaction, true) . "\n" .
                          var_export($response, true));
    }

    // Audit
    $processor->CreateAudit('PsCheckFunds finished.', 20101);
  }
}
?>
