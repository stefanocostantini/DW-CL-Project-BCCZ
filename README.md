# Product recommendation and customer analysis
### Data Science Project

##Project team: Gaston Besanson, Stefano Costantini, Laura Cozma & Jordi Zamora Munt

### Overview

For this project, we have implemented a product recommendation algorithm and carried out customer analysis using LASSO regression. We had to objectives:

- Develop a set of product recommendation rules, based on the Apriori algorithm
- Rank customers on the basis of their marginal contribution to revenues.

We believe that this information could be used by a marketing department to develop more targeted campaigns.

### Structure

The core of the analysis is contained in this two files:

- `data_and_analysis.php`
- `analysis.R`

Note that some of the key `SQL` queries, to obtain the data for the analysis but also to create a network graph, are contained in the `R` file.

### Implementation

To develop the product recommendation system we have used the Apriori algorithm. We provide a link to the relevant Wikipedia article on the introductory page of the web application.

To develop the LASSO regression, we first have identified the top 20 customers in terms of total revenues generated. Then we have carried out a LASSO regression using the `lars` package with the objective to narrow down the number of customers with a significant marginal contribution to revenues. 

### Required packages

The `R` analysis relies on the following packages. 

- `igraph`
- `apriori`
- `lars`

Note that the installation of the `igraph` package could require 2-3 minutes of the virtual machine.



