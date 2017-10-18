<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.audioverse.org
 * @since      1.0.0
 *
 * @package    Wp_Avorg_Multisite_Catalog
 * @subpackage Wp_Avorg_Multisite_Catalog/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">

  <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

  <h2 class="nav-tab-wrapper">Clean up</h2>

  <form method="post" name="cleanup_options" action="options.php">

    <?php
      //Grab all options
      $options = get_option($this->plugin_name);

      $tags = array_key_exists('tags', $options) ? $options['tags'] : '';
      $itemsPerPage = array_key_exists('itemsPerPage', $options) ? $options['itemsPerPage'] : '';
    ?>

    <?php
      settings_fields( $this->plugin_name );
      do_settings_sections( $this->plugin_name );
    ?>

    <table class="form-table">
      <tbody>
        <tr>
          <th scope="row"><label for="tags">Tags</label></th>
          <td><input name="<?php echo $this->plugin_name;?>[tags]" type="text" id="tags" aria-describedby="tag-description" value="<?php echo $tags;?>" class="regular-text">
          <p class="description" id="tag-description"><?php esc_attr_e( 'Comma separated tags.', $this->plugin_name ); ?></p></td>
        </tr>
        <tr>
          <th scope="row"><label for="itemsPerPage">Items per page</label></th>
          <td><input name="<?php echo $this->plugin_name;?>[itemsPerPage]" type="number" id="itemsPerPage" value="<?php echo $itemsPerPage;?>" class="regular-text">
        </tr>
      </tbody>
    </table>

    <?php submit_button(__('Save all changes', $this->plugin_name), 'primary','submit', TRUE); ?>

  </form>

</div>
