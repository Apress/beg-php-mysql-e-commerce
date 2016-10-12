<?php
// Class that deals with attribute values admin
class AdminAttributeValues
{
  // Public variables available in smarty template
  public $mAttributeValuesCount;
  public $mAttributeValues;
  public $mErrorMessage;
  public $mEditItem;
  public $mAttributeId;
  public $mAttributeName;
  public $mLinkToAttributeAdmin;
  public $mLinkToAttributeValuesAdmin;

  // Private members
  private $_mAction;
  private $_mActionedAttributeValueId;

  // Class constructor
  public function __construct()
  {
    if (isset ($_GET['AttributeId']))
      $this->mAttributeId = (int)$_GET['AttributeId'];
    else
      trigger_error('AttributeId not set');

    $attribute_details = Catalog::GetAttributeDetails($this->mAttributeId);
    $this->mAttributeName = $attribute_details['name'];

    foreach ($_POST as $key => $value)
      // If a submit button was clicked ...
      if (substr($key, 0, 6) == 'submit')
      {
        /* Get the position of the last '_' underscore from submit
           button name e.g strtpos('submit_edit_val_1', '_') is 16 */
        $last_underscore = strrpos($key, '_');

        /* Get the scope of submit button
           (e.g  'edit_cat' from 'submit_edit_val_1') */
        $this->_mAction = substr($key, strlen('submit_'),
                                $last_underscore - strlen('submit_'));

        /* Get the attribute value id targeted by submit button
           (the number at the end of submit button name)
           e.g '1' from 'submit_edit_val_1' */
        $this->_mActionedAttributeValueId =
          (int)substr($key, $last_underscore + 1);

        break;
      }

    $this->mLinkToAttributesAdmin = Link::ToAttributesAdmin();

    $this->mLinkToAttributeValuesAdmin =
      Link::ToAttributeValuesAdmin($this->mAttributeId);
  }

  public function init()
  {
    // If adding a new attribute value ...
    if ($this->_mAction == 'add_val')
    {
      $attribute_value = $_POST['attribute_value'];

      if ($attribute_value == null)
        $this->mErrorMessage = 'Attribute value is empty';

      if ($this->mErrorMessage == null)
      {
        Catalog::AddAttributeValue($this->mAttributeId, $attribute_value);

        header('Location: ' .
               htmlspecialchars_decode(
                 $this->mLinkToAttributeValuesAdmin));
      }
    }

    // If editing an existing attribute value ...
    if ($this->_mAction == 'edit_val')
    {
      $this->mEditItem = $this->_mActionedAttributeValueId;
    }

    // If updating an attribute value ...
    if ($this->_mAction == 'update_val')
    {
      $attribute_value = $_POST['value'];

      if ($attribute_value == null)
        $this->mErrorMessage = 'Attribute value is empty';

      if ($this->mErrorMessage == null)
      {
        Catalog::UpdateAttributeValue(
          $this->_mActionedAttributeValueId, $attribute_value);

        header('Location: ' .
               htmlspecialchars_decode(
                 $this->mLinkToAttributeValuesAdmin));
      }
    }

    // If deleting an attribute value ...
    if ($this->_mAction == 'delete_val')
    {
      $status =
        Catalog::DeleteAttributeValue($this->_mActionedAttributeValueId);

      if ($status < 0)
        $this->mErrorMessage = 'Cannot delete this attribute value. ' .
                               'One or more products are using it!';
      else
        header('Location: ' .
               htmlspecialchars_decode(
                 $this->mLinkToAttributeValuesAdmin));
    }

    // Load the list of attribute values
    $this->mAttributeValues =
      Catalog::GetAttributeValues($this->mAttributeId);
    $this->mAttributeValuesCount = count($this->mAttributeValues);
  }
}
?>
