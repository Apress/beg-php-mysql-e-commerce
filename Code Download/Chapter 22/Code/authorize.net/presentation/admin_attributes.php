<?php
// Class that supports attributes admin functionality
class AdminAttributes
{
  // Public variables available in smarty template
  public $mAttributesCount;
  public $mAttributes;
  public $mErrorMessage;
  public $mEditItem;
  public $mLinkToAttributesAdmin;

 // Private members
  private $_mAction;
  private $_mActionedAttributeId;

  // Class constructor
  public function __construct()
  {
    // Parse the list with posted variables
    foreach ($_POST as $key => $value)
      // If a submit button was clicked ...
      if (substr($key, 0, 6) == 'submit')
      {
        /* Get the position of the last '_' underscore from submit
           button name e.g strtpos('submit_edit_attr_1', '_') is 17 */
        $last_underscore = strrpos($key, '_');

        /* Get the scope of submit button
           (e.g  'edit_dep' from 'submit_edit_attr_1') */
        $this->_mAction = substr($key, strlen('submit_'),
                                 $last_underscore - strlen('submit_'));

        /* Get the attribute id targeted by submit button
           (the number at the end of submit button name)
           e.g '1' from 'submit_edit_attr_1' */
        $this->_mActionedAttributeId = substr($key, $last_underscore + 1);

        break;
      }

    $this->mLinkToAttributesAdmin = Link::ToAttributesAdmin();
  }

  public function init()
  {
    // If adding a new attribute ...
    if ($this->_mAction == 'add_attr')
    {
      $attribute_name = $_POST['attribute_name'];

      if ($attribute_name == null)
        $this->mErrorMessage = 'Attribute name required';

      if ($this->mErrorMessage == null)
      {
        Catalog::AddAttribute($attribute_name);

        header('Location: ' . $this->mLinkToAttributesAdmin);
      }
    }

    // If editing an existing attribute ...
    if ($this->_mAction == 'edit_attr')
      $this->mEditItem = $this->_mActionedAttributeId;

    // If updating an attribute ...
    if ($this->_mAction == 'update_attr')
    {
      $attribute_name = $_POST['name'];

      if ($attribute_name == null)
        $this->mErrorMessage = 'Attribute name required';

      if ($this->mErrorMessage == null)
      {
        Catalog::UpdateAttribute($this->_mActionedAttributeId,
                                 $attribute_name);

        header('Location: ' . $this->mLinkToAttributesAdmin);
      }
    }

    // If deleting an attribute ...
    if ($this->_mAction == 'delete_attr')
    {
      $status = Catalog::DeleteAttribute($this->_mActionedAttributeId);

      if ($status < 0)
        $this->mErrorMessage =
          'Attribute has one or more values and cannot be deleted';
      else
        header('Location: ' . $this->mLinkToAttributesAdmin);
    }

    // If editing an attribute values ...
    if ($this->_mAction == 'edit_val')
    {
      header('Location: ' .
             htmlspecialchars_decode(
               Link::ToAttributeValuesAdmin(
                 $this->_mActionedAttributeId)));

      exit();
    }

    // Load the list of attributes
    $this->mAttributes = Catalog::GetAttributes();
    $this->mAttributesCount = count($this->mAttributes);
  }
}
?>
