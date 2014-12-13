library(RMySQL)
library(arules)


#Connection to SQL
db = dbConnect(MySQL(), user='root', password='root', dbname='ecommerce', host='localhost')

#Query of Interest
result = dbSendQuery(db, "select distinct(a.OrderID), b.ProductName , b.ProductID from order_details a join products b on a.ProductID = b.ProductID order by a.OrderID")
data = fetch(result, n=-1)

#Prepare data for arule fuction
b<-split(data$ProductName, data$OrderID)
c<-as(b, "transactions")

#Finding rules
rules<-apriori(c, parameter=list(supp=0.002, conf=0.8))
inspect(rules)

# Turning output into Data.Frame
d<-as(rules, "data.frame")
output<-as.character(d[,1])


out<-as.data.frame(d[,1])
colnames(out)<-"Rules"


#Exporting SQL

dbWriteTable(conn = db,name="apriori", value=out, row.names=FALSE)