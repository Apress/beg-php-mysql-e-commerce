{* department.tpl *}
{load_presentation_object filename="department" assign="obj"}
<h1 class="title">{$obj->mName}</h1>
<p class="description">{$obj->mDescription}</p>
{if $obj->mShowEditButton}
<form action="{$obj->mEditActionTarget}" method="post" class="edit-form">
  <input type="submit" name="submit_{$obj->mEditAction}"
   value="{$obj->mEditButtonCaption}" />
</form>
{/if}
{include file="products_list.tpl"}
