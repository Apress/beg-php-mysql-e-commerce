<?php
class DataCashRequest
{
  // DataCash Server URL
  private $_mUrl;

  // Will hold the current XML document to be sent to DataCash
  private $_mXml;

  // Constructor initializes the class with URL of DataCash
  public function __construct($url)
  {
    // Datacash URL
    $this->_mUrl = $url;
  }

  /* Compose the XML structure for the pre-authentication
     request to DataCash */
  public function MakeXmlPre($dataCashClient, $dataCashPassword,
                             $merchantReference, $amount, $currency,
                             $cardNumber, $expiryDate,
                             $startDate = '', $issueNumber = '')
  {
    $this->_mXml =
      "<?xml version=\"1.0\" encoding=\"UTF-8\"\x3F>
       <Request>
         <Authentication>
           <password>$dataCashPassword</password>
           <client>$dataCashClient</client>
         </Authentication>
         <Transaction>
           <TxnDetails>
             <merchantreference>$merchantReference</merchantreference>
             <amount currency=\"$currency\">$amount</amount>
           </TxnDetails>
           <CardTxn>
             <method>pre</method>
             <Card>
               <pan>$cardNumber</pan>
               <expirydate>$expiryDate</expirydate>
               <startdate>$startDate</startdate>
               <issuenumber>$issueNumber</issuenumber>
             </Card>
           </CardTxn>
         </Transaction>
       </Request>";
  }

  // Compose the XML structure for the fulfillment request to DataCash
  public function MakeXmlFulfill($dataCashClient, $dataCashPassword,
                                 $authCode, $reference)
  {
    $this->_mXml =
      "<?xml version=\"1.0\" encoding=\"UTF-8\"\x3F>
       <Request>
         <Authentication>
           <password>$dataCashPassword</password>
           <client>$dataCashClient</client>
         </Authentication>
         <Transaction>
           <HistoricTxn>
             <reference>$reference</reference>
             <authcode>$authCode</authcode>
             <method>fulfill</method>
           </HistoricTxn>
         </Transaction>
       </Request>";
  }

  // Get the current XML
  public function GetRequest()
  {
    return $this->_mXml;
  }

  // Send an HTTP POST request to DataCash using CURL
  public function GetResponse()
  {
    // Initialize a CURL session
    $ch = curl_init();

    // Prepare for an HTTP POST request
    curl_setopt($ch, CURLOPT_POST, 1);

    // Prepare the XML document to be POSTed
    curl_setopt($ch, CURLOPT_POSTFIELDS, $this->_mXml);

    // Set the URL where we want to POST our XML structure
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
