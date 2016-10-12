{* admin_attributes.tpl *}
{load_presentation_object filename="admin_attributes" assign="obj"}
<form method="post"
 action="{$obj->mLinkToAttributesAdmin}">
  <h3>Edit the TShirtShop product attributes:</h3>
{if $obj->mErrorMessage}<p class="error">{$obj->mErrorMessage}</p>{/if}
{if $obj->mAttributesCount eq 0}
  <p class="no-items-found">
    There are no products attributes in your database!
  </p>
{else}
  <table class="tss-table">
    <tr>
      <th>Attribute Name</th>
      <th width="240">&nbsp;</th>
    </tr>
  {section name=i loop=$obj->mAttributes}
    {if $obj->mEditItem == $obj->mAttributes[i].attribute_id}
    <tr>
      <td>
        <input type="text" name="name"
         value="{$obj->mAttributes[i].name}" size="30" />
      </td>
      <td>
        <input type="submit"
         name="submit_edit_attr_val_{$obj->mAttributes[i].attribute_id}"
         value="Edit Attribute Values" />
        <input type="submit"
         name="submit_update_attr_{$obj->mAttributes[i].attribute_id}"
         value="Update" />
        <input type="submit" name="cancel" value="Cancel" />
        <input type="submit"
         name="submit_delete_attr_{$obj->mAttributes[i].attribute_id}"
         value="Delete" />
      </td>
    </tr>
    {else}
    <tr>
      <td>{$obj->mAttributes[i].name}</td>
      <td>
        <input type="submit"
         name="submit_edit_val_{$obj->mAttributes[i].attribute_id}"
         value="Edit Attribute Values" />
        <input type="submit"
         name="submit_edit_attr_{$obj->mAttributes[i].attribute_id}"
         value="Edit" />
        <input type="submit"
         name="submit_delete_attr_{$obj->mAttributes[i].attribute_id}"
         value="Delete" />
      </td>
    </tr>
    {/if}
  {/section}
  </table>
{/if}
  <h3>Add new attribute:</h3>
  <p>
    <input type="text" name="attribute_name" value="[name]" size="30" />
    <input type="submit" name="submit_add_attr_0" value="Add" />
  </p>
</form>
