{* categories_list.tpl *}
{load_presentation_object filename="categories_list" assign="obj"}
{* Start categories list *}
<div class="box">
  <p class="box-title">Choose a Category</p>
  <ul>
  {section name=i loop=$obj->mCategories}
    {assign var=selected value=""}
    {if ($obj->mSelectedCategory == $obj->mCategories[i].category_id)}
      {assign var=selected value="class=\"selected\""}
    {/if}
    <li>
      <a {$selected} href="{$obj->mCategories[i].link_to_category}">
        {$obj->mCategories[i].name}
      </a>
    </li>
  {/section}
  </ul>
</div>
{* End categories list *}
