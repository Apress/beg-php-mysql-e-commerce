<?php
// Manages the search box
class SearchBox
{
  // Public variables for the smarty template
  public $mSearchString = '';
  public $mAllWords = 'off';
  public $mLinkToSearch;

  // Class constructor
  public function __construct()
  {
    $this->mLinkToSearch = Link::ToSearch();

    if (isset ($_GET['Search']))
    {
      $this->mSearchString = trim($_POST['search_string']);
      $this->mAllWords = isset ($_POST['all_words']) ?
                         $_POST['all_words'] : 'off';

      // Clean output buffer
      ob_clean();

      // Redirect 302
      header('HTTP/1.1 302 Found');
      header('Location: ' .
              Link::ToSearchResults($this->mSearchString, $this->mAllWords));

      // Clear the output buffer and stop execution
      flush();
      ob_flush();
      ob_end_clean();
      exit();
    }
    elseif (isset ($_GET['SearchResults']))
    {
      $this->mSearchString = trim(str_replace('-', ' ', $_GET['SearchString']));
      $this->mAllWords = isset ($_GET['AllWords']) ? $_GET['AllWords'] : 'off';
    }

    if (isset ($_GET['ProductId']) &&
        isset ($_SESSION['link_to_continue_shopping']))
    {
      $continue_shopping =
        Link::QueryStringToArray($_SESSION['link_to_continue_shopping']);

      if (isset ($continue_shopping['SearchResults']))
      {
        $this->mSearchString =
          trim(str_replace('-', ' ', $continue_shopping['SearchString']));
        $this->mAllWords = $continue_shopping['AllWords'];
      }
    }
  }
}
?>
