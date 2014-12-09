/*Create view of Revenues for each product*/
DROP VIEW IF EXISTS `RevenuesView`;
CREATE VIEW RevenuesView AS
SELECT c.ProductID, sum(c.UnitPrice*c.Quantity) AS Revenues FROM order_details c GROUP BY ProductID;

/*Create view of OrderID's of a Customer*/
DROP VIEW IF EXISTS `OrdersOfCustomer1`;
CREATE VIEW OrdersOfCustomer1 AS
SELECT OrderID FROM orders WHERE CustomerID='VINET';

/*Create view of Quantity of each product bought by a Customer*/
DROP VIEW IF EXISTS `Customer1`;
CREATE VIEW Customer1 AS
SELECT b.ProductID,sum(b.Quantity) AS C1 FROM OrdersOfCustomer1 a 
             LEFT JOIN order_details b 
             ON a.OrderID=b.OrderID GROUP BY ProductID;

/*Create view of OrderID's of a Customer*/
DROP VIEW IF EXISTS `OrdersOfCustomer2`;
CREATE VIEW OrdersOfCustomer2 AS
SELECT OrderID FROM orders WHERE CustomerID='TOMSP';

/*Create view of Quantity of each product bought by a Customer*/
DROP VIEW IF EXISTS `Customer2`;
CREATE VIEW Customer2 AS
SELECT b.ProductID,sum(b.Quantity) AS C2 FROM OrdersOfCustomer2 a 
             LEFT JOIN order_details b 
             ON a.OrderID=b.OrderID GROUP BY ProductID;
             
/*Create view of OrderID's of a Customer*/
DROP VIEW IF EXISTS `OrdersOfCustomer3`;
CREATE VIEW OrdersOfCustomer3 AS
SELECT OrderID FROM orders WHERE CustomerID='HANAR';

/*Create view of Quantity of each product bought by a Customer*/
DROP VIEW IF EXISTS `Customer3`;
CREATE VIEW Customer3 AS
SELECT b.ProductID,sum(b.Quantity) AS C3 FROM OrdersOfCustomer3 a 
             LEFT JOIN order_details b 
             ON a.OrderID=b.OrderID GROUP BY ProductID;              
             
/*Join views of Revenues and Quantity of each product bought by Customers*/             
DROP VIEW IF EXISTS `ProductsVsCustomers`;
CREATE VIEW ProductsVsCustomers AS
SELECT d.ProductID, d.Revenues, e.C1, f.C2, g.C3 FROM RevenuesView d 
    LEFT JOIN Customer1 e ON d.ProductID=e.ProductID
    LEFT JOIN Customer2 f ON d.ProductID=f.ProductID
    LEFT JOIN Customer3 g ON d.ProductID=g.ProductID;
