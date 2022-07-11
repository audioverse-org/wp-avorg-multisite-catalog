docker run -td \
    --name "tmp-cli" \
    --volumes-from av_wp_web \
    --network container:av_wp_web \
    -e WORDPRESS_DB_USER=dbuser \
    -e WORDPRESS_DB_PASSWORD=dbpass \
    -e WORDPRESS_DB_HOST=db \
    -e WORDPRESS_DB_NAME=wordpress \
    wordpress:cli

run () { docker exec -i tmp-cli "$@"; }
wp () { run wp "$@"; }

wp core install --url=http://localhost:8888 \
  	--title="AV WP" \
  	--admin_user=admin \
  	--admin_password=password \
  	--admin_email=technical@audioverse.org \
  	--skip-email
  	
wp plugin activate wp-avorg-multisite-catalog

wp option get wp-avorg-multisite-catalog || wp option update --format=json wp-avorg-multisite-catalog < ./init.dev.json

docker rm --force tmp-cli
