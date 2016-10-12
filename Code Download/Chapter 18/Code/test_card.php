<?php
require_once 'include/config.php';
require_once BUSINESS_DIR . 'symmetric_crypt.php';
require_once BUSINESS_DIR . 'secure_card.php';

$card_holder = 'John Doe';
$card_number = '1234567890123456';
$expiry_date = '01/09';
$issue_date = '01/06'; 
$issue_number = 100;
$card_type = 'Mastercard';

echo '<br />Credit card data:<br />' .
     $card_holder . ', ' . $card_number . ', ' .
     $issue_date . ', ' . $expiry_date . ', ' .
     $issue_number . ', ' . $card_type . '<br />';

$credit_card = new SecureCard();

try
{
  $credit_card->LoadPlainDataAndEncrypt($card_holder, $card_number,
                  $issue_date, $expiry_date, $issue_number, $card_type);

  $encrypted_data = $credit_card->EncryptedData;
}
catch(Exception $e)
{
  echo '<font color="red">Exception: ' . $e->getMessage() . '</font>';

  exit();
}

echo '<br />Encrypted data:<br />' . $encrypted_data . '<br />';

$our_card = new SecureCard();

try
{
  $our_card->LoadEncryptedDataAndDecrypt($encrypted_data);

  echo '<br/>Decrypted data:<br/>' .
       $our_card->CardHolder . ', ' . $our_card->CardNumber . ', ' .
       $our_card->IssueDate . ', ' . $our_card->ExpiryDate . ', ' .
       $our_card->IssueNumber . ', ' . $our_card->CardType;
}
catch(Exception $e)
{
  echo '<font color="red">Exception: ' . $e->getMessage() . '</font>';

  exit();
}
?>
