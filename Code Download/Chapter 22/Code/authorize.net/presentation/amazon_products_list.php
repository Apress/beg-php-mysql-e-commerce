<?php
// Class that handles receiving AWS data
class AmazonProductsList
{
  // Public variables available in smarty template
  public $mProducts;
  public $mDepartmentName;
  public $mDepartmentDescription;

  // Constructor
  public function __construct()
  {
    $this->mDepartmentName = AMAZON_DEPARTMENT_TITLE;
    $this->mDepartmentDescription = AMAZON_DEPARTMENT_DESCRIPTION;
  }

  public function init()
  {
    $amazon = new Amazon();
    $this->mProducts = $amazon->GetProducts();

    for ($i = 0;$i < count($this->mProducts); $i++)
      $this->mProducts[$i]['link_to_product'] =
        'http://www.amazon.com/exec/obidos/ASIN/' .
        $this->mProducts[$i]['asin'] . '/ref=nosim/' . AMAZON_ASSOCIATES_ID;
  }
}
?>