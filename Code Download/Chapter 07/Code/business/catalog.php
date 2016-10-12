<?php
// Business tier class for reading product catalog information

class Catalog
{
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
}
?>
