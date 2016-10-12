<?php
// Business tier class for reading product catalog information

class Catalog
{
  // Defines product display options
  public static $mProductDisplayOptions = array ('Default',       // 0
                                                 'On Catalog',    // 1
                                                 'On Department', // 2
                                                 'On Both');      // 3

  // Retrieves all departments
  public static function GetDepartments()
  {
    // Build SQL query
    $sql = 'CALL catalog_get_departments_list()';

    // Execute the query and return the results
    return DatabaseHandler::GetAll($sql);
  }

  // Retrieves complete details for the specified department
  public static function GetDepartmentDetails($departmentId)
  {
    // Build SQL query
    $sql = 'CALL catalog_get_department_details(:department_id)';

    // Build the parameters array
    $params = array (':department_id' => $departmentId);

    // Execute the query and return the results
    return DatabaseHandler::GetRow($sql, $params);
  }

  // Retrieves list of categories that belong to a department
  public static function GetCategoriesInDepartment($departmentId)
  {
    // Build SQL query
    $sql = 'CALL catalog_get_categories_list(:department_id)';

    // Build the parameters array
    $params = array (':department_id' => $departmentId);

    // Execute the query and return the results
    return DatabaseHandler::GetAll($sql, $params);
  }

  // Retrieves complete details for the specified category
  public static function GetCategoryDetails($categoryId)
  {
    // Build SQL query
    $sql = 'CALL catalog_get_category_details(:category_id)';

    // Build the parameters array
    $params = array (':category_id' => $categoryId);

    // Execute the query and return the results
    return DatabaseHandler::GetRow($sql, $params);
  }

  /* Calculates how many pages of products could be filled by the
     number of products returned by the $countSql query */
  private static function HowManyPages($countSql, $countSqlParams)
  {
    // Create a hash for the sql query 
    $queryHashCode = md5($countSql . var_export($countSqlParams, true));

    // Verify if we have the query results in cache
    if (isset ($_SESSION['last_count_hash']) &&
        isset ($_SESSION['how_many_pages']) &&
        $_SESSION['last_count_hash'] === $queryHashCode)
    {
      // Retrieve the the cached value
      $how_many_pages = $_SESSION['how_many_pages'];
    }
    else
    {
      // Execute the query
      $items_count = DatabaseHandler::GetOne($countSql, $countSqlParams);

      // Calculate the number of pages
      $how_many_pages = ceil($items_count / PRODUCTS_PER_PAGE);

      // Save the query and its count result in the session
      $_SESSION['last_count_hash'] = $queryHashCode;
      $_SESSION['how_many_pages'] = $how_many_pages;
    }

    // Return the number of pages    
    return $how_many_pages;
  }

  // Retrieves list of products that belong to a category
  public static function GetProductsInCategory(
                           $categoryId, $pageNo, &$rHowManyPages)
  {
    // Query that returns the number of products in the category
    $sql = 'CALL catalog_count_products_in_category(:category_id)';
    // Build the parameters array
    $params = array (':category_id' => $categoryId);

    // Calculate the number of pages required to display the products
    $rHowManyPages = Catalog::HowManyPages($sql, $params);
    // Calculate the start item
    $start_item = ($pageNo - 1) * PRODUCTS_PER_PAGE;

    // Retrieve the list of products
    $sql = 'CALL catalog_get_products_in_category(
                   :category_id, :short_product_description_length,
                   :products_per_page, :start_item)';

    // Build the parameters array
    $params = array (
      ':category_id' => $categoryId,
      ':short_product_description_length' =>
        SHORT_PRODUCT_DESCRIPTION_LENGTH,
      ':products_per_page' => PRODUCTS_PER_PAGE,
      ':start_item' => $start_item);

    // Execute the query and return the results
    return DatabaseHandler::GetAll($sql, $params);
  }

  // Retrieves the list of products for the department page
  public static function GetProductsOnDepartment(
                           $departmentId, $pageNo, &$rHowManyPages)
  {
    // Query that returns the number of products in the department page
    $sql = 'CALL catalog_count_products_on_department(:department_id)';
    // Build the parameters array
    $params = array (':department_id' => $departmentId);

    // Calculate the number of pages required to display the products
    $rHowManyPages = Catalog::HowManyPages($sql, $params);
    // Calculate the start item
    $start_item = ($pageNo - 1) * PRODUCTS_PER_PAGE;

    // Retrieve the list of products
    $sql = 'CALL catalog_get_products_on_department(
                   :department_id, :short_product_description_length,
                   :products_per_page, :start_item)';

    // Build the parameters array
    $params = array (
      ':department_id' => $departmentId,
      ':short_product_description_length' =>
        SHORT_PRODUCT_DESCRIPTION_LENGTH,
      ':products_per_page' => PRODUCTS_PER_PAGE,
      ':start_item' => $start_item);

    // Execute the query and return the results
    return DatabaseHandler::GetAll($sql, $params);
  }

  // Retrieves the list of products on catalog page
  public static function GetProductsOnCatalog($pageNo, &$rHowManyPages)
  {
    // Query that returns the number of products for the front catalog page
    $sql = 'CALL catalog_count_products_on_catalog()';

    // Calculate the number of pages required to display the products
    $rHowManyPages = Catalog::HowManyPages($sql, null);
    // Calculate the start item
    $start_item = ($pageNo - 1) * PRODUCTS_PER_PAGE;

    // Retrieve the list of products
    $sql = 'CALL catalog_get_products_on_catalog(
                   :short_product_description_length,
                   :products_per_page, :start_item)';

    // Build the parameters array
    $params = array (
      ':short_product_description_length' =>
        SHORT_PRODUCT_DESCRIPTION_LENGTH,
      ':products_per_page' => PRODUCTS_PER_PAGE,
      ':start_item' => $start_item);

    // Execute the query and return the results
    return DatabaseHandler::GetAll($sql, $params);
  }

  // Retrieves complete product details
  public static function GetProductDetails($productId)
  {
    // Build SQL query
    $sql = 'CALL catalog_get_product_details(:product_id)';

    // Build the parameters array
    $params = array (':product_id' => $productId);

    // Execute the query and return the results
    return DatabaseHandler::GetRow($sql, $params);
  }

  // Retrieves product locations
  public static function GetProductLocations($productId)
  {
    // Build SQL query
    $sql = 'CALL catalog_get_product_locations(:product_id)';

    // Build the parameters array
    $params = array (':product_id' => $productId);

    // Execute the query and return the results
    return DatabaseHandler::GetAll($sql, $params);
  }

  // Retrieves product attributes
  public static function GetProductAttributes($productId)
  {
    // Build SQL query
    $sql = 'CALL catalog_get_product_attributes(:product_id)';

    // Build the parameters array
    $params = array (':product_id' => $productId);

    // Execute the query and return the results
    return DatabaseHandler::GetAll($sql, $params);
  }

  // Retrieves department name
  public static function GetDepartmentName($departmentId)
  {
    // Build SQL query
    $sql = 'CALL catalog_get_department_name(:department_id)';

    // Build the parameters array
    $params = array (':department_id' => $departmentId);

    // Execute the query and return the results
    return DatabaseHandler::GetOne($sql, $params);
  }

  // Retrieves category name
  public static function GetCategoryName($categoryId)
  {
    // Build SQL query
    $sql = 'CALL catalog_get_category_name(:category_id)';

    // Build the parameters array
    $params = array (':category_id' => $categoryId);

    // Execute the query and return the results
    return DatabaseHandler::GetOne($sql, $params);
  }

  // Retrieves product name
  public static function GetProductName($productId)
  {
    // Build SQL query
    $sql = 'CALL catalog_get_product_name(:product_id)';

    // Build the parameters array
    $params = array (':product_id' => $productId);

    // Execute the query and return the results
    return DatabaseHandler::GetOne($sql, $params);
  }

  // Search the catalog
  public static function Search($searchString, $allWords,
                                $pageNo, &$rHowManyPages)
  {
    //The search result will be an array of this form
    $search_result = array ('accepted_words' => array (),
                            'ignored_words' => array (),
                            'products' => array ());

    // Return void if the search string is void
    if (empty ($searchString))
      return $search_result;

    // Search string delimiters
    $delimiters = ',.; ';

    /* On the first call to strtok you supply the whole
       search string and the list of delimiters.
       It returns the first word of the string */
    $word = strtok($searchString, $delimiters);

    // Parse the string word by word until there are no more words
    while ($word)
    {
      // Short words are added to the ignored_words list from $search_result
      if (strlen($word) < FT_MIN_WORD_LEN)
        $search_result['ignored_words'][] = $word;
      else
        $search_result['accepted_words'][] = $word;

      // Get the next word of the search string
      $word = strtok($delimiters);
    }

    // If there aren't any accepted words return the $search_result
    if (count($search_result['accepted_words']) == 0)
      return $search_result;

    // Build $search_string from accepted words list
    $search_string = '';

    // If $allWords is 'on' then we append a ' +' to each word
    if (strcmp($allWords, "on") == 0)
      $search_string = implode(" +", $search_result['accepted_words']);
    else
      $search_string = implode(" ", $search_result['accepted_words']);

    // Count the number of search results
    $sql = 'CALL catalog_count_search_result(:search_string, :all_words)';
    $params = array(':search_string' => $search_string,
                    ':all_words' => $allWords);

    // Calculate the number of pages required to display the products
    $rHowManyPages = Catalog::HowManyPages($sql, $params);
    // Calculate the start item
    $start_item = ($pageNo - 1) * PRODUCTS_PER_PAGE;

    // Retrieve the list of matching products
    $sql = 'CALL catalog_search(:search_string, :all_words,
                                :short_product_description_length,
                                :products_per_page, :start_item)';

    // Build the parameters array
    $params = array (':search_string' => $search_string,
                     ':all_words' => $allWords,
                     ':short_product_description_length' =>
                       SHORT_PRODUCT_DESCRIPTION_LENGTH,
                     ':products_per_page' => PRODUCTS_PER_PAGE,
                     ':start_item' => $start_item);

    // Execute the query
    $search_result['products'] = DatabaseHandler::GetAll($sql, $params);

    // Return the results
    return $search_result;
  }

  // Retrieves all departments with their descriptions
  public static function GetDepartmentsWithDescriptions()
  {
    // Build the SQL query
    $sql = 'CALL catalog_get_departments()';

    // Execute the query and return the results
    return DatabaseHandler::GetAll($sql);
  }

  // Add a department
  public static function AddDepartment($departmentName, $departmentDescription)
  {
    // Build the SQL query
    $sql = 'CALL catalog_add_department(:department_name,
                                        :department_description)';

    // Build the parameters array
    $params = array (':department_name' => $departmentName,
                     ':department_description' => $departmentDescription);

    // Execute the query
    DatabaseHandler::Execute($sql, $params);
  }

  // Updates department details
  public static function UpdateDepartment($departmentId, $departmentName,
                                          $departmentDescription)
  {
    // Build the SQL query
    $sql = 'CALL catalog_update_department(:department_id, :department_name,
                                           :department_description)';

    // Build the parameters array
    $params = array (':department_id' => $departmentId,
                     ':department_name' => $departmentName,
                     ':department_description' => $departmentDescription);

    // Execute the query
    DatabaseHandler::Execute($sql, $params);
  }

  // Deletes a department
  public static function DeleteDepartment($departmentId)
  {
    // Build the SQL query
    $sql = 'CALL catalog_delete_department(:department_id)';

    // Build the parameters array
    $params = array (':department_id' => $departmentId);

    // Execute the query and return the results
    return DatabaseHandler::GetOne($sql, $params);
  }

  // Gets categories in a department
  public static function GetDepartmentCategories($departmentId)
  {
    // Build the SQL query
    $sql = 'CALL catalog_get_department_categories(:department_id)';

    // Build the parameters array
    $params = array (':department_id' => $departmentId);

    // Execute the query and return the results
    return DatabaseHandler::GetAll($sql, $params);
  }

  // Adds a category
  public static function AddCategory($departmentId, $categoryName,
                                     $categoryDescription)
  {
    // Build the SQL query
    $sql = 'CALL catalog_add_category(:department_id, :category_name,
                                      :category_description)';

    // Build the parameters array
    $params = array (':department_id' => $departmentId,
                     ':category_name' => $categoryName,
                     ':category_description' => $categoryDescription);

    // Execute the query
    DatabaseHandler::Execute($sql, $params);
  }

  // Updates a category
  public static function UpdateCategory($categoryId, $categoryName,
                                        $categoryDescription)
  {
    // Build the SQL query
    $sql = 'CALL catalog_update_category(:category_id, :category_name,
                                         :category_description)';

    // Build the parameters array
    $params = array (':category_id' => $categoryId,
                     ':category_name' => $categoryName,
                     ':category_description' => $categoryDescription);

    // Execute the query
    DatabaseHandler::Execute($sql, $params);
  }

  // Deletes a category
  public static function DeleteCategory($categoryId)
  {
    // Build the SQL query
    $sql = 'CALL catalog_delete_category(:category_id)';

    // Build the parameters array
    $params = array (':category_id' => $categoryId);

    // Execute the query and return the results
    return DatabaseHandler::GetOne($sql, $params);
  }

  // Retrieves all attributes
  public static function GetAttributes()
  {
    // Build the SQL query
    $sql = 'CALL catalog_get_attributes()';

    // Execute the query and return the results
    return DatabaseHandler::GetAll($sql);
  }

  // Add an attribute
  public static function AddAttribute($attributeName)
  {
    // Build the SQL query
    $sql = 'CALL catalog_add_attribute(:attribute_name)';

    // Build the parameters array
    $params = array (':attribute_name' => $attributeName);

    // Execute the query
    DatabaseHandler::Execute($sql, $params);
  }

  // Updates attribute name
  public static function UpdateAttribute($attributeId, $attributeName)
  {
    // Build the SQL query
    $sql = 'CALL catalog_update_attribute(:attribute_id, :attribute_name)';

    // Build the parameters array
    $params = array (':attribute_id' => $attributeId,
                     ':attribute_name' => $attributeName);

    // Execute the query
    DatabaseHandler::Execute($sql, $params);
  }

  // Deletes an attribute
  public static function DeleteAttribute($attributeId)
  {
    // Build the SQL query
    $sql = 'CALL catalog_delete_attribute(:attribute_id)';

    // Build the parameters array
    $params = array (':attribute_id' => $attributeId);

    // Execute the query and return the results
    return DatabaseHandler::GetOne($sql, $params);
  }

  // Retrieves details for the specified attribute
  public static function GetAttributeDetails($attributeId)
  {
    // Build SQL query
    $sql = 'CALL catalog_get_attribute_details(:attribute_id)';

    // Build the parameters array
    $params = array (':attribute_id' => $attributeId);

    // Execute the query and return the results
    return DatabaseHandler::GetRow($sql, $params);
  }

  // Gets atribute values
  public static function GetAttributeValues($attributeId)
  {
    // Build the SQL query
    $sql = 'CALL catalog_get_attribute_values(:attribute_id)';

    // Build the parameters array
    $params = array (':attribute_id' => $attributeId);

    // Execute the query and return the results
    return DatabaseHandler::GetAll($sql, $params);
  }

  // Adds an attribute value
  public static function AddAttributeValue($attributeId, $attributeValue)
  {
    // Build the SQL query
    $sql = 'CALL catalog_add_attribute_value(:attribute_id, :value)';

    // Build the parameters array
    $params = array (':attribute_id' => $attributeId,
                     ':value' => $attributeValue);

    // Execute the query
    DatabaseHandler::Execute($sql, $params);
  }

  // Updates an atribute value
  public static function UpdateAttributeValue(
                           $attributeValueId, $attributeValue)
  {
    // Build the SQL query
    $sql = 'CALL catalog_update_attribute_value(
                   :attribute_value_id, :value)';

    // Build the parameters array
    $params = array (':attribute_value_id' => $attributeValueId,
                     ':value' => $attributeValue);

    // Execute the query
    DatabaseHandler::Execute($sql, $params);
  }

  // Deletes an attribute value
  public static function DeleteAttributeValue($attributeValueId)
  {
    // Build the SQL query
    $sql = 'CALL catalog_delete_attribute_value(:attribute_value_id)';

    // Build the parameters array
    $params = array (':attribute_value_id' => $attributeValueId);

    // Execute the query and return the results
    return DatabaseHandler::GetOne($sql, $params);
  }

  // Gets products in a category
  public static function GetCategoryProducts($categoryId)
  {
    // Build the SQL query
    $sql = 'CALL catalog_get_category_products(:category_id)';

    // Build the parameters array
    $params = array (':category_id' => $categoryId);

    // Execute the query and return the results
    return DatabaseHandler::GetAll($sql, $params);
  }

  // Creates a product and assigns it to a category
  public static function AddProductToCategory($categoryId, $productName,
                           $productDescription, $productPrice)
  {
    // Build the SQL query
    $sql = 'CALL catalog_add_product_to_category(:category_id, :product_name,
                   :product_description, :product_price)';

    // Build the parameters array
    $params = array (':category_id' => $categoryId,
                     ':product_name' => $productName,
                     ':product_description' => $productDescription,
                     ':product_price' => $productPrice);

    // Execute the query
    DatabaseHandler::Execute($sql, $params);
  }

  // Updates a product
  public static function UpdateProduct($productId, $productName,
                   $productDescription, $productPrice,
                   $productDiscountedPrice)
  {
    // Build the SQL query
    $sql = 'CALL catalog_update_product(:product_id, :product_name,
                   :product_description, :product_price,
                   :product_discounted_price)';

    // Build the parameters array
    $params = array (':product_id' => $productId,
                     ':product_name' => $productName,
                     ':product_description' => $productDescription,
                     ':product_price' => $productPrice,
                     ':product_discounted_price' => $productDiscountedPrice);

    // Execute the query
    DatabaseHandler::Execute($sql, $params);
  }

  // Removes a product from the product catalog
  public static function DeleteProduct($productId)
  {
    // Build SQL query
    $sql = 'CALL catalog_delete_product(:product_id)';

    // Build the parameters array
    $params = array (':product_id' => $productId);

    // Execute the query
    DatabaseHandler::Execute($sql, $params);
  }

  // Unassigns a product from a category
  public static function RemoveProductFromCategory($productId, $categoryId)
  {
    // Build SQL query
    $sql = 'CALL catalog_remove_product_from_category(
                   :product_id, :category_id)';

    // Build the parameters array
    $params = array (':product_id' => $productId,
                     ':category_id' => $categoryId);

    // Execute the query and return the results
    return DatabaseHandler::GetOne($sql, $params);
  }

  // Retrieves the list of categories a product belongs to
  public static function GetCategories()
  {
    // Build SQL query
    $sql = 'CALL catalog_get_categories()';

    // Execute the query and return the results
    return DatabaseHandler::GetAll($sql);
  }

  // Retrieves product info
  public static function GetProductInfo($productId)
  {
    // Build SQL query
    $sql = 'CALL catalog_get_product_info(:product_id)';

    // Build the parameters array
    $params = array (':product_id' => $productId);

    // Execute the query and return the results
    return DatabaseHandler::GetRow($sql, $params);
  }

  // Retrieves the list of categories a product belongs to
  public static function GetCategoriesForProduct($productId)
  {
    // Build SQL query
    $sql = 'CALL catalog_get_categories_for_product(:product_id)';

    // Build the parameters array
    $params = array (':product_id' => $productId);

    // Execute the query and return the results
    return DatabaseHandler::GetAll($sql, $params);
  }

  // Assigns a product to a category
  public static function SetProductDisplayOption($productId, $display)
  {
    // Build SQL query
    $sql = 'CALL catalog_set_product_display_option(
                   :product_id, :display)';

    // Build the parameters array
    $params = array (':product_id' => $productId,
                     ':display' => $display);

    // Execute the query
    DatabaseHandler::Execute($sql, $params);
  }

  // Assigns a product to a category
  public static function AssignProductToCategory($productId, $categoryId)
  {
    // Build SQL query
    $sql = 'CALL catalog_assign_product_to_category(
                   :product_id, :category_id)';

    // Build the parameters array
    $params = array (':product_id' => $productId,
                     ':category_id' => $categoryId);

    // Execute the query
    DatabaseHandler::Execute($sql, $params);
  }

  // Moves a product from one category to another
  public static function MoveProductToCategory($productId, $sourceCategoryId,
                                               $targetCategoryId)
  {
    // Build SQL query
    $sql = 'CALL catalog_move_product_to_category(:product_id,
                   :source_category_id, :target_category_id)';

    // Build the parameters array
    $params = array (':product_id' => $productId,
                     ':source_category_id' => $sourceCategoryId,
                     ':target_category_id' => $targetCategoryId);

    // Execute the query
    DatabaseHandler::Execute($sql, $params);
  }

  // Gets the catalog attributes that are not assigned to the specified product
  public static function GetAttributesNotAssignedToProduct($productId)
  {
    // Build the SQL query
    $sql = 'CALL catalog_get_attributes_not_assigned_to_product(:product_id)';

    // Build the parameters array
    $params = array (':product_id' => $productId);

    // Execute the query and return the results
    return DatabaseHandler::GetAll($sql, $params);
  }

  // Assign an attribute value to the specified product
  public static function AssignAttributeValueToProduct($productId,
                                                       $attributeValueId)
  {
    // Build SQL query
    $sql = 'CALL catalog_assign_attribute_value_to_product(
                   :product_id, :attribute_value_id)';

    // Build the parameters array
    $params = array (':product_id' => $productId,
                     ':attribute_value_id' => $attributeValueId);

    // Execute the query
    DatabaseHandler::Execute($sql, $params);
  }

  // Removes a product attribute value
  public static function RemoveProductAttributeValue($productId,
                                                     $attributeValueId)
  {
    // Build SQL query
    $sql = 'CALL catalog_remove_product_attribute_value(
                   :product_id, :attribute_value_id)';

    // Build the parameters array
    $params = array (':product_id' => $productId,
                     ':attribute_value_id' => $attributeValueId);

    // Execute the query
    DatabaseHandler::Execute($sql, $params);
  }


  // Changes the name of the product image file in the database
  public static function SetImage($productId, $imageName)
  {
    // Build SQL query
    $sql = 'CALL catalog_set_image(:product_id, :image_name)';

    // Build the parameters array
    $params = array (':product_id' => $productId, ':image_name' => $imageName);

    // Execute the query
    DatabaseHandler::Execute($sql, $params);
  }

  // Changes the name of the second product image file in the database
  public static function SetImage2($productId, $imageName)
  {
    // Build SQL query
    $sql = 'CALL catalog_set_image_2(:product_id, :image_name)';

    // Build the parameters array
    $params = array (':product_id' => $productId, ':image_name' => $imageName);

    // Execute the query
    DatabaseHandler::Execute($sql, $params);
  }

  // Changes the name of the product thumbnail file in the database
  public static function SetThumbnail($productId, $thumbnailName)
  {
    // Build SQL query
    $sql = 'CALL catalog_set_thumbnail(:product_id, :thumbnail_name)';

    // Build the parameters array
    $params = array (':product_id' => $productId,
                     ':thumbnail_name' => $thumbnailName);

    // Execute the query
    DatabaseHandler::Execute($sql, $params);
  }

  // Get product recommendations
  public static function GetRecommendations($productId)
  {
    // Build the SQL query
    $sql = 'CALL catalog_get_recommendations(
                   :product_id, :short_product_description_length)';

    // Build the parameters array
    $params = array (':product_id' => $productId,
                     ':short_product_description_length' =>
                       SHORT_PRODUCT_DESCRIPTION_LENGTH);

    // Execute the query and return the results
    return DatabaseHandler::GetAll($sql, $params);
  }

  // Gets the reviews for a specific product
  public static function GetProductReviews($productId)
  {
    // Build the SQL query
    $sql = 'CALL catalog_get_product_reviews(:product_id)';

    // Build the parameters array
    $params = array (':product_id' => $productId);

    // Execute the query and return the results
    return DatabaseHandler::GetAll($sql, $params);
  }

  // Creates a product review
  public static function CreateProductReview($customer_id, $productId,
                                             $review, $rating)
  {
    // Build the SQL query
    $sql ='CALL catalog_create_product_review(:customer_id, :product_id,
                                               :review, :rating)';
    // Build the parameters array
    $params = array (':customer_id' => $customer_id,
                     ':product_id' => $productId,
                     ':review' => $review,
                     ':rating' => $rating);

    // Execute the query
    DatabaseHandler::Execute($sql, $params);
  }
}
?>
