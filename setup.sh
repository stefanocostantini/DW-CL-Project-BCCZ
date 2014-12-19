#!/bin/bash

# installion script

cmd=$1

user=`grep dbuser service.conf | cut -f2 -d' '`
pswd=`grep dbpswd service.conf | cut -f2 -d' '`

case $cmd in

install)
	echo "Installing"

	mysql -u $user -p$pswd < db/ecommerce.sql
	mysql -u $user -p$pswd < data/ecommerce-dump.sql
	mysql -u $user -p$pswd < analysis/Customers_by_product.sql

	mkdir -p "$HOME/public_html/MyApp"
	cp -rf web/* "$HOME/public_html/MyApp"

	echo "done!"
	;;

uninstall)
	echo "Uninstalling"
	
	mysql -u $user -p$pswd -e "DROP DATABASE ecommerce;" 
	rm -rf "$HOME/public_html/MyApp"

	echo "done!"
	;;

run)
	echo "Running"
	R CMD BATCH --vanilla analysis/analysis.R 
	cat analysis.Rout
	rm analysis.Rout
	cp web/categories_network.png "$HOME/public_html/MyApp"

	;;

*)
	echo "Unknown Command!"

esac
