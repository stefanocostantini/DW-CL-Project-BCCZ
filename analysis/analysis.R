library(RMySQL)

#### IMPLEMENTATION OF APRIORI ALGORITHM ####

library(arules)

#Connection to SQL
db = dbConnect(MySQL(), user='root', password='root', dbname='ecommerce', host='localhost')

#Run query of Interest
result = dbSendQuery(db, "select distinct(a.OrderID), b.ProductName , b.ProductID from order_details a join products b on a.ProductID = b.ProductID order by a.OrderID")
data = fetch(result, n=-1)

#Prepare data for arule fuction
b<-split(data$ProductName, data$OrderID)
c<-as(b, "transactions")

#Finding rules
rules<-apriori(c, parameter=list(supp=0.002, conf=0.8))
inspect(rules)

# Turning output into required form
d<-as(rules, "data.frame")
out<-as.data.frame(d[1:20,1])
colnames(out)<-"Rules"

#Exporting SQL table
dbSendQuery(db,"drop table if exists apriori")
dbWriteTable(conn = db,name="apriori", value=out, row.names=FALSE)

#### IMPLEMENTATION OF LASSO REGRESSION ####

#Extract data from data set
result = dbSendQuery(db, "select * from ProductsVsCustomers_Pivot")
dataPCsC = fetch(result, n=-1)
result1 = dbSendQuery(db, "SELECT b.CustomerID Customer, sum(a.Quantity*a.UnitPrice) Amount, count(b.OrderID) N_Orders from order_details a left join orders b on a.OrderID=b.OrderID group by CustomerID order by Amount desc limit 20;")
dataPVsC = fetch(result1, n=-1)

#View(dataPVsC)
dataPVsC[,1]
y<-as.matrix(dataPCsC[,3])
x <- dataPCsC[,c(dataPVsC[,1])] # Using best 20 customers
row.names(x)<-dataPCsC[,2]
x[is.na(x)] <- 0
z <- as.matrix(x)


soft.threshold <- function(x,lambda){
  sign(x)*max( c( abs(x) - lambda , 0 ) )
}

lasso.shooting <- function(y,X,lambda){
  
  max.iter <- 10;
  P        <- ncol(X);
  
  beta       <- solve(t(X)%*%X,t(X)%*%y)
  beta.prev  <- beta
  
  for( iter in 1:max.iter ){
    for( i in 1:P ){
      
      y.aux <- y-X[,setdiff(1:P,i)]%*%beta[setdiff(1:P,i)]
      x.aux <- X[,i]
      
      cov <- sum( y.aux*x.aux )
      var <- sum( x.aux*x.aux )
      
      beta[i] <- soft.threshold( cov/var , lambda/(2*var) )
      
      if( sum( (beta-beta.prev)**2 ) < 1e-6 ){ return(beta) }
      
      beta.prev <- beta
    }    
  }
  
  beta
}

ev <- eigen(t(z)%*%z)$values
lambda <- max(ev)

## Lasso Coef using Shooting Lasso
lasso.coef<-as.data.frame(lasso.shooting(y,z,lambda))

## Lasso Coef using Package
library(glmnet)
set.seed(1)
cv <- cv.glmnet(z, y, nfolds = 10)
cv$lambda.min
mdl <- glmnet(z, y, lambda = cv$lambda.min)
func<-as(mdl$beta, "matrix")

TableFinal<-cbind(func,lasso.coef)

#Exporting SQL table
dbSendQuery(db,"drop table if exists top_customers")
dbWriteTable(conn = db,name="top_customers", value=TableFinal, row.names=FALSE)

##### TESTING IMAGE SAVING

o <- c(1,2,3,4)
p <- c(2,5,2,3)
png("categories.png")
plot(p,o)
dev.off()


