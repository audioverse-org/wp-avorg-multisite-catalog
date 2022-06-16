docker run -it --rm \
	--volumes-from wp-avorg-multisite-catalog_wp_1 \
	--network container:wp-avorg-multisite-catalog_wp_1 \
  -e WORDPRESS_DB_HOST=db \
  -e WORDPRESS_DB_USER=dbuser \
  -e WORDPRESS_DB_PASSWORD=dbpass \
  -e WORDPRESS_DB_NAME=wordpress \
  -e WORDPRESS_DEBUG=1 \
	wordpress:cli pwd

#docker run -it --rm \
#	--volumes-from wp-avorg-multisite-catalog_wp_1 \
#	--network container:wp-avorg-multisite-catalog_wp_1 \
#  -e WORDPRESS_DB_HOST=db \
#  -e WORDPRESS_DB_USER=dbuser \
#  -e WORDPRESS_DB_PASSWORD=dbpass \
#  -e WORDPRESS_DB_NAME=wordpress \
#  -e WORDPRESS_DEBUG=1 \
#	wordpress:cli scaffold plugin-tests wp-avorg-multisite-catalog
