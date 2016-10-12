<?php
if (isset ($_GET['to_be_hashed']))
{
  require_once 'include/config.php';
  require_once BUSINESS_DIR . 'password_hasher.php';

  $original_string = $_GET['to_be_hashed'];

  echo 'The hash of "' . $original_string . '" is ' .
       PasswordHasher::Hash($original_string, false);

  echo '<br />';

  echo '... and the hash of "' . HASH_PREFIX . $original_string .
       '" (secret prefix concateneted with password) is ' .
       PasswordHasher::Hash($original_string, true);
}
?>

<br /><br />
<form action="test_hasher.php">
  Write your password:
  <input type="text" name="to_be_hashed" /><br />
  <input type="submit" value="Hash it" />
</form>
