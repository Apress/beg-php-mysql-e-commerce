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
            (float)$processor->mOrderInfo['tax_percentage'], 2) / 100.00;

    $request = new DataCashRequest(DATACASH_URL);
    $request->MakeXmlPre(DATACASH_CLIENT, DATACASH_PASSWORD,
      $processor->mOrderInfo['order_id'] + 1000006,
      $order_total_cost, 'GBP',
      $processor->mCustomerInfo['credit_card']->CardNumber,
      $processor->mCustomerInfo['credit_card']->ExpiryDate,
      $processor->mCustomerInfo['credit_card']->IssueDate,
      $processor->mCustomerInfo['credit_card']->IssueNumber);

    $responseXml = $request->GetResponse();
    $xml = simplexml_load_string($responseXml);

    if ($xml->status == 1)
    {
      $processor->SetAuthCodeAndReference(
        $xml->merchantreference, $xml->datacash_reference);

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
                          $processor->mOrderInfo['order_id'] . "\n\n" .
                          'Data exchanged:' . "\n" .
                          $request->GetResponse() . "\n" . $responseXml);
    }

    // Audit
    $processor->CreateAudit('PsCheckFunds finished.', 20101);
  }
}
?>
