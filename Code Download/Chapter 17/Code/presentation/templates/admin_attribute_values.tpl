{* admin_attribute_values.tpl *}
{load_presentation_object filename="admin_attribute_values" assign="obj"}
<form method="post"
 action="{$obj->mLinkToAttributeValuesAdmin}">
  <h3>
    Editing values for attribute: {$obj->mAttributeName} [
    <a href="{$obj->mLinkToAttributesAdmin}">back to attributes ...</a> ]
  </h3>
{if $obj->mErrorMessage}<p class="error">{$obj->mErrorMessage}</p>{/if}
{if $obj->mAttributeValuesCount eq 0}
  <p class="no-items-found">There are no values for this attribute!</p>
{else}
  <table class="tss-table">
    <tr>
      <th>Attribute Value</th>
      <th width="170">&nbsp;</th>
    </tr>
  {section name=i loop=$obj->mAttributeValues}
    {if $obj->mEditItem == $obj->mAttributeValues[i].attribute_value_id}
    <tr>
      <td>
        <input type="text" name="value"
         value="{$obj->mAttributeValues[i].value}" size="30" />
      </td>
      <td>
        <input type="submit"
         name="submit_update_val_{$obj->mAttributeValues[i].attribute_value_id}"
         value="Update" />
        <input type="submit" name="cancel" value="Cancel" />
        <input type="submit"
         name="submit_delete_val_{$obj->mAttributeValues[i].attribute_value_id}"
         value="Delete" />
      </td>
    </tr>
    {else}
    <tr>
      <td>{$obj->mAttributeValues[i].value}</td>
      <td>
        <input type="submit"
         name="submit_edit_val_{$obj->mAttributeValues[i].attribute_value_id}"
         value="Edit" />
        <input type="submit"
         name="submit_delete_val_{$obj->mAttributeValues[i].attribute_value_id}"
         value="Delete" />
      </td>
    </tr>
    {/if}
  {/section}
  </table>
{/if}
  <h3>Add new attribute value:</h3>
  <input type="text" name="attribute_value" value="[value]" size="30" />
  <input type="submit" name="submit_add_val_0" value="Add" />
</form>
