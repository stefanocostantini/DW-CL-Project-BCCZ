<?php

	include 'functions.php';
	$GLOBALS['graphid'] = 0;

	// Load libraries
	document_header();

	// Create connection
	$link = connect_to_db();
?>
	<div id="data" style="display: none">
	
	<h2>Data</h2>
	
	<p>In this section we carry out an initial analysis of past transaction, with the objective of gathering information about the categories, products and customers that tend to generate the highest revenues. The results shown in this page can provide insights to inform the activities of the sales team. This information, together with the recommendation system and customer analysis which we have implemented in the next page, can support the activities of the company's marketing team.</p>

<?php
	// Page body. Write here your queries
	
	$query = "SELECT C1.CategoryName, SUM(O1.UnitPrice*O1.Quantity) as Revenue
 			  FROM ecommerce.products P1
       		  JOIN ecommerce.categories C1
         	  ON P1.CategoryID= C1.CategoryID
         	  LEFT JOIN ecommerce.order_details O1
          	  ON O1.ProductID = P1.ProductId
 			  GROUP BY CategoryName
 			  ORDER BY Revenue DESC";
	$title = "Product categories by revenues";
	query_and_print_graph($query,$title,"Euros");
?>

	<p> The chart above shows the product categories ranked according to the revenues they generate. As shown in the chart, the top three categories (Confections, Dairy Products and Beverages) account for more than half of total revenues </p>
	
	<p>The chart below carries out the same analysis, this time to rank the customers that contribute the most to total revenues. Only the top 20 customers are shown below</p>
	
<?php
	// Page body. Write here your queries
	
	$query = "SELECT b.CustomerID Customer, sum(a.Quantity*a.UnitPrice) Revenues from ecommerce.order_details a left join ecommerce.orders b on a.OrderID=b.OrderID group by CustomerID order by Revenues desc limit 20";
	$title = "Customers by revenues";
	query_and_print_graph($query,$title,"Euros");
?>

	<p> We now consider associations between product categories as observed in past transactions. Specifically, the analysis below ranks the pairs of categories according to the number of times they are bought together</p>
	
<?php
	// Most sold categories pairs
	$query = "SELECT 
       		  C1.CategoryName as Category_1, 
       		  C2.CategoryName as Category_2,
       		  Count(DISTINCT O1.OrderID) as Number_of_occurences
			  FROM ecommerce.products P1
       		  JOIN ecommerce.products P2
         	  ON P1.ProductID != P2.ProductID 
        	  JOIN ecommerce.categories C1 on P1.CategoryID=C1.CategoryID 
        	  JOIN ecommerce.categories C2 on P2.CategoryID=C2.CategoryID
       		  LEFT JOIN ecommerce.order_details O1
              INNER JOIN ecommerce.order_details O2
              ON O1.OrderID = O2.OrderID
         	  ON O1.ProductID = P1.ProductId
              AND O2.ProductID = P2.ProductID 
  			  WHERE P1.CategoryID > P2.CategoryID          
			  GROUP  BY P1.CategoryID, P2.CategoryID
			  ORDER BY Number_of_occurences DESC";
	$title = "Pairs of product categories frequently purchased together";
	query_and_print_table($query,$title);
?>
	
	<p> Finally, the table below show a ranking of pairs of products that tend to be purchased together. The pairs of products are ranked according to the number of times each pair appears in a transaction. To focus on the most relevant information, we show only the product pairs that appear at least five times. While this information does not, on its own, provide a recommendation system, it can provide insight on customers behaviour</p>

<?php

	// Most sold product pairs
	
	$query = "SELECT
			  P1.ProductName as Product_1,
       		  P2.ProductName as Product_2,
       		  Count(DISTINCT O1.OrderID) as Number_of_occurrences
			  FROM ecommerce.products P1
       		  JOIN ecommerce.products P2
         	  ON P1.ProductID != P2.ProductID
       		  LEFT JOIN ecommerce.order_details O1
              INNER JOIN ecommerce.order_details O2
                ON O1.OrderID = O2.OrderID
         		ON O1.ProductID = P1.ProductId
            	AND O2.ProductID = P2.ProductID 
			  WHERE P1.ProductID > P2.ProductID
              GROUP BY P1.ProductID, P2.ProductID
              HAVING COUNT(DISTINCT O1.OrderID)>=5
			  ORDER BY Count(DISTINCT O1.OrderID) DESC";
	$title = "Pairs of products frequently purchased together";
	query_and_print_table($query,$title);

?>
	</div>
	<div id="analysis" style="display: none">
	<h2>Analysis</h2>
			
	<p>Below we show the top 20 product recommendation rules identified by the <b>Apriori algorithm</b>. The table can be read as follows: for each rule, the left-hand side shows a potential basket that the customer has put together, while the right-hand side shows the additional product that could be purchased to "complete the basket". For example, the first rule indicates that a customer that has already added dried applies and sild (herring) to her basket, would be recommended gorgonzola cheese <em>(note: it sounds disgusting but the customer is always right!)</em> The recommendations are based on the analysis of historical transaction already stored in the database.</p>

<?php

	$query = "SELECT * FROM ecommerce.apriori";
	$title = "Recommendation rules";
	query_and_print_table($query,$title);
	echo "";
?>
	<p>The table below shows the results of the lasso regression. Due to the quantity of response variables, we do not show the detailed regression results. Instead, we show a list of the top 20 customers identified by our analysis. Customers are ranked on the basis of the extent to which they contribute to the company's revenue across a wide variety of products. That is, given the same revenue contribution, a customer buying a wider range of products would be ranked above one buying a narrower one. We believe that this analysis would help the sale team identify the most promising customers for their marketing activities to target, perhaps in combination with the results of the recommendation engine above.</p>


		</div>
<?php
	// Close connection
	mysql_close($link);
?>
