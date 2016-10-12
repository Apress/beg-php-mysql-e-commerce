<?php
class AdminMenu
{
  public $mLinkToStoreAdmin;
  public $mLinkToStoreFront;
  public $mLinkToLogout;

  public function __construct()
  {
    $this->mLinkToStoreAdmin = Link::ToAdmin();
    $this->mLinkToStoreFront = Link::ToIndex();
    $this->mLinkToLogout = Link::ToLogout();
  }
}
?>
