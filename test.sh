#docker-compose run --rm test

#docker exec --network container:wp-avorg-multisite-catalog_wp_1 .

docker build -t wptest .
docker run -d wptest --rm --network container:wp-avorg-multisite-catalog_wp_1

#docker run -it --rm \
#	--volume $PWD/wp-config.php:/var/www/html/wp-config.php \
#	--network container:wp-avorg-multisite-catalog_wp_1 \
#  -e WORDPRESS_DB_HOST=db \
#  -e WORDPRESS_DB_USER=dbuser \
#  -e WORDPRESS_DB_PASSWORD=dbpass \
#  -e WORDPRESS_DB_NAME=wordpress \
#  -e WORDPRESS_DEBUG=1 \
#  -e WP_TESTS_DIR=/var/www/html/wp-content/plugins/wp-avorg-multisite-catalog/tests \
#	php:latest \
#	wp-content/plugins/wp-avorg-multisite-catalog/vendor/bin/phpunit \
#	--configuration wp-content/plugins/wp-avorg-multisite-catalog/phpunit.xml.dist \

#docker run -it --rm \
#	--volumes-from wp-avorg-multisite-catalog_wp_1 \
#	--network container:wp-avorg-multisite-catalog_wp_1 \
#  -e WORDPRESS_DB_HOST=db \
#  -e WORDPRESS_DB_USER=dbuser \
#  -e WORDPRESS_DB_PASSWORD=dbpass \
#  -e WORDPRESS_DB_NAME=wordpress \
#  -e WORDPRESS_DEBUG=1 \
#  -e WP_TESTS_DIR=/var/www/html/wp-content/plugins/wp-avorg-multisite-catalog/tests \
#	wordpress:latest \
#	wp-content/plugins/wp-avorg-multisite-catalog/vendor/bin/phpunit \
#	--configuration wp-content/plugins/wp-avorg-multisite-catalog/phpunit.xml.dist \
