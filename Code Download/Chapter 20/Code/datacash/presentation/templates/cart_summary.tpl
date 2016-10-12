{* cart_summary.tpl *}
{load_presentation_object filename="cart_summary" assign="obj"}
{* Start cart summary *}
<div class="box" id="cart-summary">
  <p class="box-title">Cart Summary</p>
  <div id="updating">Updating...</div>
{if $obj->mEmptyCart}
  <p class="empty-cart">Your shopping cart is empty!</p>
{else}
  <table class="cart-summary">
    <tbody>
  {section name=i loop=$obj->mItems}
      <tr>
        <td width="30" valign="top" align="right">
          {$obj->mItems[i].quantity} x 
        </td>
        <td>
          {$obj->mItems[i].name} ({$obj->mItems[i].attributes})
        </td>
      </tr>
  {/section}
      <tr>
        <td colspan="2" class="cart-summary-subtotal">
          <span class="price">${$obj->mTotalAmount}</span>
          <span>
            [ <a href="{$obj->mLinkToCartDetails}">View details</a> ]
          </span>
        </td>
      </tr>
    </tbody>
  </table>
{/if}
</div>
{* End cart summary *}
