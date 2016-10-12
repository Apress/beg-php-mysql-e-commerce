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
      <td class="bold-text">Tax: </td>
      <td class="price">{$obj->mOrderInfo.tax_type} ${$obj->mTax}</td>
    </tr>
    <tr>
      <td class="bold-text">Shipping: </td>
      <td class="price">{$obj->mOrderInfo.shipping_type}</td>
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
      <td class="bold-text">Authorization Code: </td>
      <td>
        <input name="authCode" type="text" size="50"
         value="{$obj->mOrderInfo.auth_code}"
         {if ! $obj->mEditEnabled} disabled="disabled" {/if} />
      <td>
    </tr>
    <tr>
      <td class="bold-text">Reference Number: </td>
      <td>
        <input name="reference" type="text" size="50"
         value="{$obj->mOrderInfo.reference}"
         {if ! $obj->mEditEnabled} disabled="disabled" {/if} />
      <td>
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
      <td>{$obj->mCustomerInfo.name}</td>
    </tr>
    <tr>
      <td class="bold-text" valign="top">Shipping Address: </td>
      <td>
        {$obj->mCustomerInfo.address_1}<br />
        {if $obj->mCustomerInfo.address_2}
          {$obj->mCustomerInfo.address_2}<br />
        {/if}
        {$obj->mCustomerInfo.city}<br />
        {$obj->mCustomerInfo.region}<br />
        {$obj->mCustomerInfo.postal_code}<br />
        {$obj->mCustomerInfo.country}<br />
      </td>
    </tr>
    <tr>
      <td class="bold-text">Customer Email: </td>
      <td>{$obj->mCustomerInfo.email}</td>
    </tr>
  </table>
  <p>
    <input type="submit" name="submitEdit" value="Edit"
     {if $obj->mEditEnabled} disabled="disabled" {/if} />
    <input type="submit" name="submitUpdate" value="Update"
     {if ! $obj->mEditEnabled} disabled="disabled" {/if} />
    <input type="submit" name="submitCancel" value="Cancel"
     {if ! $obj->mEditEnabled} disabled="disabled" {/if} />
    {if $obj->mProcessButtonText}
    <input type="submit" name="submitProcessOrder"
     value="{$obj->mProcessButtonText}" />
    {/if}
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
  <h3>Order audit trail:</h3>
  <table class="tss-table">
    <tr>
      <th>Audit ID</th>
      <th>Created On</th>
      <th>Code</th>
      <th>Message</th>
    </tr>
  {section name=j loop=$obj->mAuditTrail}
    <tr>
      <td>{$obj->mAuditTrail[j].audit_id}</td>
      <td>{$obj->mAuditTrail[j].created_on}</td>
      <td>{$obj->mAuditTrail[j].code}</td>
      <td>{$obj->mAuditTrail[j].message}</td>
    </tr>
  {/section}
  </table>
</form>
