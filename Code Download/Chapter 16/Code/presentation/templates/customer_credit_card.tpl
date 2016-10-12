{* customer_credit_card.tpl *}
{load_presentation_object filename="customer_credit_card" assign="obj"}
<form method="post" action="{$obj->mLinkToCreditCardDetails}">
  <h2>Please enter your credit card details:</h2>
  <table class="customer-table">
    <tr>
      <td>Card Holder:</td>
      <td>
        <input type="text" name="cardHolder" size="32"
         value="{$obj->mPlainCreditCard.card_holder}" />
        {if $obj->mCardHolderError}
        <p class="error">You must enter a card holder.</p>
        {/if}
      </td>
    </tr>
    <tr>
      <td>Card Number (digits only):</td>
      <td>
        <input type="text" name="cardNumber" size="32"
         value="{$obj->mPlainCreditCard.card_number}" />
        {if $obj->mCardNumberError}
        <p class="error">You must enter a card number.</p>
        {/if}
      </td>
    </tr>
    <tr>
      <td>Expiry Date (MM/YY):</td>
      <td>
        <input type="text" name="expDate" size="32"
         value="{$obj->mPlainCreditCard.expiry_date}" />
        {if $obj->mExpDateError}
        <p class="error">You must enter an expiry date</p>
        {/if}
      </td>
    </tr>
    <tr>
      <td>Issue Date (MM/YY if applicable):</td>
      <td>
        <input type="text" name="issueDate" size="32"
         value="{$obj->mPlainCreditCard.issue_date}" />
      </td>
    </tr>
    <tr>
      <td>Issue Number (if applicable):</td>
      <td>
        <input type="text" name="issueNumber" size="32"
         value="{$obj->mPlainCreditCard.issue_number}" />
      </td>
    </tr>
    <tr>
      <td>Card Type:</td>
      <td>
        <select name="cardType">
          {html_options options=$obj->mCardTypes
           selected=$obj->mPlainCreditCard.card_type}
        </select>
        {if $obj->mCardTypesError}
        <p class="error">You must enter a card type.</p>
        {/if}
      </td>
    </tr>
  </table>
  <input type="submit" name="sended" value="Confirm" /> |
  <a href="{$obj->mLinkToCancelPage}">Cancel</a>
</form>
