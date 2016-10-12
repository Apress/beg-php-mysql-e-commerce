<?php
// Presentation tier class that deals with administering order details
class AdminOrderDetails
{
  // Public variables available in smarty template
  public $mOrderId;
  public $mOrderInfo;
  public $mOrderDetails;
  public $mEditEnabled;
  public $mOrderStatusOptions;
  public $mLinkToAdmin;
  public $mLinkToOrdersAdmin;
  public $mCustomerInfo;
  public $mTotalCost;
  public $mTax = 0.0;
  public $mProcessButtonText;
  public $mAuditTrail;

  // Class constructor
  public function __construct()
  {
    // Get the back link from session
    $this->mLinkToOrdersAdmin = $_SESSION['link_to_orders_admin'];

    $this->mLinkToAdmin = Link::ToAdmin();

    // We receive the order ID in the query string
    if (isset ($_GET['OrderId']))
      $this->mOrderId = (int) $_GET['OrderId'];
    else
      trigger_error('OrderId paramater is required');

    $this->mOrderStatusOptions = Orders::$mOrderStatusOptions;
  }

  // Initializes class members
  public function init()
  {
    if (isset ($_GET['submitUpdate']))
    {
      Orders::UpdateOrder($this->mOrderId, $_GET['status'],
        $_GET['comments'], $_GET['authCode'], $_GET['reference']);
    }

    if (isset ($_GET['submitProcessOrder']))
    {
      $processor = new OrderProcessor($this->mOrderId);
      $processor->Process();
    }

    $this->mOrderInfo = Orders::GetOrderInfo($this->mOrderId);
    $this->mOrderDetails = Orders::GetOrderDetails($this->mOrderId);
    $this->mCustomerInfo = Customer::Get($this->mOrderInfo['customer_id']);
    $this->mTotalCost = $this->mOrderInfo['total_amount'];
    $this->mAuditTrail = Orders::GetAuditTrail($this->mOrderId);

    if ($this->mOrderInfo['tax_percentage'] !== 0.0)
      $this->mTax = round((float)$this->mTotalCost *
                          (float)$this->mOrderInfo['tax_percentage'], 2)
                         / 100.00;

    $this->mTotalCost += $this->mOrderInfo['shipping_cost'];
    $this->mTotalCost += $this->mTax;

    // Format the values
    $this->mTotalCost = number_format($this->mTotalCost, 2, '.', '');
    $this->mTax = number_format($this->mTax, 2, '.', '');

    if ($this->mOrderInfo['status'] == 3)
      $this->mProcessButtonText = 'Confirm Stock for Order';
    elseif ($this->mOrderInfo['status'] == 6)
      $this->mProcessButtonText = 'Confirm Shipment for Order';

    // Value which specifies whether to enable or disable edit mode
    if (isset ($_GET['submitEdit']))
      $this->mEditEnabled = true;
    else
      $this->mEditEnabled = false;
  }
}
?>
