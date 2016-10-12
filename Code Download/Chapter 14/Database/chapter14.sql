-- Create orders table
CREATE TABLE `orders` (
  `order_id`         INT            NOT NULL  AUTO_INCREMENT,
  `total_amount`     NUMERIC(10, 2) NOT NULL  DEFAULT 0.00,
  `created_on`       DATETIME       NOT NULL,
  `shipped_on`       DATETIME,
  `status`           INT            NOT NULL  DEFAULT 0,
  `comments`         VARCHAR(255),
  `customer_name`    VARCHAR(100),
  `shipping_address` VARCHAR(255),
  `customer_email`   VARCHAR(50),
  PRIMARY KEY (`order_id`)
);

-- Create order_detail table
CREATE TABLE `order_detail` (
  `item_id`      INT            NOT NULL  AUTO_INCREMENT,
  `order_id`     INT            NOT NULL,
  `product_id`   INT            NOT NULL,
  `attributes`   VARCHAR(1000)  NOT NULL,
  `product_name` VARCHAR(100)   NOT NULL,
  `quantity`     INT            NOT NULL,
  `unit_cost`    NUMERIC(10, 2) NOT NULL,
  PRIMARY KEY (`item_id`),
  KEY `idx_order_detail_order_id` (`order_id`)
);

-- Change DELIMITER to $$
DELIMITER $$

-- Create shopping_cart_empty stored procedure
CREATE PROCEDURE shopping_cart_empty(IN inCartId CHAR(32))
BEGIN
  DELETE FROM shopping_cart WHERE cart_id = inCartId;
END$$

-- Create shopping_cart_create_order stored procedure
CREATE PROCEDURE shopping_cart_create_order(IN inCartId CHAR(32))
BEGIN
  DECLARE orderId INT;

  -- Insert a new record into orders and obtain the new order ID
  INSERT INTO orders (created_on) VALUES (NOW());
  -- Obtain the new Order ID
  SELECT LAST_INSERT_ID() INTO orderId;

  -- Insert order details in order_detail table
  INSERT INTO order_detail (order_id, product_id, attributes,
                            product_name, quantity, unit_cost)
  SELECT      orderId, p.product_id, sc.attributes, p.name, sc.quantity,
              COALESCE(NULLIF(p.discounted_price, 0), p.price) AS unit_cost
  FROM        shopping_cart sc
  INNER JOIN  product p
                ON sc.product_id = p.product_id
  WHERE       sc.cart_id = inCartId AND sc.buy_now;

  -- Save the order's total amount
  UPDATE orders
  SET    total_amount = (SELECT SUM(unit_cost * quantity) 
                         FROM   order_detail
                         WHERE  order_id = orderId)
  WHERE  order_id = orderId;

  -- Clear the shopping cart
  CALL shopping_cart_empty(inCartId);

  -- Return the Order ID
  SELECT orderId;
END$$

-- Create orders_get_most_recent_orders stored procedure
CREATE PROCEDURE orders_get_most_recent_orders(IN inHowMany INT)
BEGIN
  PREPARE statement FROM
    "SELECT   order_id, total_amount, created_on,
              shipped_on, status, customer_name
     FROM     orders
     ORDER BY created_on DESC
     LIMIT    ?";

  SET @p1 = inHowMany;

  EXECUTE statement USING @p1;
END$$

-- Create orders_get_orders_between_dates stored procedure
CREATE PROCEDURE orders_get_orders_between_dates(
  IN inStartDate DATETIME, IN inEndDate DATETIME)
BEGIN
  SELECT   order_id, total_amount, created_on,
           shipped_on, status, customer_name
  FROM     orders
  WHERE    created_on >= inStartDate AND created_on <= inEndDate
  ORDER BY created_on DESC;
END$$

-- Create orders_get_orders_by_status stored procedure
CREATE PROCEDURE orders_get_orders_by_status(IN inStatus INT)
BEGIN
  SELECT   order_id, total_amount, created_on,
           shipped_on, status, customer_name
  FROM     orders
  WHERE    status = inStatus
  ORDER BY created_on DESC;
END$$

-- Create orders_get_order_info stored procedure
CREATE PROCEDURE orders_get_order_info(IN inOrderId INT)
BEGIN
  SELECT order_id, total_amount, created_on, shipped_on, status,
         comments, customer_name, shipping_address, customer_email
  FROM   orders
  WHERE  order_id = inOrderId;
END$$

-- Create orders_get_order_details stored procedure
CREATE PROCEDURE orders_get_order_details(IN inOrderId INT)
BEGIN
  SELECT order_id, product_id, attributes, product_name,
         quantity, unit_cost, (quantity * unit_cost) AS subtotal
  FROM   order_detail
  WHERE  order_id = inOrderId;
END$$

-- Create orders_update_order stored procedure
CREATE PROCEDURE orders_update_order(IN inOrderId INT, IN inStatus INT,
  IN inComments VARCHAR(255), IN inCustomerName VARCHAR(50),
  IN inShippingAddress VARCHAR(255), IN inCustomerEmail VARCHAR(50))
BEGIN
  DECLARE currentStatus INT;

  SELECT status
  FROM   orders
  WHERE  order_id = inOrderId
  INTO   currentStatus;

  IF inStatus != currentStatus AND (inStatus = 0 OR inStatus = 1) THEN
    UPDATE orders SET shipped_on = NULL WHERE order_id = inOrderId;
  ELSEIF inStatus != currentStatus AND inStatus = 2 THEN
    UPDATE orders SET shipped_on = NOW() WHERE order_id = inOrderId;
  END IF;

  UPDATE orders
  SET    status = inStatus, comments = inComments,
         customer_name = inCustomerName,
         shipping_address = inShippingAddress,
         customer_email = inCustomerEmail
  WHERE  order_id = inOrderId;
END$$

-- Change back the DELIMITER to ;
DELIMITER ;
