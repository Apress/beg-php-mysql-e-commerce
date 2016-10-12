{* customer_address.tpl *}
{load_presentation_object filename="customer_address" assign="obj"}
<form method="post" action="{$obj->mLinkToAddressDetails}">
  <h2>Please enter your address details:</h2>
  <table class="customer-table">
    <tr>
      <td>Address 1:</td>
      <td>
        <input type="text" name="address1" value="{$obj->mAddress1}"
         size="32" />
        {if $obj->mAddress1Error}
        <p class="error">You must enter an address.</p>
        {/if}
      </td>
    </tr>
    <tr>
      <td>Address 2:</td>
      <td>
        <input type="text" name="address2" value="{$obj->mAddress2}"
         size="32" />
      </td>
    </tr>
    <tr>
      <td>Town/City:</td>
      <td>
        <input type="text" name="city" value="{$obj->mCity}"
         size="32" />
        {if $obj->mCityError}
        <p class="error">You must enter a city.</p>
        {/if}
      </td>
    </tr>
    <tr>
      <td>Region/State:</td>
      <td>
        <input type="text" name="region" value="{$obj->mRegion}"
         size="32" />
        {if $obj->mRegionError}
        <p class="error">You must enter a region/state.</p>
        {/if}
      </td>
    </tr>
    <tr>
      <td>Postal Code/ZIP:</td>
      <td>
        <input type="text" name="postalCode" value="{$obj->mPostalCode}"
         size="32" />
        {if $obj->mPostalCodeError}
        <p class="error">You must enter a postal code/ZIP.</p>
        {/if}
      </td>
    </tr>
    <tr>
      <td>Country:</td>
      <td>
        <input type="text" name="country" value="{$obj->mCountry}"
         size="32" />
        {if $obj->mCountryError}
        <p class="error">You must enter a country.</p>
        {/if}
      </td>
    </tr>
    <tr>
      <td>Shipping region:</td>
      <td>
        <select name="shippingRegion">
          {html_options options=$obj->mShippingRegions
           selected=$obj->mShippingRegion}
        </select>
        {if $obj->mShippingRegionError}
        <p class="error">You must select a shipping region.</p>
        {/if}
      </td>
    </tr>
  </table>
  <input type="submit" name="sended" value="Confirm" /> |
  <a href="{$obj->mLinkToCancelPage}">Cancel</a>
</form>
