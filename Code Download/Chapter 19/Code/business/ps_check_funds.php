<?php
class PsCheckFunds implements IPipelineSection
{
  public function Process($processor)
  {
    // Audit
    $processor->CreateAudit('PsCheckFunds started.', 20100);

    /* Check customer funds assume they exist for now
       set order authorization code and reference */
    $processor->SetAuthCodeAndReference('DummyAuthCode',
                                        'DummyReference');

    // Audit
    $processor->CreateAudit('Funds available for purchase.', 20102);

    // Update order status
    $processor->UpdateOrderStatus(2);

    // Continue processing
    $processor->mContinueNow = true;

    // Audit
    $processor->CreateAudit('PsCheckFunds finished.', 20101);
  }
}
?>
