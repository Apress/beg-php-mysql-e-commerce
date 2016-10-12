<?php
// Business tier class for the orders
class Orders
{
  public static $mOrderStatusOptions = array (
                  'Order placed, notifying customer', // 0
                  'Awaiting confirmation of funds',   // 1
                  'Notifying supplier-stock check',   // 2
                  'Awaiting stock confirmation',      // 3
                  'Awaiting credit card payment',     // 4
                  'Notifying supplier-shipping',      // 5
                  'Awaiting shipment confirmation',   // 6
                  'Sending final notification',       // 7
                  'Order completed',                  // 8
                  'Order canceled');                  // 9

  // Get the most recent $how_many orders
  public static function GetMostRecentOrders($how_many)
  {
    // Build the SQL query
    $sql = 'CALL orders_get_most_recent_orders(:how_many)';

    // Build the parameters array
    $params = array (':how_many' => $how_many);

    // Execute the query and return the results
    return DatabaseHandler::GetAll($sql, $params);
  }

  // Get orders between two dates
  public static function GetOrdersBetweenDates($startDate, $endDate)
  {
    // Build the SQL query
    $sql = 'CALL orders_get_orders_between_dates(:start_date, :end_date)';

    // Build the parameters array
    $params = array (':start_date' => $startDate, ':end_date' => $endDate);

    // Execute the query and return the results
    return DatabaseHandler::GetAll($sql, $params);
  }

  // Gets orders by status
  public static function GetOrdersByStatus($status)
  {
    // Build the SQL query
    $sql = 'CALL orders_get_orders_by_status(:status)';

    // Build the parameters array
    $params = array (':status' => $status);

    // Execute the query and return the results
    return DatabaseHandler::GetAll($sql, $params);
  }

  // Gets the details of a specific order
  public static function GetOrderInfo($orderId)
  {
    // Build the SQL query
    $sql = 'CALL orders_get_order_info(:order_id)';

    // Build the parameters array
    $params = array (':order_id' => $orderId);

    // Execute the query and return the results
    return DatabaseHandler::GetRow($sql, $params);
  }

  // Gets the products that belong to a specific order
  public static function GetOrderDetails($orderId)
  {
    // Build the SQL query
    $sql = 'CALL orders_get_order_details(:order_id)';

    // Build the parameters array
    $params = array (':order_id' => $orderId);

    // Execute the query and return the results
    return DatabaseHandler::GetAll($sql, $params);
  }

  // Updates order details
  public static function UpdateOrder($orderId, $status, $comments,
                                     $authCode, $reference)
  {
    // Build the SQL query
    $sql = 'CALL orders_update_order(:order_id, :status, :comments,
                   :auth_code, :reference)';

    // Build the parameters array
    $params = array (':order_id' => $orderId,
                     ':status' => $status,
                     ':comments' => $comments,
                     ':auth_code' => $authCode,
                     ':reference' => $reference);

    // Execute the query
    DatabaseHandler::Execute($sql, $params);
  }

  // Gets all orders placed by a specified customer
  public static function GetByCustomerId($customerId)
  {
    // Build the SQL query
    $sql = 'CALL orders_get_by_customer_id(:customer_id)';

    // Build the parameters array
    $params = array (':customer_id' => $customerId);

    // Execute the query and return the results
    return DatabaseHandler::GetAll($sql, $params);
  }

  // Get short details for an order
  public static function GetOrderShortDetails($orderId)
  {
    // Build the SQL query
    $sql = 'CALL orders_get_order_short_details(:order_id)';

    // Build the parameters array
    $params = array (':order_id' => $orderId);

    // Execute the query and return the results
    return DatabaseHandler::GetAll($sql, $params);
  }

  // Retrieves the shipping details for a given $shippingRegionId
  public static function GetShippingInfo($shippingRegionId)
  {
    // Build the SQL query
    $sql = 'CALL orders_get_shipping_info(:shipping_region_id)';

    // Build the parameters array
    $params = array (':shipping_region_id' => $shippingRegionId);

    // Execute the query and return the results
    return DatabaseHandler::GetAll($sql, $params);
  }

  // Creates audit record
  public static function CreateAudit($orderId, $message, $code)
  {
    // Build the SQL query
    $sql = 'CALL orders_create_audit(:order_id, :message, :code)';

    // Build the parameters array
    $params = array (':order_id' => $orderId,
                     ':message' => $message,
                     ':code' => $code);

    // Execute the query
    DatabaseHandler::Execute($sql, $params);
  }

  // Updates the order pipeline status of an order
  public static function UpdateOrderStatus($orderId, $status)
  {
    // Build the SQL query
    $sql = 'CALL orders_update_status(:order_id, :status)';

    // Build the parameters array
    $params = array (':order_id' => $orderId, ':status' => $status);

    // Execute the query
    DatabaseHandler::Execute($sql, $params);
  }

  // Sets order's authorization code
  public static function SetOrderAuthCodeAndReference ($orderId, $authCode,
                                                       $reference)
  {
    // Build the SQL query
    $sql = 'CALL orders_set_auth_code(:order_id, :auth_code, :reference)';

    // Build the parameters array
    $params = array (':order_id' => $orderId,
                     ':auth_code' => $authCode,
                     ':reference' => $reference);

    // Execute the query
    DatabaseHandler::Execute($sql, $params);
  }

  // Set order's ship date
  public static function SetDateShipped($orderId)
  {
    // Build the SQL query
    $sql = 'CALL orders_set_date_shipped(:order_id)';

    // Build the parameters array
    $params = array (':order_id' => $orderId);

    // Execute the query
    DatabaseHandler::Execute($sql, $params);
  }

  // Gets the audit table entries associated with a specific order
  public static function GetAuditTrail($orderId)
  {
    // Build the SQL query
    $sql = 'CALL orders_get_audit_trail(:order_id)';

    // Build the parameters array
    $params = array (':order_id' => $orderId);

    // Execute the query and return the results
    return DatabaseHandler::GetAll($sql, $params);
  }
}
?>
