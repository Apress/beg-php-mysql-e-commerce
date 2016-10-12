<?php
class PasswordHasher
{
  public static function Hash($password, $withPrefix = true)
  {
    if ($withPrefix)
      $hashed_password = sha1(HASH_PREFIX . $password);
    else
      $hashed_password = sha1($password);

    return $hashed_password;
  }
}
?>
