{* admin_order_details.tpl *}
{load_presentation_object filename="admin_order_details" assign="obj"}
<form method="get" action="{$obj->mLinkToAdmin}">
  <h3>
    Editing details for order ID:
    {$obj->mOrderInfo.order_id} [
    <a href="{$obj->mLinkToOrdersAdmin}">back to admin orders...</a> ]
  </h3>
  <input type="hidden" name="Page" value="OrderDetails" />
  <input type="hidden" name="OrderId"
   value="{$obj->mOrderInfo.order_id}" />
  <table class="borderless-table">
    <tr>
      <td class="bold-text">Total Amount: </td>
      <td class="price">
        ${$obj->mOrderInfo.total_amount}
      </td>
    </tr>
    <tr>
      <td class="bold-text">Date Created: </td>
      <td>
        {$obj->mOrderInfo.created_on|date_format:"%Y-%m-%d %T"}
      </td>
    </tr>
    <tr>
      <td class="bold-text">Date Shipped: </td>
      <td>
        {$obj->mOrderInfo.shipped_on|date_format:"%Y-%m-%d %T"}
      </td>
    </tr>
    <tr>
      <td class="bold-text">Status: </td>
      <td>
        <select name="status"
         {if ! $obj->mEditEnabled} disabled="disabled" {/if} >
          {html_options options=$obj->mOrderStatusOptions
           selected=$obj->mOrderInfo.status}
        </select>
      </td>
    </tr>
    <tr>
      <td class="bold-text">Comments: </td>
      <td>
        <input name="comments" type="text" size="50"
         value="{$obj->mOrderInfo.comments}"
         {if ! $obj->mEditEnabled} disabled="disabled" {/if} />
      <td>
    </tr>
    <tr>
      <td class="bold-text">Customer Name: </td>
      <td>
        <input name="customerName" type="text" size="50"
         value="{$obj->mOrderInfo.customer_name}"
         {if ! $obj->mEditEnabled} disabled="disabled" {/if} />
      <td>
    </tr>
    <tr>
      <td class="bold-text">Shipping Address: </td>
      <td>
        <input name="shippingAddress" type="text" size="50"
         value="{$obj->mOrderInfo.shipping_address}"
         {if ! $obj->mEditEnabled} disabled="disabled" {/if} />
      </td>
    </tr>
    <tr>
      <td class="bold-text">Customer Email: </td>
      <td>
        <input name="customerEmail" type="text" size="50"
         value="{$obj->mOrderInfo.customer_email}"
         {if ! $obj->mEditEnabled} disabled="disabled" {/if} />
      </td>
    </tr>
  </table>
  <p>
    <input type="submit" name="submitEdit" value="Edit"
     {if $obj->mEditEnabled} disabled="disabled" {/if} />
    <input type="submit" name="submitUpdate" value="Update"
     {if ! $obj->mEditEnabled} disabled="disabled" {/if} />
    <input type="submit" name="submitCancel" value="Cancel"
     {if ! $obj->mEditEnabled} disabled="disabled" {/if} />
  </p>
  <h3>Order contains these products:</h3>
  <table class="tss-table">
    <tr>
      <th>Product ID</th>
      <th>Product Name</th>
      <th>Quantity</th>
      <th>Unit Cost</th>
      <th>Subtotal</th>
    </tr>
  {section name=i loop=$obj->mOrderDetails}
    <tr>
      <td>{$obj->mOrderDetails[i].product_id}</td>
      <td>
        {$obj->mOrderDetails[i].product_name}
        ({$obj->mOrderDetails[i].attributes})
      </td>
      <td>{$obj->mOrderDetails[i].quantity}</td>
      <td>${$obj->mOrderDetails[i].unit_cost}</td>
      <td>${$obj->mOrderDetails[i].subtotal}</td>
    </tr>
  {/section}
  </table>
</form>
