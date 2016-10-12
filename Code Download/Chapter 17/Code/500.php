<?php
  // Set the 500 status code
  header('HTTP/1.0 500 Internal Server Error');

  require_once 'include/config.php';
  require_once PRESENTATION_DIR . 'link.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <title>
      TShirtShop Application Error (500): Demo Product Catalog from
      Beginning PHP and MySQL E-Commerce
    </title>
    <link href="<?php echo Link::Build('styles/tshirtshop.css'); ?>"
     type="text/css" rel="stylesheet" />
  </head>
  <body>
    <div id="doc" class="yui-t7">
      <div id="bd">
        <div id="header" class="yui-g">
          <a href="<?php echo Link::Build(''); ?>">
            <img src="<?php echo Link::Build('images/tshirtshop.png'); ?>"
             alt="tshirtshop logo" />
          </a>
        </div>
        <div id="contents" class="yui-g">
          <h1>
            TShirtShop is experiencing technical difficulties.
          </h1>
          <p>
            Please
            <a href="<?php echo Link::Build(''); ?>">visit us</a> soon,
            or <a href="<?php echo ADMIN_ERROR_MAIL; ?>">contact us</a>.
          </p>
          <p>Thank you!</p>
          <p>The TShirtShop team.</p>
        </div>
      </div>
    </div>
  </body>
</html>
