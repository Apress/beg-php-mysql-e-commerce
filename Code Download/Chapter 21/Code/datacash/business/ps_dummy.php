<?php
class PsDummy implements IPipelineSection
{
  public function Process($processor)
  {
    $processor->CreateAudit('PsDoNothing started.', 99999);

    $processor->CreateAudit('Customer: ' .
      $processor->mCustomerInfo['name'], 99999);

    $processor->CreateAudit('Order subtotal: ' .
      $processor->mOrderInfo['total_amount'], 99999);

    $processor->MailAdmin('Test.', 'Test mail from PsDummy.', 99999);

    $processor->CreateAudit('PsDoNothing finished', 99999);
  }
}
?>
