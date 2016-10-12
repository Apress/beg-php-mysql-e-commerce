<?php
class PsTakePayment implements IPipelineSection
{
  public function Process($processor)
  {
    // Audit
    $processor->CreateAudit('PsTakePayment started.', 20400);

    // Take customer funds assume success for now

    // Audit
    $processor->CreateAudit('Funds deducted from customer credit card account.',
                            20402);

    // Update order status
    $processor->UpdateOrderStatus(5);

    // Continue processing
    $processor->mContinueNow = true;

    // Audit
    $processor->CreateAudit('PsTakePayment finished.', 20401);
  }
}
?>
