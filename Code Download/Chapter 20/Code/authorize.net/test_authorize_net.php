<?php
session_start();

if (empty ($_GET['step']))
{
  require_once 'include/config.php';
  require_once BUSINESS_DIR . 'authorize_net_request.php';

  $request = new AuthorizeNetRequest(AUTHORIZE_NET_URL);

  // Auth
  $transaction =
    array ('x_invoice_num' => '99999', // Invoice number
           'x_amount'      => '45.99', // Amount
           'x_card_num'    => '4007000000027', // Credit card number
           'x_exp_date'    => '1209', // Expiration date
           'x_method'      => 'CC', // Payment method 
           'x_type'        => 'AUTH_ONLY'); // Transaction type

  $request->SetRequest($transaction);
  $auth_only_response = $request->GetResponse();

  $_SESSION['auth_only_response'] = $auth_only_response;

  $auth_only_response = explode('|', $auth_only_response);
 
  // Read the transaction ID, which will be necessary for taking the payment
  $ref_trans_id = $auth_only_response[6];

  // Capture
  $transaction =
    array ('x_ref_trans_id' => $ref_trans_id, // Transaction id
           'x_method'       => 'CC', // Payment method 
           'x_type'         => 'PRIOR_AUTH_CAPTURE'); // Transaction type

  $request->SetRequest($transaction);
  $prior_auth_capture_response = $request->GetResponse();

  $_SESSION['prior_auth_capture_response'] = $prior_auth_capture_response;
}
else
{
  switch ($_GET['step'])
  {
    case 1:
      print $_SESSION['auth_only_response'];

      break;
    case 2:
      print $_SESSION['prior_auth_capture_response'];

      break;
  }

  exit();
}
?>
<frameset cols="50%, 50%">
  <frame src="test_authorize_net.php?step=1">
  <frame src="test_authorize_net.php?step=2">
</frameset>
