{* admin_products.tpl *}
{load_presentation_object filename="admin_products" assign="obj"}
<form method="post"
 action="{$obj->mLinkToCategoryProductsAdmin}">
  <h3>
    Editing products for category: {$obj->mCategoryName} [
    <a href="{$obj->mLinkToDepartmentCategoriesAdmin}">
      back to categories ...</a> ]
  </h3>
{if $obj->mErrorMessage}<p class="error">{$obj->mErrorMessage}</p>{/if}
{if $obj->mProductsCount eq 0}
  <p class="no-items-found">There are no products in this category!</p>
{else}
  <table class="tss-table">
    <tr>
      <th>Name</th>
      <th>Description</th>
      <th>Price</th>
      <th>Discounted Price</th>
      <th width="80">&nbsp;</th>
    </tr>
  {section name=i loop=$obj->mProducts}
    <tr>
      <td>{$obj->mProducts[i].name}</td>
      <td>{$obj->mProducts[i].description}</td>
      <td>{$obj->mProducts[i].price}</td>
      <td>{$obj->mProducts[i].discounted_price}</td>
      <td>
        <input type="submit"
         name="submit_edit_prod_{$obj->mProducts[i].product_id}"
         value="Edit" />
      </td>
    </tr>
  {/section}
  </table>
{/if}
  <h3>Add new product:</h3>
  <input type="text" name="product_name" value="[name]" size="30" />
  <input type="text" name="product_description" value="[description]"
   size="60" />
  <input type="text" name="product_price" value="[price]" size="10" />
  <input type="submit" name="submit_add_prod_0" value="Add" />
</form>
