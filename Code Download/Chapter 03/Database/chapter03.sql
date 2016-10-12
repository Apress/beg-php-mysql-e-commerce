-- Create tshirtshop database
CREATE DATABASE `tshirtshop`
       DEFAULT CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci';

-- Create tshirtshopadmin user
GRANT ALL PRIVILEGES ON `tshirtshop`.*
      TO 'tshirtshopadmin'@'localhost' IDENTIFIED BY 'tshirtshopadmin'
      WITH GRANT OPTION;
