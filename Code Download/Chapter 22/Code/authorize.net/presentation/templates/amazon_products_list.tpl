{* amazon_products_list.tpl *}
{load_presentation_object filename="amazon_products_list" assign="obj"}
<h1>{$obj->mDepartmentName}</h1>
<p class="description">{$obj->mDepartmentDescription}</p>
<table class="product-list">
  <tbody>
{section name=k loop=$obj->mProducts}
  {if $smarty.section.k.index % 2 == 0}
    <tr>
  {/if}
      <td valign="top">
        <h3 class="product-title">
          <a href="{$obj->mProducts[k].link_to_product}">
            {$obj->mProducts[k].item_name}
          </a>
          <br />
          by {$obj->mProducts[k].brand}
        </h3>
        <p>
          {if $obj->mProducts[k].image neq ""}
          <a href="{$obj->mProducts[k].link_to_product}">
            <img src="{$obj->mProducts[k].image}"
             alt="{$obj->mProducts[k].item_name}" width="120" />
          </a>
          {/if}
        </p>
        <p class="attributes">
          {if $obj->mProducts[k].price neq ""}
          Price: <font class="price">{$obj->mProducts[k].price}</font>
          {/if}
        </p>
        <p class="section">
          <a target="_blank" href="{$obj->mProducts[k].link_to_product}">
           Buy From Amazon
          </a>
        </p>
      </td>
  {if $smarty.section.k.index % 2 != 0 && !$smarty.section.k.first ||
      $smarty.section.k.last}
    </tr>
  {/if}
{/section}
  </tbody>
</table>