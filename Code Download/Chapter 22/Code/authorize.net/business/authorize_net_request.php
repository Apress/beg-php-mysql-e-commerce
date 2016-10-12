<?php
class AuthorizeNetRequest
{
  // Authorize Server URL
  private $_mUrl;

  // Will hold the current request to be sent to Authorize.net
  private $_mRequest;

  // Constructor initializes the class with URL of Authorize.net
  public function __construct($url)
  {
    // Authorize.net URL
    $this->_mUrl = $url;
  }

  public function SetRequest($request)
  {
    $this->_mRequest = '';

    $request_init =
      array ('x_login'          => AUTHORIZE_NET_LOGIN_ID,
             'x_tran_key'       => AUTHORIZE_NET_TRANSACTION_KEY,
             'x_version'        => '3.1',
             'x_test_request'   => AUTHORIZE_NET_TEST_REQUEST,
             'x_delim_data'     => 'TRUE',
             'x_delim_char'     => '|',
             'x_relay_response' => 'FALSE');

    $request = array_merge($request_init, $request);

    foreach($request as $key => $value )
      $this->_mRequest .= $key . '=' . urlencode($value) . '&';
  }

  // Send an HTTP POST request to Authorize.net using CURL
  public function GetResponse()
  {
    // Initialize a CURL session
    $ch = curl_init();

    // Prepare for an HTTP POST request
    curl_setopt($ch, CURLOPT_POST, 1);

    // Prepare the request to be POSTed
    curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim($this->_mRequest, '& '));

    // Set the URL where we want to POST our data
    curl_setopt($ch, CURLOPT_URL, $this->_mUrl);

    /* Do not verify the Common name of the peer certificate in the SSL
       handshake */
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

    // Prevent CURL from verifying the peer's certificate
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    /* We want CURL to directly return the transfer instead of
       printing it */
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // Perform a CURL session
    $result = curl_exec($ch);

    // Close a CURL session
    curl_close ($ch);

    // Return the response
    return $result;
  }
}
?>
