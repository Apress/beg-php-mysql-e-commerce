<?php
class FirstPageContents
{
  public $mLinkToAdmin;

  public function __construct()
  {
    $this->mLinkToAdmin = Link::ToAdmin();
  }
}
?>
