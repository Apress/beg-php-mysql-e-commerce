{* admin_login.tpl *}
{load_presentation_object filename="admin_login" assign="obj"}
<div class="login">
  <p class="login-title">TShirtShop Login</p>
  <form method="post" action="{$obj->mLinkToAdmin}">
    <p>
      Enter login information or go back to
      <a href="{$obj->mLinkToIndex}">storefront</a>.
    </p>
{if $obj->mLoginMessage neq ""}
    <p class="error">{$obj->mLoginMessage}</p>
{/if}
    <p>
      <label for="username">Username:</label>
      <input type="text" name="username" size="35" value="{$obj->mUsername}" />
    </p>
    <p>
      <label for="password">Password:</label>
      <input type="password" name="password" size="35" value="" />
    </p>
    <p>
      <input type="submit" name="submit" value="Login" />
    </p>
  </form>
</div>
