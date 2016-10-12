<?php
// Activate session
session_start();

// Start output buffer
ob_start();

// Include utility files
require_once 'include/config.php';
require_once BUSINESS_DIR . 'error_handler.php';

// Set the error handler
ErrorHandler::SetHandler();

// Load the application page template
require_once PRESENTATION_DIR . 'application.php';
require_once PRESENTATION_DIR . 'link.php';

// Load the database handler
require_once BUSINESS_DIR . 'database_handler.php'; 

// Load Business Tier
require_once BUSINESS_DIR . 'catalog.php';
require_once BUSINESS_DIR . 'shopping_cart.php';
require_once BUSINESS_DIR . 'password_hasher.php';
require_once BUSINESS_DIR . 'symmetric_crypt.php';
require_once BUSINESS_DIR . 'secure_card.php';
require_once BUSINESS_DIR . 'customer.php';
require_once BUSINESS_DIR . 'orders.php';
require_once BUSINESS_DIR . 'i_pipeline_section.php';
require_once BUSINESS_DIR . 'order_processor.php';
require_once BUSINESS_DIR . 'ps_initial_notification.php';
require_once BUSINESS_DIR . 'ps_check_funds.php';
require_once BUSINESS_DIR . 'ps_check_stock.php';
require_once BUSINESS_DIR . 'authorize_net_request.php';

// URL correction
Link::CheckRequest();

// Load Smarty template file
$application = new Application();

// Handle AJAX requests
if (isset ($_GET['AjaxRequest']))
{
  // Headers are sent to prevent browsers from caching
  header('Expires: Fri, 25 Dec 1980 00:00:00 GMT'); // Time in the past
  header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
  header('Cache-Control: no-cache, must-revalidate');
  header('Pragma: no-cache');
  header('Content-Type: text/html');

  if (isset ($_GET['CartAction']))
  {
    $cart_action = $_GET['CartAction'];

    if ($cart_action == ADD_PRODUCT)
    {
      require_once PRESENTATION_DIR . 'cart_details.php';

      $cart_details = new CartDetails();
      $cart_details->init();

      $application->display('cart_summary.tpl');
    }
    else
    {
      $application->display('cart_details.tpl');
    }
  }
  else
    trigger_error('CartAction not set', E_USER_ERROR);
}
else
{
  // Display the page
  $application->display('store_front.tpl');
}

// Close database connection
DatabaseHandler::Close();

// Output content from the buffer
flush();
ob_flush();
ob_end_clean();
?>
