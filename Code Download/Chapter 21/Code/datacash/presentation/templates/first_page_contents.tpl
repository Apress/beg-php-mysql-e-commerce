{* first_page_contents.tpl *}
{load_presentation_object filename="first_page_contents" assign="obj"}
<p class="description">
  We hope you have fun developing TShirtShop, the e-commerce store from
  Beginning PHP and MySQL E-Commerce: From Novice to Professional!
</p>
<p class="description">
  We have the largest collection of t-shirts with postal stamps on Earth!
  Browse our departments and cateogories to find your favorite!
</p>
<p>Access the <a href="{$obj->mLinkToAdmin}">admin page</a>.</p>
{include file="products_list.tpl"}
