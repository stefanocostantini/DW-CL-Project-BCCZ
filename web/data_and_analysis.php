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
<?php
	// Page body. Write here your queries
	$query = "SELECT P1.ProductID as ProductID1, P1.ProductName as ProductName1,
       P2.ProductID as ProductID2, P2.ProductName as ProductName2,
       Count(DISTINCT O1.OrderID) as NumberOfOccurences
	   FROM ecommerce.products P1
       JOIN ecommerce.products P2
         ON P1.ProductID != P2.ProductID
       LEFT JOIN ecommerce.order_details O1
       INNER JOIN ecommerce.order_details O2
       ON O1.OrderID = O2.OrderID
       ON O1.ProductID = P1.ProductId
       AND O2.ProductID = P2.ProductID            
		GROUP  BY P1.ProductID, P2.ProductID
		HAVING COUNT(DISTINCT O1.OrderID)>=5
		order by COUNT(DISTINCT O1.OrderID) DESC";
	$title = "Pairs of products frequently purchased together";
	echo "The table below show a ranking of pairs of products that tend to be purchased together. The pairs of products are ranked according to the number of times each pair appears in a transaction. To focus on the most relevant information, we show only the product pairs that appear at least five times. While this information does not, on its own, provide a recommendation system, it can provide insight on customers' behaviour";
	query_and_print_table($query,$title);

	$query = "SELECT * FROM ecommerce.products LIMIT 10";
	$title = "Product list (10 elements)";
	query_and_print_table($query,$title);
	echo "Comment 2";

	// query_and_print_graph: Needs two columns: the first one with labels, the second one with values of the graph
	$query = "SELECT ProductName, UnitPrice FROM ecommerce.products ORDER BY UnitPrice DESC LIMIT 10";
	$title = "Top products";
	query_and_print_graph($query,$title,"Euros");
	echo "Comment 3";

	$query = "SELECT ProductName, UnitPrice FROM ecommerce.products ORDER BY UnitPrice ASC LIMIT 10";
	$title = "Top cheap products";
	query_and_print_graph($query,$title,"Euros");
	echo "Comment 4";
?>
		</div>
		<div id="analysis" style="display: none">
			<h2>Analysis</h2>
			<p>Below we show the top 20 product recommendation rules identified by the <b>Apriori algorithm</b>. The table can be read as follows: for each rule, the left-hand side shows a potential basket that the customer has put together, while the right-hand side shows the additional product that could be purchased to "complete the basket". For example, the first rule indicates that a customer that has already added dried applies and sild (herring) to her basket, would be recommended gorgonzola cheese <em>(note: it sounds disgusting but the customer is always right!)</em> The recommendations are based on the analysis of historical transaction already stored in the database.</p>
<?php

	$query = "SELECT * FROM ecommerce.apriori";
	$title = "Recommendation rules";
	query_and_print_table($query,$title);
	echo "Comment 3";
?>
	<p>The table below shows the results of the lasso regression. Due to the quantity of response variables, we do not show the detailed regression results. Instead, we show a list of the top 20 customers identified by our analysis. Customers are ranked on the basis of the extent to which they contribute to the company's revenue across a wide variety of products. That is, given the same revenue contribution, a customer buying a wider range of products would be ranked above one buying a narrower one. We believe that this analysis would help the sale team identify the most promising customers for their marketing activities to target, perhaps in combination with the results of the recommendation engine above.</p>


		</div>
<?php
	// Close connection
	mysql_close($link);
?>
