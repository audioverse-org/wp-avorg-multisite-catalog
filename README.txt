=== Plugin Name ===
Contributors: pobrejuanito, alangumer
Donate link: https://www.audioverse.org
Tags: API, AudioVerse, Stories
Requires at least: 3.0.1
Tested up to: 3.4
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Access to the AudioVerse Catalog by tags, using WP shortcodes.

== Description ==

This plugin allows to connect to the AudioVerse.org API to pull recordings filtered by tags and provides a set of shortcodes to display and format the data.

Site ID : 

AudioVerse : 1
Journeys Unscripted : 2 

Shortcodes:

[recording_media]
Shows the media player with the recording ready to play
[recording_title]
Shows the title of the recording
[recording_desc]
Shows the description of the recording
[recording_speaker]
Shows the name of the speaker
[list]
Shows the list of recordings for the current site.
[list tags=”editors”] can accept parameters, known as attributes:
tags=”editors” the value of tags could be one value or a comma separated list of values.


== Installation ==

1. Upload `wp-avorg-multisite-catalog.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to the plugin settings

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

== Tests ==

composer install
./vendor/bin/phpunit ./tests/

== Changelog ==

= 1.0 =
* Initial version

== Upgrade Notice ==
