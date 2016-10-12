{* search_box.tpl *}
{load_presentation_object filename="search_box" assign="obj"}
{* Start search box *}
<div class="box">
  <p class="box-title">Search the Catalog</p>
  <form class="search_form" method="post" action="{$obj->mLinkToSearch}">
    <p>
      <input maxlength="100" id="search_string" name="search_string"
       value="{$obj->mSearchString}" size="19" />
      <input type="submit" value="Go!" /><br />
    </p>
    <p>
      <input type="checkbox" id="all_words" name="all_words"
       {if $obj->mAllWords == "on"} checked="checked" {/if}/>
      Search for all words
    </p>
  </form>
</div>
{* End search box *}
