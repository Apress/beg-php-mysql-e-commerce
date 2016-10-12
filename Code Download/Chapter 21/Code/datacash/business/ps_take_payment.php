<?php
class PsTakePayment implements IPipelineSection
{
  public function Process($processor)
  {
    // Audit
    $processor->CreateAudit('PsTakePayment started.', 20400);

    $request = new DataCashRequest(DATACASH_URL);
    $request->MakeXmlFulFill(DATACASH_CLIENT, DATACASH_PASSWORD,
                             $processor->mOrderInfo['auth_code'],
                             $processor->mOrderInfo['reference']);

    $responseXml = $request->GetResponse();
    $xml = simplexml_load_string($responseXml);

    if ($xml->status == 1)
    {
      // Audit
      $processor->CreateAudit(
        'Funds deducted from customer credit card account.', 20402);

      // Update order status
      $processor->UpdateOrderStatus(5);

      // Continue processing
      $processor->mContinueNow = true;
    }
    else
    {
      // Audit
      $processor->CreateAudit('Could not deduct funds from credit card.',
                              20403);

      throw new Exception('Credit card take payment failed for order ' .
                          $processor->mOrderInfo['order_id'] . "\n\n" .
                          'Data exchanged:' . "\n" .
                          $request->GetResponse() . "\n" . $responseXml);
    }

    // Audit
    $processor->CreateAudit('PsTakePayment finished.', 20401);
  }
}
?>
