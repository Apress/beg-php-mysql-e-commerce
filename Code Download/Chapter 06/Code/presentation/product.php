<?php
// Handles product details
class Product
{
  // Public variables to be used in Smarty template
  public $mProduct;
  public $mProductLocations;
  public $mLinkToContinueShopping;
  public $mLocations;

  // Private stuff
  private $_mProductId;

  // Class constructor
  public function __construct()
  {
    // Variable initialization
    if (isset ($_GET['ProductId']))
      $this->_mProductId = (int)$_GET['ProductId'];
    else
      trigger_error('ProductId not set');
  }

  public function init()
  {
    // Get product details from business tier
    $this->mProduct = Catalog::GetProductDetails($this->_mProductId);

    if (isset ($_SESSION['link_to_continue_shopping']))
    {
      $continue_shopping =
        Link::QueryStringToArray($_SESSION['link_to_continue_shopping']);

      $page = 1;

      if (isset ($continue_shopping['Page']))
        $page = (int)$continue_shopping['Page'];

      if (isset ($continue_shopping['CategoryId']))
        $this->mLinkToContinueShopping =
          Link::ToCategory((int)$continue_shopping['DepartmentId'],
                           (int)$continue_shopping['CategoryId'], $page);
      elseif (isset ($continue_shopping['DepartmentId']))
        $this->mLinkToContinueShopping =
          Link::ToDepartment((int)$continue_shopping['DepartmentId'], $page);
      else
        $this->mLinkToContinueShopping = Link::ToIndex($page);
    }

    if ($this->mProduct['image'])
      $this->mProduct['image'] =
        Link::Build('product_images/' . $this->mProduct['image']);

    if ($this->mProduct['image_2'])
      $this->mProduct['image_2'] =
        Link::Build('product_images/' . $this->mProduct['image_2']);

    $this->mProduct['attributes'] =
      Catalog::GetProductAttributes($this->mProduct['product_id']);

    $this->mLocations = Catalog::GetProductLocations($this->_mProductId);

    // Build links for product departments and categories pages
    for ($i = 0; $i < count($this->mLocations); $i++)
    {
      $this->mLocations[$i]['link_to_department'] =
        Link::ToDepartment($this->mLocations[$i]['department_id']);

      $this->mLocations[$i]['link_to_category'] =
        Link::ToCategory($this->mLocations[$i]['department_id'],
                         $this->mLocations[$i]['category_id']);
    }
  }
}
?>
