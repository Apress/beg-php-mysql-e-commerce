<?php
class StoreFront
{
  public $mSiteUrl;

  // Class constructor
  public function __construct()
  {
    $this->mSiteUrl = Link::Build('');
  }
}
?>
