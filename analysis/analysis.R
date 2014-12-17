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
