#!/bin/sh

rsync -rlptDzv --delete --delete-excluded --delete-after \
	--include ".htaccess" \
	--filter "P cache" \
	--filter "P config.php" \
	--filter "H config.php" \
	--exclude ".git*" \
	--exclude ".git/*" \
	--exclude "*.tmproj" \
	--exclude ".DS_Store" \
	--exclude "__OLD__*" \
	--exclude "cache/*" \
	--exclude "tests" \
	--exclude "scripts" \
	. \
	lemmon@37.9.171.73:Customers/alternativnamedicina.eu/www