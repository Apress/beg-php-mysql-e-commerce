{* admin_departments.tpl *}
{load_presentation_object filename="admin_departments" assign="obj"}
<form method="post"
 action="{$obj->mLinkToDepartmentsAdmin}">
  <h3>Edit the departments of TShirtShop:</h3>
{if $obj->mErrorMessage}<p class="error">{$obj->mErrorMessage}</p>{/if}
{if $obj->mDepartmentsCount eq 0}
  <p class="no-items-found">There are no departments in your database!</p>
{else}
  <table class="tss-table">
    <tr>
      <th width="200">Department Name</th>
      <th>Department Description</th>
      <th width="240">&nbsp;</th>
    </tr>
  {section name=i loop=$obj->mDepartments}
    {if $obj->mEditItem == $obj->mDepartments[i].department_id}
    <tr>
      <td>
        <input type="text" name="name"
         value="{$obj->mDepartments[i].name}" size="30" />
      </td>
      <td>
      {strip}
        <textarea name="description" rows="3" cols="60">
          {$obj->mDepartments[i].description}
        </textarea>
      {/strip}
      </td>
      <td>
        <input type="submit"
         name="submit_edit_cat_{$obj->mDepartments[i].department_id}"
         value="Edit Categories" />
        <input type="submit"
         name="submit_update_dept_{$obj->mDepartments[i].department_id}"
         value="Update" />
        <input type="submit" name="cancel" value="Cancel" />
        <input type="submit"
         name="submit_delete_dept_{$obj->mDepartments[i].department_id}"
         value="Delete" />
      </td>
    </tr>
    {else}
    <tr>
      <td>{$obj->mDepartments[i].name}</td>
      <td>{$obj->mDepartments[i].description}</td>
      <td>
        <input type="submit"
         name="submit_edit_cat_{$obj->mDepartments[i].department_id}"
         value="Edit Categories" />
        <input type="submit"
         name="submit_edit_dept_{$obj->mDepartments[i].department_id}"
         value="Edit" />
        <input type="submit"
         name="submit_delete_dept_{$obj->mDepartments[i].department_id}"
         value="Delete" />
      </td>
    </tr>
    {/if}
  {/section}
  </table>
{/if}
  <h3>Add new department:</h3>
  <p>
    <input type="text" name="department_name" value="[name]" size="30" />
    <input type="text" name="department_description" value="[description]"
     size="60" />
    <input type="submit" name="submit_add_dept_0" value="Add" />
  </p>
</form>
