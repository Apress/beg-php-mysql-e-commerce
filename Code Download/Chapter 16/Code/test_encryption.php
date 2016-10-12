<?php
if (isset ($_GET['my_string']))
{
  require_once 'include/config.php';
  require_once BUSINESS_DIR . 'symmetric_crypt.php';

  $string = $_GET['my_string'];

  echo 'The string is:<br />' . $string . '<br /><br />';

  $encrypted_string = SymmetricCrypt::Encrypt($string);

  echo 'Encrypted string: <br />' . $encrypted_string . '<br /><br />';

  $decrypted_string = SymmetricCrypt::Decrypt($encrypted_string);

  echo 'Decrypted string:<br />' . $decrypted_string;
}
?>

<br /><br />
<form action="test_encryption.php">
  Enter string to encrypt:
  <input type="text" name="my_string" /><br />
  <input type="submit" value="Encrypt" />
</form>
