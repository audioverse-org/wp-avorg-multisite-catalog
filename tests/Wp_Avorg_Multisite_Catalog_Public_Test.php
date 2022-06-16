<?php
use PHPUnit\Framework\TestCase;


function get_option($option)
{
    return 'value';
}

include('./public/class-wp-avorg-multisite-catalog-public.php');

final class Wp_Avorg_Multisite_Catalog_Public_Test extends TestCase {
//    /* @doesNotPerformAssertions */
//    public function test_get_catalog_url() {
//        $object = new Wp_Avorg_Multisite_Catalog_Public('name', 'version');
//        
//        $result = $object->format_recordings([
//            'recording_url' => 'https://example.com/recording.mp3'
//        ]);
//    }
    
    public function test_get_recordings() {
        $object = new Wp_Avorg_Multisite_Catalog_Public('name', 'version');
        
        $result = $object->get_recordings([]);
    }
}
