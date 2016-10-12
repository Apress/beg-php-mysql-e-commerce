{* admin_menu.tpl *}
{load_presentation_object filename="admin_menu" assign="obj"}
<h1>TShirtShop Admin</h1>
<p class="menu"> |
  <a href="{$obj->mLinkToStoreAdmin}">CATALOG ADMIN</a> |
  <a href="{$obj->mLinkToAttributesAdmin}">PRODUCTS ATTRIBUTES ADMIN</a> |
  <a href="{$obj->mLinkToCartsAdmin}">CARTS ADMIN</a> |
  <a href="{$obj->mLinkToOrdersAdmin}">ORDERS ADMIN</a> |
  <a href="{$obj->mLinkToStoreFront}">STOREFRONT</a> |
  <a href="{$obj->mLinkToLogout}">LOGOUT</a> |
</p>
