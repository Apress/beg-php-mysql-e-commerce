<?php
session_start();

if (empty ($_GET['step']))
{
  require_once 'include/config.php';
  require_once BUSINESS_DIR . 'datacash_request.php';

  $request = new DataCashRequest(DATACASH_URL);
  $request->MakeXmlPre(DATACASH_CLIENT, DATACASH_PASSWORD,
                       8880000 + rand(0, 10000), 49.99, 'GBP',
                       '3528000000000007', '11/09');

  $request_xml = $request->GetRequest();
  $_SESSION['pre_request'] = $request_xml;

  $response_xml = $request->GetResponse();
  $_SESSION['pre_response'] = $response_xml;

  $xml = simplexml_load_string($response_xml);
  $request->MakeXmlFulfill(DATACASH_CLIENT, DATACASH_PASSWORD,
                           $xml->merchantreference,
                           $xml->datacash_reference);

  $response_xml = $request->GetResponse();
  $_SESSION['fulfill_response'] = $response_xml;
}
else
{
  header('Content-type: text/xml');

  switch ($_GET['step'])
  {
    case 1:
      print $_SESSION['pre_request'];

      break;
    case 2:
      print $_SESSION['pre_response'];

      break;
    case 3:
      print $_SESSION['fulfill_response'];

      break;
  }
  exit();
}
?>
<frameset cols="33%, 33%, 33%">
  <frame src="test_datacash.php?step=1">
  <frame src="test_datacash.php?step=2">
  <frame src="test_datacash.php?step=3">
</frameset>
