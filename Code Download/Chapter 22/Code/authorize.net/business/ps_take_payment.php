<?php
class PsTakePayment implements IPipelineSection
{
  public function Process($processor)
  {
    // Audit
    $processor->CreateAudit('PsTakePayment started.', 20400);

    $transaction =
      array ('x_ref_trans_id' => $processor->mOrderInfo['reference'],
             'x_method'       => 'CC',
             'x_type'         => 'PRIOR_AUTH_CAPTURE');

    // Process Transaction
    $request = new AuthorizeNetRequest(AUTHORIZE_NET_URL);
    $request->SetRequest($transaction);

    $response = $request->GetResponse();

    $response = explode('|', $response);

    if ($response[0] == 1)
    {
      // Audit
      $processor->CreateAudit(
        'Funds deducted from customer credit card account.', 20402);

      // Update order status
      $processor->UpdateOrderStatus(5);

      // Continue processing
      $processor->mContinueNow = true;

      // Audit
      $processor->CreateAudit('PsTakePayment finished.', 20401);
    }
    else
    {
      // Audit
      $processor->CreateAudit(
        'Error taking funds from customer credit card.', 20403);

      throw new Exception('Credit card take payment failed for order ' .
                          $processor->mOrderInfo['order_id'] . ".\n\n" .
                          'Data exchanged:' . "\n" .
                          var_export($transaction, true) . "\n" .
                          var_export($response, true));
    }
  }
}
?>
