<?php
class PsShipOk implements IPipelineSection
{
  public function Process($processor)
  {
    // Audit
    $processor->CreateAudit('PsShipOk started.', 20600);

    // Set order shipment date
    $processor->SetDateShipped();

    // Audit
    $processor->CreateAudit('Order dispatched by supplier.', 20602);

    // Update order status
    $processor->UpdateOrderStatus(7);

    // Continue processing
    $processor->mContinueNow = true;

    // Audit
    $processor->CreateAudit('PsShipOk finished.', 20601);
  }
}
?>
