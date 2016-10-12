<?php
/* Main class, used to obtain order information,
   run pipeline sections, audit orders, etc. */
class OrderProcessor
{
  public  $mOrderInfo;
  public  $mOrderDetailsInfo;
  public  $mCustomerInfo;
  public  $mContinueNow;

  private $_mCurrentPipelineSection;
  private $_mOrderProcessStage;

  // Class constructor
  public function __construct($orderId)
  {
    // Get order
    $this->mOrderInfo = Orders::GetOrderInfo($orderId);

    if (empty ($this->mOrderInfo['shipping_id']))
      $this->mOrderInfo['shipping_id'] = -1;

    if (empty ($this->mOrderInfo['tax_id']))
      $this->mOrderInfo['tax_id'] = -1;

    // Get order details
    $this->mOrderDetailsInfo = Orders::GetOrderDetails($orderId);

    // Get customer associated with the processed order
    $this->mCustomerInfo = Customer::Get($this->mOrderInfo['customer_id']);

    $credit_card = new SecureCard();
    $credit_card->LoadEncryptedDataAndDecrypt(
      $this->mCustomerInfo['credit_card']);

    $this->mCustomerInfo['credit_card'] = $credit_card;
  }

  /* Process is called from presentation/checkout_info.php and
     presentation/admin_orders.php to process an order */
  public function Process()
  {
    // Configure processor
    $this->mContinueNow = true;

    // Log start of execution
    $this->CreateAudit('Order Processor started.', 10000);

    // Process pipeline section
    try
    {
      while ($this->mContinueNow)
      {
        $this->mContinueNow = false;

        $this->_GetCurrentPipelineSection();
        $this->_mCurrentPipelineSection->Process($this);
      }
    }
    catch(Exception $e)
    {
      $this->MailAdmin('Order Processing error occurred.',
                       'Exception: "' . $e->getMessage() . '" on ' .
                       $e->getFile() . ' line ' . $e->getLine(),
                       $this->_mOrderProcessStage);

      $this->CreateAudit('Order Processing error occurred.', 10002);

      throw new Exception('Error occurred, order aborted. ' .
                          'Details mailed to administrator.');
    }

    $this->CreateAudit('Order Processor finished.', 10001);
  }

  // Adds audit message
  public function CreateAudit($message, $code)
  {
    Orders::CreateAudit($this->mOrderInfo['order_id'], $message, $code);
  }

  // Builds e-mail message
  public function MailAdmin($subject, $message, $sourceStage)
  {
    $to = ADMIN_EMAIL;
    $headers = 'From: ' . ORDER_PROCESSOR_EMAIL . "\r\n";
    $body = 'Message: ' . $message . "\n" .
            'Source: ' . $sourceStage . "\n" .
            'Order ID: ' . $this->mOrderInfo['order_id'];

    $result = mail($to, $subject, $body, $headers);

    if ($result === false)
    {
      throw new Exception ('Failed sending this mail to administrator:' .
                           "\n" . $body);
    }
  }

  // Gets current pipeline section
  private function _GetCurrentPipelineSection()
  {
    switch($this->mOrderInfo['status'])
    {
      case 0:
        $this->_mOrderProcessStage = $this->mOrderInfo['status'];
        $this->_mCurrentPipelineSection = new PsInitialNotification();

        break;
      case 1:
        $this->_mOrderProcessStage = $this->mOrderInfo['status'];
        $this->_mCurrentPipelineSection = new PsCheckFunds();

        break;
      case 2:
        $this->_mOrderProcessStage = $this->mOrderInfo['status'];
        $this->_mCurrentPipelineSection = new PsCheckStock();

        break;
      case 3:
        $this->_mOrderProcessStage = $this->mOrderInfo['status'];
        $this->_mCurrentPipelineSection = new PsStockOk();

        break;
      case 4:
        $this->_mOrderProcessStage = $this->mOrderInfo['status'];
        $this->_mCurrentPipelineSection = new PsTakePayment();

        break;
      case 5:
        $this->_mOrderProcessStage = $this->mOrderInfo['status'];
        $this->_mCurrentPipelineSection = new PsShipGoods();

        break;
      case 6:
        $this->_mOrderProcessStage = $this->mOrderInfo['status'];
        $this->_mCurrentPipelineSection = new PsShipOk();

        break;
      case 7:
        $this->_mOrderProcessStage = $this->mOrderInfo['status'];
        $this->_mCurrentPipelineSection = new PsFinalNotification();

        break;
      case 8:
        $this->_mOrderProcessStage = 100;
        throw new Exception('Order already been completed.');

        break;
      default:
        $this->_mOrderProcessStage = 100;
        throw new Exception('Unknown pipeline section requested.');
    }
  }

  // Set order status
  public function UpdateOrderStatus($status)
  {
    Orders::UpdateOrderStatus($this->mOrderInfo['order_id'], $status);
    $this->mOrderInfo['status'] = $status;
  }

  // Set order's authorization code and reference code
  public function SetAuthCodeAndReference($authCode, $reference)
  {
    Orders::SetOrderAuthCodeAndReference($this->mOrderInfo['order_id'],
                                         $authCode, $reference);

    $this->mOrderInfo['auth_code'] = $authCode;
    $this->mOrderInfo['reference'] = $reference;
  }

  // Set order's ship date
  public function SetDateShipped()
  {
    Orders::SetDateShipped($this->mOrderInfo['order_id']);

    $this->mOrderInfo['shipped_on'] = date('Y-m-d');
  }

  // Send e-mail to the customer
  public function MailCustomer($subject, $body)
  {
    $to = $this->mCustomerInfo['email'];
    $headers = 'From: ' . CUSTOMER_SERVICE_EMAIL . "\r\n";
    $result = mail($to, $subject, $body, $headers);

    if ($result === false)
    {
      throw new Exception ('Unable to send e-mail to customer.');
    }
  }

  // Send e-mail to the supplier
  public function MailSupplier($subject, $body)
  {
    $to = SUPPLIER_EMAIL;
    $headers = 'From: ' . ORDER_PROCESSOR_EMAIL . "\r\n";
    $result = mail($to, $subject, $body, $headers);

    if ($result === false)
    {
      throw new Exception ('Unable to send e-mail to supplier.');
    }
  }

  // Returns a string that contains the customer's address
  public function GetCustomerAddressAsString()
  {
    $new_line = "\n";

    $address_details = $this->mCustomerInfo['name'] . $new_line .
                       $this->mCustomerInfo['address_1'] . $new_line;

    if (!empty ($this->mOrderInfo['address_2']))
      $address_details .= $this->mCustomerInfo['address_2'] . $new_line;

    $address_details .= $this->mCustomerInfo['city'] . $new_line .
                        $this->mCustomerInfo['region'] . $new_line .
                        $this->mCustomerInfo['postal_code'] . $new_line .
                        $this->mCustomerInfo['country'];

    return $address_details;
  }

  public function GetOrderAsString($withCustomerDetails = true)
  {
    $total_cost = 0.00;
    $order_details = '';
    $new_line = "\n";

    if ($withCustomerDetails)
    {
      $order_details = 'Customer address:' . $new_line .
                       $this->GetCustomerAddressAsString() .
                       $new_line . $new_line;

      $order_details .= 'Customer credit card:' . $new_line .
                        $this->mCustomerInfo['credit_card']->CardNumberX .
                        $new_line . $new_line;
    }

    foreach ($this->mOrderDetailsInfo as $order_detail)
    {
      $order_details .= $order_detail['quantity'] . ' ' .
                        $order_detail['product_name'] . '(' .
                        $order_detail['attributes'] . ') $' .
                        $order_detail['unit_cost'] . ' each, total cost $' .
                        number_format($order_detail['subtotal'],
                                      2, '.', '') . $new_line;
      $total_cost += $order_detail['subtotal'];
    }

    // Add shipping cost
    if ($this->mOrderInfo['shipping_id'] != -1)
    {
      $order_details .= 'Shipping: ' . $this->mOrderInfo['shipping_type'] .
                        $new_line;

      $total_cost += $this->mOrderInfo['shipping_cost'];
    }

    // Add tax
    if ($this->mOrderInfo['tax_id'] != -1 &&
        $this->mOrderInfo['tax_percentage'] != 0.00)
    {
      $tax_amount = round((float)$total_cost *
                          (float)$this->mOrderInfo['tax_percentage'], 2)
                          / 100.00;

      $order_details .= 'Tax: ' . $this->mOrderInfo['tax_type'] . ', $' .
                        number_format($tax_amount, 2, '.', '') .
                        $new_line;

      $total_cost += $tax_amount;
    }

    $order_details .= $new_line . 'Total order cost: $' .
                      number_format($total_cost, 2, '.', '');

    return $order_details;
  }
}
?>
