<?php
class CustomerAddress
{
  // Public attributes
  public $mAddress1 = '';
  public $mAddress2 = '';
  public $mCity = '';
  public $mRegion = '';
  public $mPostalCode = '';
  public $mCountry = '';
  public $mShippingRegion = '';
  public $mShippingRegions = array ();
  public $mAddress1Error = 0;
  public $mCityError = 0;
  public $mRegionError = 0;
  public $mPostalCodeError = 0;
  public $mCountryError = 0;
  public $mShippingRegionError = 0;
  public $mLinkToAddressDetails;
  public $mLinkToCancelPage;

  // Private attributes
  private $_mErrors = 0;

  // Class constructor
  public function __construct()
  {
    // Set form action target
    $this->mLinkToAddressDetails = Link::ToAddressDetails();

    // Set the cancel page
    if (isset ($_SESSION['customer_cancel_link']))
      $this->mLinkToCancelPage = $_SESSION['customer_cancel_link'];
    else
      $this->mLinkToCancelPage = Link::ToIndex();

    // Check if we have submitted data
    if (isset ($_POST['sended']))
    {
      // Address 1 cannot be empty
      if (empty ($_POST['address1']))
      {
        $this->mAddress1Error = 1;
        $this->_mErrors++;
      }
      else
        $this->mAddress1 = $_POST['address1'];

      if (isset ($_POST['address2']))
        $this->mAddress2 = $_POST['address2'];

      if (empty ($_POST['city']))
      {
        $this->mCityError = 1;
        $this->_mErrors++;
      }
      else
        $this->mCity = $_POST['city'];

      if (empty ($_POST['region']))
      {
        $this->mRegionError = 1;
        $this->_mErrors++;
      }
      else
        $this->mRegion = $_POST['region'];

      if (empty ($_POST['postalCode']))
      {
        $this->mPostalCodeError = 1;
        $this->_mErrors++;
      }
      else
        $this->mPostalCode = $_POST['postalCode'];

      if (empty ($_POST['country']))
      {
        $this->mCountryError = 1;
        $this->_mErrors++;
      }
      else
        $this->mCountry = $_POST['country'];

      if ($_POST['shippingRegion'] == 1)
      {
        $this->mShippingRegionError = 1;
        $this->_mErrors++;
      }
      else
        $this->mShippingRegion = $_POST['shippingRegion'];
    }
  }

  public function init()
  {
    $shipping_regions = Customer::GetShippingRegions();

    foreach ($shipping_regions as $item)
      $this->mShippingRegions[$item['shipping_region_id']] =
        $item['shipping_region'];

    if (!isset ($_POST['sended']))
    {
      $customer_data = Customer::Get();

      if (!(empty ($customer_data)))
      {
        $this->mAddress1       = $customer_data['address_1'];
        $this->mAddress2       = $customer_data['address_2'];
        $this->mCity           = $customer_data['city'];
        $this->mRegion         = $customer_data['region'];
        $this->mPostalCode     = $customer_data['postal_code'];
        $this->mCountry        = $customer_data['country'];
        $this->mShippingRegion = $customer_data['shipping_region_id'];
      }
    }
    elseif ($this->_mErrors == 0)
    {
      Customer::UpdateAddressDetails($this->mAddress1, $this->mAddress2,
        $this->mCity, $this->mRegion, $this->mPostalCode,
        $this->mCountry, $this->mShippingRegion);

      header('Location:' . $this->mLinkToCancelPage);

      exit();
    }
  }
}
?>
