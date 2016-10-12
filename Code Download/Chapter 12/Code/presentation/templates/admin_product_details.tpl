{* admin_product_details.tpl *}
{load_presentation_object filename="admin_product_details" assign="obj"}
<form enctype="multipart/form-data" method="post"
 action="{$obj->mLinkToProductDetailsAdmin}">
  <h3>
    Editing product: ID #{$obj->mProduct.product_id} &mdash;
    {$obj->mProduct.name} [
    <a href="{$obj->mLinkToCategoryProductsAdmin}">
      back to products ...</a> ]
  </h3>
  {if $obj->mErrorMessage}<p class="error">{$obj->mErrorMessage}</p>{/if}
  <table class="borderless-table">
    <tbody>
      <tr>
        <td valign="top">
          <p class="bold-text">
            Product name:
          </p>
          <p>
            <input type="text" name="name"
            value="{$obj->mProduct.name}" size="30" />
          </p>
          <p class="bold-text">
            Product description:
          </p>
          <p>
            {strip}
            <textarea name="description" rows="3" cols="60">
              {$obj->mProduct.description}
            </textarea>
            {/strip}
          </p>
          <p class="bold-text">
            Product price:
          </p>
          <p>
            <input type="text" name="price"
             value="{$obj->mProduct.price}" size="5" />
          </p>
          <p class="bold-text">
            Product discounted price:
          </p>
          <p>
            <input type="text" name="discounted_price"
             value="{$obj->mProduct.discounted_price}" size="5" />
          </p>
          <p>
            <input type="submit" name="UpdateProductInfo"
             value="Update info" />
          </p>
        </td>
        <td valign="top">
          <p>
            <font class="bold-text">Product belongs to these categories:</font>
            {$obj->mProductCategoriesString}
          </p>
          <p class="bold-text">
            Remove this product from:
          </p>
          <p>
            {html_options name="TargetCategoryIdRemove"
             options=$obj->mRemoveFromCategories}
            <input type="submit" name="RemoveFromCategory" value="Remove"
             {if $obj->mRemoveFromCategoryButtonDisabled}
             disabled="disabled" {/if}/>
          </p>
          <p class="bold-text">
            Assign product to this category:
          </p>
          <p>
            {html_options name="TargetCategoryIdAssign"
             options=$obj->mAssignOrMoveTo}
            <input type="submit" name="Assign" value="Assign" />
          </p>
          <p class="bold-text">
            Move product to this category:
          </p>
          <p>
            {html_options name="TargetCategoryIdMove"
             options=$obj->mAssignOrMoveTo}
            <input type="submit" name="Move" value="Move" />
            <input type="submit" name="RemoveFromCatalog"
             value="Remove product from catalog"
             {if !$obj->mRemoveFromCategoryButtonDisabled}
             disabled="disabled" {/if}/>
          </p>
          {if $obj->mProductAttributes}
          <p class="bold-text">
            Product attributes:
          </p>
          <p>
            {html_options name="TargetAttributeValueIdRemove"
             options=$obj->mProductAttributes}
            <input type="submit" name="RemoveAttributeValue"
             value="Remove" />
          </p>
          {/if}
          {if $obj->mCatalogAttributes}
          <p class="bold-text">
            Assign attribute to product:
          </p>
          <p>
            {html_options name="TargetAttributeValueIdAssign"
             options=$obj->mCatalogAttributes}
            <input type="submit" name="AssignAttributeValue"
             value="Assign" />
          </p>
          {/if}
          <p class="bold-text">
            Set display option for this product:
          </p>
          <p>
            {html_options name="ProductDisplay"
             options=$obj->mProductDisplayOptions
             selected=$obj->mProduct.display}
            <input type="submit" name="SetProductDisplayOption" value="Set" />
          </p>
        </td>
      </tr>
    </tbody>
  </table>
  <p>
    <font class="bold-text">Image name:</font> {$obj->mProduct.image}
    <input name="ImageUpload" type="file" value="Upload" />
    <input type="submit" name="Upload" value="Upload" />
  </p>
  {if $obj->mProduct.image}
  <p>
    <img src="product_images/{$obj->mProduct.image}"
     border="0" alt="{$obj->mProduct.name} image" />
  </p>
  {/if}
  <p>
    <font class="bold-text">Image 2 name:</font> {$obj->mProduct.image_2}
    <input name="Image2Upload" type="file" value="Upload" />
    <input type="submit" name="Upload" value="Upload" />
  </p>
  {if $obj->mProduct.image_2}
  <p>
    <img src="product_images/{$obj->mProduct.image_2}"
     border="0" alt="{$obj->mProduct.name} image 2" />
  </p>
  {/if}
  <p>
    <font class="bold-text">Thumbnail name:</font> {$obj->mProduct.thumbnail}
    <input name="ThumbnailUpload" type="file" value="Upload" />
    <input type="submit" name="Upload" value="Upload" />
  </p>
  {if $obj->mProduct.thumbnail}
  <p>
    <img src="product_images/{$obj->mProduct.thumbnail}"
     border="0" alt="{$obj->mProduct.name} thumbnail" />
  </p>
  {/if}
</form>
