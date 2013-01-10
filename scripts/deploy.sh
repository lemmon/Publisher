#!/bin/sh

rsync -rlptDzv --delete --delete-excluded --delete-after \
	--include ".htaccess" \
	--filter "P cache" \
	--filter "P config.php" \
	--filter "H config.php" \
	--filter "P user/uploads" \
	--filter "H user/uploads" \
	--exclude ".git*" \
	--exclude ".git/*" \
	--exclude "*.tmproj" \
	--exclude ".DS_Store" \
	--exclude "__OLD__*" \
	--exclude "cache/*" \
	--exclude "tests" \
	--exclude "scripts" \
	--exclude "user/uploads" \
	. \
	lemmon@37.9.171.73:Customers/alternativnamedicina.eu/www