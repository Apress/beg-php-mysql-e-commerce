<?php
class PsShipGoods implements IPipelineSection
{
  private $_mProcessor;

  public function Process($processor)
  {
    // Set processor reference
    $this->_mProcessor = $processor;

    // Audit
    $processor->CreateAudit('PsShipGoods started.', 20500);

    // Send mail to supplier
    $processor->MailSupplier(STORE_NAME . ' ship goods.',
                             $this->GetMailBody());

    // Audit
    $processor->CreateAudit('Ship goods e-mail sent to supplier.', 20502);

    // Update order status
    $processor->UpdateOrderStatus(6);

    // Audit
    $processor->CreateAudit('PsShipGoods finished.', 20501);
  }

  private function GetMailBody()
  {
    $body = 'Payment has been received for the following goods:';
    $body.= "\n\n";

    $body.= $this->_mProcessor->GetOrderAsString(false);
    $body.= "\n\n";

    $body.= 'Please ship to:';
    $body.= "\n\n";

    $body.= $this->_mProcessor->GetCustomerAddressAsString();
    $body.= "\n\n";

    $body.= 'When goods have been shipped, please confirm via ' .
            Link::ToAdmin();
    $body.= "\n\n";

    $body.= 'Order reference number: ';
    $body.= $this->_mProcessor->mOrderInfo['order_id'];

    return $body;
  }
}
?>
