<?php
// Business tier class for the orders
class Orders
{
  public static $mOrderStatusOptions = array ('placed',    // 0
                                              'verified',  // 1
                                              'completed', // 2
                                              'canceled'); // 3

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
                           $customerName, $shippingAddress, $customerEmail)
  {
    // Build the SQL query
    $sql = 'CALL orders_update_order(:order_id, :status, :comments,
                   :customer_name, :shipping_address, :customer_email)';

    // Build the parameters array
    $params = array (':order_id' => $orderId,
                     ':status' => $status,
                     ':comments' => $comments,
                     ':customer_name' => $customerName,
                     ':shipping_address' => $shippingAddress,
                     ':customer_email' => $customerEmail);

    // Execute the query
    DatabaseHandler::Execute($sql, $params);
  }
}
?>
