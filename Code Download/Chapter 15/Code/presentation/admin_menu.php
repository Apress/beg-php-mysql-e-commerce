<?php
class AdminMenu
{
  public $mLinkToStoreAdmin;
  public $mLinkToAttributesAdmin;
  public $mLinkToCartsAdmin;
  public $mLinkToOrdersAdmin;
  public $mLinkToStoreFront;
  public $mLinkToLogout;

  public function __construct()
  {
    $this->mLinkToStoreAdmin = Link::ToAdmin();
    $this->mLinkToAttributesAdmin = Link::ToAttributesAdmin();
    $this->mLinkToCartsAdmin = Link::ToCartsAdmin();
    $this->mLinkToOrdersAdmin = Link::ToOrdersAdmin();

    if (isset ($_SESSION['link_to_store_front']))
      $this->mLinkToStoreFront = $_SESSION['link_to_store_front'];
    else
      $this->mLinkToStoreFront = Link::ToIndex();

    $this->mLinkToLogout = Link::ToLogout();
  }
}
?>
