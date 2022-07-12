# This script uses the Docker WordPress CLI image.
# https://hub.docker.com/_/wordpress/
# https://developer.wordpress.org/cli/commands/

docker run -td \
  --name "tmp-cli" \
  --volumes-from av_wp_web \
  --network container:av_wp_web \
  -e WORDPRESS_DB_USER=dbuser \
  -e WORDPRESS_DB_PASSWORD=dbpass \
  -e WORDPRESS_DB_HOST=db \
  -e WORDPRESS_DB_NAME=wordpress \
  wordpress:cli

run () { docker exec -t tmp-cli "$@"; }
wp () { run wp "$@"; }

get_post_id () {
  wp post list \
    --title="$1" \
    --post_type=page \
    --post_status=publish \
    --posts_per_page=1 \
    --format=ids
}

create_post() {
  wp post create \
    --post_title="$1" \
    --post_content="$2" \
    --post_type=page \
    --post_status="publish"
}

wp core install \
  --url=http://localhost:8888 \
  --title="AudioVerse Local" \
  --admin_user=admin \
  --admin_password=password \
  --admin_email=technical@audioverse.org \
  --skip-email
  	
wp plugin activate wp-avorg-multisite-catalog

get_post_id "List" &>/dev/null || create_post "List" "[list]"

get_post_id "Detail" &>/dev/null || create_post "Detail" \
  "[recording_title][recording_speaker][recording_desc][recording_media]"

LIST_ID=$(get_post_id "List")
DETAIL_ID=$(get_post_id "Detail")

wp option update show_on_front "page"
wp option update page_on_front "$LIST_ID"

OPTIONS=$(sed "s/DETAIL_PAGE_ID/$DETAIL_ID/g" < ./init.dev.json)

wp option get wp-avorg-multisite-catalog || \
  wp option update --format=json wp-avorg-multisite-catalog "$OPTIONS"

docker rm --force tmp-cli
