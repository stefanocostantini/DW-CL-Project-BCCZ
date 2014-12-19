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

## Lasso Coef using Package
library(lars)

set.seed(1)
lasso<-lars(z,y, type = "lasso",trace=TRUE, use.Gram = TRUE)
cv.lasso<-cv.lars(z,y, type="lasso")
limit<-min(cv.lasso$cv)+cv.lasso$cv.error[which.min(cv.lasso$cv)]
s.cv<-cv.lasso$index[min(which(cv.lasso$cv<limit))]
lasso.coef<-as.data.frame(coef(lasso, s = s.cv, mode="fraction"))
colnames(lasso.coef)<-c("Coefficient")
vvv<-cbind(Customer=rownames(lasso.coef),lasso.coef)
rownames(vvv)<-NULL
TableFinal<-vvv[order(-vvv$Coefficient),]

#Exporting SQL table
dbSendQuery(db,"drop table if exists top_customers")
dbWriteTable(conn = db,name="top_customers", value=TableFinal, row.names=FALSE)

##### TESTING IMAGE SAVING (to be replaced with Laura's code)

o <- c(1,2,3,4)
p <- c(2,5,2,3)
png("categories.png")
plot(p,o)
dev.off()


