{load_presentation_object filename="store_admin" assign="obj"}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <title>Demo Store Admin from Beginning PHP and MySQL E-Commerce</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link href="{$obj->mSiteUrl}styles/tshirtshop.css" type="text/css"
     rel="stylesheet" />
  </head>
  <body>
    <div id="doc" class="yui-t7">
      <div id="bd">
        <div class="yui-g">
          {include file=$obj->mMenuCell}
        </div>
        <div class="yui-g">
          {include file=$obj->mContentsCell}
        </div>
      </div>
    </div>
  </body>
</html>
