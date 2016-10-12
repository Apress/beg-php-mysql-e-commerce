{* customer_details.tpl *}
{load_presentation_object filename="customer_details" assign="obj"}
<form method="post" action="{$obj->mLinkToAccountDetails}">
  <h2>Please enter your details:</h2>
  <table class="customer-table">
    <tr>
      <td>E-mail Address:</td>
      <td>
        <input type="text" name="email" value="{$obj->mEmail}"
         {if $obj->mEditMode}readonly="readonly"{/if} size="32" />
        {if $obj->mEmailAlreadyTaken}
        <p class="error">A user with that e-mail address already exists.</p>
        {/if}
        {if $obj->mEmailError}
        <p class="error">You must enter an e-mail address.</p>
        {/if}
      </td>
    </tr>
    <tr>
      <td>Name:</td>
      <td>
        <input type="text" name="name" value="{$obj->mName}" size="32" />
        {if $obj->mNameError}
        <p class="error">You must enter your name.</p>
        {/if}
      </td>
    </tr>
    <tr>
      <td>Password:</td>
      <td>
        <input type="password" name="password" size="32" />
        {if $obj->mPasswordError}
        <p class="error">You must enter a password.</p>
        {/if}
      </td>
    </tr>
    <tr>
      <td>Re-enter Password:</td>
      <td>
        <input type="password" name="passwordConfirm" size="32" />
        {if $obj->mPasswordConfirmError}
        <p class="error">You must re-enter your password.</p>
        {elseif $obj->mPasswordMatchError}
        <p class="error">You must re-enter the same password.</p>
        {/if}
      </td>
    </tr>
    {if $obj->mEditMode}
    <tr>
      <td>Day phone:</td>
      <td>
        <input type="text" name="dayPhone" value="{$obj->mDayPhone}"
         size="32" />
      </td>
    </tr>
    <tr>
      <td>Eve phone:</td>
      <td>
        <input type="text" name="evePhone" value="{$obj->mEvePhone}"
         size="32" />
      </td>
    </tr>
    <tr>
      <td>Mob phone:</td>
      <td>
        <input type="text" name="mobPhone" value="{$obj->mMobPhone}"
         size="32" />
      </td>
    </tr>
    {/if}
  </table>
  <input type="submit" name="sended" value="Confirm" /> |
  <a href="{$obj->mLinkToCancelPage}">Cancel</a>
</form>
