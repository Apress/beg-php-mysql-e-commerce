<?php
class CustomerCreditCard
{
  // Public attributes
  public $mCardHolderError;
  public $mCardNumberError;
  public $mExpDateError;
  public $mCardTypesError;
  public $mPlainCreditCard;
  public $mCardTypes;
  public $mLinkToCreditCardDetails;
  public $mLinkToCancelPage;

  // Private attributes
  private $_mErrors = 0;

  public function __construct()
  {
    $this->mPlainCreditCard = array('card_holder' => '',
      'card_number'  => '', 'issue_date' => '', 'expiry_date'   => '',
      'issue_number' => '', 'card_type'  => '', 'card_number_x' => '');

    // Set form action target
    $this->mLinkToCreditCardDetails = Link::ToCreditCardDetails();

    // Set the cancel page
    if (isset ($_SESSION['customer_cancel_link']))
      $this->mLinkToCancelPage = $_SESSION['customer_cancel_link'];
    else
      $this->mLinkToCancelPage = Link::ToIndex();

    $this->mCardTypes = array ('Mastercard' => 'Mastercard',
      'Visa' => 'Visa', 'Mastercard' => 'Mastercard',
      'Switch' => 'Switch', 'Solo' => 'Solo',
      'American Express' => 'American Express');

    // Check if we have submitted data
    if (isset ($_POST['sended']))
    {
      // Initialization/validation stuff
      if (empty ($_POST['cardHolder']))
      {
        $this->mCardHolderError = 1;
        $this->_mErrors++;
      }
      else
        $this->mPlainCreditCard['card_holder'] = $_POST['cardHolder'];

      if (empty ($_POST['cardNumber']))
      {
        $this->mCardNumberError = 1;
        $this->_mErrors++;
      }
      else
        $this->mPlainCreditCard['card_number'] = $_POST['cardNumber'];

      if (empty ($_POST['expDate']))
      {
        $this->mExpDateError = 1;
        $this->_mErrors++;
      }
      else
        $this->mPlainCreditCard['expiry_date'] = $_POST['expDate'];

      if (isset ($_POST['issueDate']))
        $this->mPlainCreditCard['issue_date'] = $_POST['issueDate'];

      if (isset ($_POST['issueNumber']))
        $this->mPlainCreditCard['issue_number'] = $_POST['issueNumber'];

      $this->mPlainCreditCard['card_type'] = $_POST['cardType'];

      if (empty ($this->mPlainCreditCard['card_type']))
      {
        $this->mCardTypeError = 1;
        $this->_mErrors++;
      }
    }
  }

  public function init()
  {
    if (!isset ($_POST['sended']))
    {
      // Get credit card information
      $this->mPlainCreditCard = Customer::GetPlainCreditCard();
    }
    elseif ($this->_mErrors == 0)
    {
      // Update credit card information
      Customer::UpdateCreditCardDetails($this->mPlainCreditCard);

      header('Location:' . $this->mLinkToCancelPage);

      exit();
    }
  }
}
?>
