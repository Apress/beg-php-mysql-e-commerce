<?php
try
{
  // Initialize SOAP client object
  $client = new SoapClient(
    'http://webservices.amazon.com/AWSECommerceService/AWSECommerceService.wsdl');

  /* DON'T FORGET to replace the string '[Your Access Key ID]' with your 
     subscription ID in the following line */
  $request = array ('Service' => 'AWSECommerceService',
                    'AWSAccessKeyId' => '[Your Access Key ID]',
                    'Request' => array ('Operation' => 'ItemSearchRequest',
                                        'Keywords' => 'postal+t-shirt',
                                        'SearchIndex' => 'Apparel',
                                        'ResponseGroup' =>
                                          array ('Request',
                                                 'Medium',
                                                 'VariationSummary')));

  $result = $client->ItemSearch($request);

  echo '<pre>';
  print_r($result);
  echo '</pre>';
}
catch (SoapFault $fault)
{
  trigger_error('SOAP Fault: (faultcode: ' . $fault->faultcode . ', ' .
                'faultstring: ' . $fault->faultstring . ')', E_USER_ERROR);
}
?>