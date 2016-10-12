<?php
// Tell the browser it is going to receive an XML document.
header('Content-type: text/xml');

/* DON'T FORGET to replace the string '[Your Access Key ID]' with your 
   Access Key ID in the following line */
$url = 'http://webservices.amazon.com/onca/xml?Service=AWSECommerceService' .
       '&AWSAccessKeyId=[Your Access Key ID]' .
       '&Operation=ItemSearch' .
       '&Keywords=postal+t-shirt' .
       '&SearchIndex=Apparel' .
       '&ResponseGroup=Request,Medium,VariationSummary';

echo file_get_contents($url);
?>