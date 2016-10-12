<?php
class PsInitialNotification implements IPipelineSection
{
  private $_mProcessor;

  public function Process($processor)
  {
    // Set processor reference
    $this->_mProcessor = $processor;

    // Audit
    $processor->CreateAudit('PsInitialNotification started.', 20000);

    // Send mail to customer
    $processor->MailCustomer(STORE_NAME . ' order received.',
                             $this->GetMailBody());

    // Audit
    $processor->CreateAudit('Notification e-mail sent to customer.', 20002);

    // Update order status
    $processor->UpdateOrderStatus(1);

    // Continue processing
    $processor->mContinueNow = true;

    // Audit
    $processor->CreateAudit('PsInitialNotification finished.', 20001);
  }

  private function GetMailBody()
  {
    $body = 'Thank you for your order! ' .
            'The products you have ordered are as follows:';
    $body.= "\n\n";

    $body.= $this->_mProcessor->GetOrderAsString(false);
    $body.= "\n\n";

    $body.= 'Your order will be shipped to:';
    $body.= "\n\n";

    $body.= $this->_mProcessor->GetCustomerAddressAsString();
    $body.= "\n\n";

    $body.= 'Order reference number: ';
    $body.= $this->_mProcessor->mOrderInfo['order_id'];
    $body.= "\n\n";

    $body.= 'You will receive a confirmation e-mail when this order ' .
            'has been dispatched. Thank you for shopping at ' .
            STORE_NAME . '!';

    return $body;
  }
}
?>
