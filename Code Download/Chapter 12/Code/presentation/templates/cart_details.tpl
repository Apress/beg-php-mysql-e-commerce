{* cart_details.tpl *}
{load_presentation_object filename="cart_details" assign="obj"}
{if $obj->mIsCartNowEmpty eq 1}
<h3>Your shopping cart is empty!</h3>
{else}
<h3>These are the products in your shopping cart:</h3>
<form class="cart-form" method="post" action="{$obj->mUpdateCartTarget}">
  <table class="tss-table">
    <tr>
      <th>Product Name</th>
      <th>Price</th>
      <th>Quantity</th>
      <th>Subtotal</th>
      <th>&nbsp;</th>
    </tr>
  {section name=i loop=$obj->mCartProducts}
    <tr>
      <td>
        <input name="itemId[]" type="hidden"
         value="{$obj->mCartProducts[i].item_id}" />
        {$obj->mCartProducts[i].name}
        ({$obj->mCartProducts[i].attributes})
      </td>
      <td>${$obj->mCartProducts[i].price}</td>
      <td>
        <input type="text" name="quantity[]" size="5"
         value="{$obj->mCartProducts[i].quantity}" />
      </td>
      <td>${$obj->mCartProducts[i].subtotal}</td>
      <td>
        <a href="{$obj->mCartProducts[i].save}">Save for later</a>
        <a href="{$obj->mCartProducts[i].remove}">Remove</a>
      </td>
    </tr>
  {/section}
  </table>
  <table class="cart-subtotal">
    <tr>
      <td>
        <p>
          Total amount:&nbsp;
          <font class="price">${$obj->mTotalAmount}</font>
        </p>
      </td>
      <td align="right">
        <input type="submit" name="update" value="Update" />
      </td>
    </tr>
  </table>
</form>
{/if}
{if ($obj->mIsCartLaterEmpty eq 0)}
<h3>Saved products to buy later:</h3>
<table class="tss-table">
  <tr>
    <th>Product Name</th>
    <th>Price</th>
    <th>&nbsp;</th>
  </tr>
  {section name=j loop=$obj->mSavedCartProducts}
  <tr>
    <td>
      {$obj->mSavedCartProducts[j].name}
      ({$obj->mSavedCartProducts[j].attributes})
    </td>
    <td>
      ${$obj->mSavedCartProducts[j].price}
    </td>
    <td>
        <a href="{$obj->mSavedCartProducts[j].move}">Move to cart</a>
        <a href="{$obj->mSavedCartProducts[j].remove}">Remove</a>
    </td>
  </tr>
  {/section}
</table>
{/if}
{if $obj->mLinkToContinueShopping}
<p><a href="{$obj->mLinkToContinueShopping}">Continue Shopping </a></p>
{/if}
