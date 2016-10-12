{* admin_carts.tpl *}
{load_presentation_object filename="admin_carts" assign="obj"}
<form action="{$obj->mLinkToCartsAdmin}" method="post">
  <h3>Admin users&#039; shopping carts:</h3>
  {if $obj->mMessage}<p>{$obj->mMessage}</p>{/if}
  <p>
    Select carts:
    {html_options name="days" options=$obj->mDaysOptions
                  selected=$obj->mSelectedDaysNumber}
    <input type="submit" name="submit_count" value="Count Old Shopping Carts" />
    <input type="submit" name="submit_delete"
     value="Delete Old Shopping Carts" />
  </p>
</form>
