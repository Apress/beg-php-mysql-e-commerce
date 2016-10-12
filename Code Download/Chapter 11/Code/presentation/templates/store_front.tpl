{* smarty *}
{config_load file="site.conf"}
{load_presentation_object filename="store_front" assign="obj"}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <title>{$obj->mPageTitle}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link type="text/css" rel="stylesheet"
     href="{$obj->mSiteUrl}styles/tshirtshop.css" />
  </head>
  <body>
    <div id="doc" class="yui-t2">
      <div id="bd">
        <div id="yui-main">
          <div class="yui-b">
            <div id="header" class="yui-g">
              <a href="{$obj->mSiteUrl}">
                <img src="{$obj->mSiteUrl}images/tshirtshop.png"
                 alt="tshirtshop logo" />
              </a>
            </div>
            <div id="contents" class="yui-g">
              {include file=$obj->mContentsCell}
            </div>
          </div>
        </div>
        <div class="yui-b">
          {include file="search_box.tpl"}
          {include file="departments_list.tpl"}
          {include file=$obj->mCategoriesCell}
          <div class="view-cart">
            <form target="_self" method="post"
             action="{$smarty.const.PAYPAL_URL}">
              <input type="hidden" name="cmd" value="_cart" />
              <input type="hidden" name="business"
               value="{$smarty.const.PAYPAL_EMAIL}" />
              <input type="hidden" name="display" value="1" />
              <input type="hidden" name="shopping_url"
               value="{$obj->mPayPalContinueShoppingLink}" />
              <input type="hidden" name="return"
               value="{$smarty.const.PAYPAL_RETURN_URL}" />
              <input type="hidden" name="cancel_return"
               value="{$smarty.const.PAYPAL_CANCEL_RETURN_URL}" />
              <input type="submit" name="view_cart" value="View Cart" />
            </form>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
