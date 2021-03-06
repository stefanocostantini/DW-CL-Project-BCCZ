<?php ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>MyApp</title>    
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<script>
/**
 * Given an element, or an element ID, blank its style's display
 * property (return it to default)
 */
function show(element) {
    if (typeof(element) != "object")	{
	element = document.getElementById(element);
    }
    
    if (typeof(element) == "object") {
	element.style.display = '';
    }
}

/**
 * Given an element, or an element ID, set its style's display property
 * to 'none'
 */
function hide(element) {
    if (typeof(element) != "object")	{
	element = document.getElementById(element);
    }
    
    if (typeof(element) == "object") {
	element.style.display = 'none';
    }
}

function show_content(optionsId) {
	var ids = new Array('home','data','analysis');
	show(optionsId);
	document.getElementById(optionsId + '_link').className = 'active';

	for (var i = 0; i < ids.length; i++)
	{
	    if (ids[i] == optionsId) continue;
	    hide(ids[i]);
	    document.getElementById(ids[i] + '_link').className = '';
	}
}
</script>
<body>


	<div id="header"><h1 style="color:#1C2A57;">ECOMMERCE ANALYSIS</h1></div>

	<div id="menu">
		<a id="home_link" href="#" class="active" onclick="show_content('home'); return false;">Home</a> &middot;
		<a id="data_link" href="#" onclick="show_content('data'); update_data_charts(); return false;">Data</a> &middot;
		<a id="analysis_link" href="#" onclick="show_content('analysis'); return false;">Analysis</a> 
	</div>
	

<div id="main">

<div id="home">
    
            
			<h3>The challenge</h3>
			<div class="text_home">
			<span class="texto">Every sales team seeks to identify and exploit opportunities to increase sales. In addition to acquiring new customers, another way to increase sales is to target existing customers by providing them with relevant offeres that might persuade them to purchase additional items. In order to be able to do that, a sales team would need to be able to build up offers that customers truly value and to identify those customers that are more likely to purchase new items. The first objective can be achieved by developing a recommendation engine which, based on a customer's intended purchases can recommend additional items that are related to those already in the shopping basket. The second objective can be achieved by analysing the average contribution that existing customers make across the entire product line. This is based on the idea that customers that already contribute to the company's revenues across a wider variety of products can more easily extend their purchases to additional items.
			<br><br></spam></div>
			
			<h3>The solution</h3>
			<div class="text_home">
			<span class="texto">
		    We have addressed the above challenges in two steps. First we have implemented a simple recommendation system, based on the <b><a href="http://www.wikipedia.org/wiki/Apriori_algorithm" target="_blank">Apriori algorithm.</a></b> This algorithm analyses existing transaction recorded in the database and develops associative rules between products, based on the frequency of them being purchased together. The output of the algorithm is a set of rules which link a set of two or more products (which are assumed to be already in the customer's basket) with an additional product that the customer is recommended to buy.
			<br><br>
			To address the second part of the challenge, i.e. to identify the company's "best customers", we have carried out a lasso regression of each customers purchases on the revenues associated to each product. We have used the results of this regression to rank customers according to their <b>average contribution</b> across a variety of products. In this way, given the same level of revenues generated, customers that buy across a wider range of products are considered to be more promising for further upselling compared to customer that only buy a limited set of products. We have used lasso regression given the high number of features in the regression.
			</spam>                  
		    </div>	
		    
		    <?php include 'data_and_analysis.php' ?>
	
</div>

<br><br><br><br>
<br><br><br><br>
<div id="footer">
    <div class="gray_line"> &nbsp; </div>
    <span class="textogeneral" style="font: 1em Arial, Verdana, Sans-serif;">
    <center>Project team: Gaston Besanson, Stefano Costantini, Laura Cozma, Jordi Zamora Munt</center></span>
</div>

</div>
</body>
</html>
<?php ?>
