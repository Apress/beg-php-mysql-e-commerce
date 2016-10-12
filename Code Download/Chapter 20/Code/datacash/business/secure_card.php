<?php
// Represents a credit card
class SecureCard
{
  // Private members containing credit card's details
  private $_mIsDecrypted = false;
  private $_mIsEncrypted = false;
  private $_mCardHolder;
  private $_mCardNumber;
  private $_mIssueDate;
  private $_mExpiryDate;
  private $_mIssueNumber;
  private $_mCardType;
  private $_mEncryptedData;
  private $_mXmlCardData;

  // Class constructor
  public function __construct()
  {
    // Nothing here
  }

  // Decrypt data
  public function LoadEncryptedDataAndDecrypt($newEncryptedData)
  {
    $this->_mEncryptedData = $newEncryptedData;
    $this->DecryptData();
  }

  // Encrypt data
  public function LoadPlainDataAndEncrypt($newCardHolder, $newCardNumber,
                                          $newIssueDate, $newExpiryDate,
                                          $newIssueNumber, $newCardType)
  {
    $this->_mCardHolder = $newCardHolder;
    $this->_mCardNumber = $newCardNumber;
    $this->_mIssueDate = $newIssueDate;
    $this->_mExpiryDate = $newExpiryDate;
    $this->_mIssueNumber = $newIssueNumber;
    $this->_mCardType = $newCardType;
    $this->EncryptData();
  }

  // Create XML with credit card information
  private function CreateXml()
  {
    // Encode card details as XML document
    $xml_card_data = &$this->_mXmlCardData;
    $xml_card_data = new DOMDocument();

    $document_root = $xml_card_data->createElement('CardDetails');

    $child = $xml_card_data->createElement('CardHolder');
    $child = $document_root->appendChild($child);
    $value = $xml_card_data->createTextNode($this->_mCardHolder);
    $value = $child->appendChild($value);

    $child = $xml_card_data->createElement('CardNumber');
    $child = $document_root->appendChild($child);
    $value = $xml_card_data->createTextNode($this->_mCardNumber);
    $value = $child->appendChild($value);

    $child = $xml_card_data->createElement('IssueDate');
    $child = $document_root->appendChild($child);
    $value = $xml_card_data->createTextNode($this->_mIssueDate);
    $value = $child->appendChild($value);

    $child = $xml_card_data->createElement('ExpiryDate');
    $child = $document_root->appendChild($child);
    $value = $xml_card_data->createTextNode($this->_mExpiryDate);
    $value = $child->appendChild($value);

    $child = $xml_card_data->createElement('IssueNumber');
    $child = $document_root->appendChild($child);
    $value = $xml_card_data->createTextNode($this->_mIssueNumber);
    $value = $child->appendChild($value);

    $child = $xml_card_data->createElement('CardType');
    $child = $document_root->appendChild($child);
    $value = $xml_card_data->createTextNode($this->_mCardType);
    $value = $child->appendChild($value);

    $document_root = $xml_card_data->appendChild($document_root);
  }

  // Extract information from XML credit card data
  private function ExtractXml($decryptedData)
  {
    $xml = simplexml_load_string($decryptedData);
    $this->_mCardHolder = (string) $xml->CardHolder;
    $this->_mCardNumber = (string) $xml->CardNumber;
    $this->_mIssueDate = (string) $xml->IssueDate;
    $this->_mExpiryDate = (string) $xml->ExpiryDate;
    $this->_mIssueNumber = (string) $xml->IssueNumber;
    $this->_mCardType = (string) $xml->CardType;
  }

  // Encrypts the XML credit card data
  private function EncryptData()
  {
    // Put data into XML doc
    $this->CreateXml();

    // Encrypt data
    $this->_mEncryptedData =
      SymmetricCrypt::Encrypt($this->_mXmlCardData->saveXML());

    // Set encrypted flag
    $this->_mIsEncrypted = true;
  }

  // Decrypts XML credit card data
  private function DecryptData()
  {
    // Decrypt data
    $decrypted_data = SymmetricCrypt::Decrypt($this->_mEncryptedData);

    // Extract data from XML
    $this->ExtractXml($decrypted_data);

    // Set decrypted flag
    $this->_mIsDecrypted = true;
  }

  public function __get($name)
  {
    if ($name == 'EncryptedData')
    {
      if ($this->_mIsEncrypted)
        return $this->_mEncryptedData;
      else
        throw new Exception('Data not encrypted');
    }
    elseif ($name == 'CardNumberX')
    {
      if ($this->_mIsDecrypted)
        return 'XXXX-XXXX-XXXX-' .
          substr($this->_mCardNumber, strlen($this->_mCardNumber) - 4, 4);
      else
        throw new Exception('Data not decrypted');
    }
    elseif (in_array($name, array ('CardHolder', 'CardNumber', 'IssueDate',
                                   'ExpiryDate', 'IssueNumber', 'CardType')))
    {
      $name = '_m' . $name;

      if ($this->_mIsDecrypted)
        return $this->$name;
      else
        throw new Exception('Data not decrypted');
    }
    else
    {
      throw new Exception('Property ' . $name . ' not found');
    }
  }
}
?>
