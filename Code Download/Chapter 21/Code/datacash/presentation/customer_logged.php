<?php
class CustomerLogged
{
  // Public attributes
  public $mCustomerName;
  public $mCreditCardAction = 'Add';
  public $mAddressAction = 'Add';
  public $mLinkToAccountDetails;
  public $mLinkToCreditCardDetails;
  public $mLinkToAddressDetails;
  public $mLinkToLogout;
  public $mSelectedMenuItem;

  // Class constructor
  public function __construct()
  {
    $this->mLinkToAccountDetails    = Link::ToAccountDetails();
    $this->mLinkToCreditCardDetails = Link::ToCreditCardDetails();
    $this->mLinkToAddressDetails    = Link::ToAddressDetails();

    $this->mLinkToLogout = Link::Build('index.php?Logout');

    if (isset ($_GET['AccountDetails']))
      $this->mSelectedMenuItem = 'account';
    elseif (isset ($_GET['CreditCardDetails']))
      $this->mSelectedMenuItem = 'credit-card';
    elseif (isset ($_GET['AddressDetails']))
      $this->mSelectedMenuItem = 'address';
  }

  public function init()
  {
    if (isset ($_GET['Logout']))
    {
      Customer::Logout();

      header('Location:' . $_SESSION['link_to_last_page_loaded']);

      exit();
    }

    $customer_data = Customer::Get();
    $this->mCustomerName = $customer_data['name'];

    if (!(empty ($customer_data['credit_card'])))
      $this->mCreditCardAction = 'Change';

    if (!(empty ($customer_data['address_1'])))
      $this->mAddressAction = 'Change';
  }
}
?>
