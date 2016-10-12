{* admin_categories.tpl *}
{load_presentation_object filename="admin_categories" assign="obj"}
<form method="post"
 action="{$obj->mLinkToDepartmentCategoriesAdmin}">
  <h3>
    Editing categories for department: {$obj->mDepartmentName} [
    <a href="{$obj->mLinkToDepartmentsAdmin}">back to departments ...</a> ]
  </h3>
{if $obj->mErrorMessage}<p class="error">{$obj->mErrorMessage}</p>{/if}
{if $obj->mCategoriesCount eq 0}
  <p class="no-items-found">There are no categories in this department!</p>
{else}
  <table class="tss-table">
    <tr>
      <th width="200">Category Name</th>
      <th>Category Description</th>
      <th width="240">&nbsp;</th>
    </tr>
  {section name=i loop=$obj->mCategories}
    {if $obj->mEditItem == $obj->mCategories[i].category_id}
    <tr>
      <td>
        <input type="text" name="name"
         value="{$obj->mCategories[i].name}" size="30" />
      </td>
      <td>
        {strip}
        <textarea name="description" rows="3" cols="60">
          {$obj->mCategories[i].description}
        </textarea>
        {/strip}
      </td>
      <td>
        <input type="submit"
         name="submit_edit_prod_{$obj->mCategories[i].category_id}"
         value="Edit Products" />
        <input type="submit"
         name="submit_update_cat_{$obj->mCategories[i].category_id}"
         value="Update" />
        <input type="submit" name="cancel" value="Cancel" />
        <input type="submit"
         name="submit_delete_cat_{$obj->mCategories[i].category_id}"
         value="Delete" />
      </td>
    </tr>
    {else}
    <tr>
      <td>{$obj->mCategories[i].name}</td>
      <td>{$obj->mCategories[i].description}</td>
      <td>
        <input type="submit"
         name="submit_edit_prod_{$obj->mCategories[i].category_id}"
         value="Edit Products" />
        <input type="submit"
         name="submit_edit_cat_{$obj->mCategories[i].category_id}"
         value="Edit" />
        <input type="submit"
         name="submit_delete_cat_{$obj->mCategories[i].category_id}"
         value="Delete" />
      </td>
    </tr>
    {/if}
  {/section}
  </table>
{/if}
  <h3>Add new category:</h3>
  <input type="text" name="category_name" value="[name]" size="30" />
  <input type="text" name="category_description" value="[description]"
   size="60" />
  <input type="submit" name="submit_add_cat_0" value="Add" />
</form>
