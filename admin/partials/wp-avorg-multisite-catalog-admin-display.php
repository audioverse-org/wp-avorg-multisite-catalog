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

  <!-- <h2 class="nav-tab-wrapper">Tab</h2> -->
  

  <form method="post" name="cleanup_options" action="options.php">

    <?php
      $options = get_option($this->plugin_name);

      // $baseURLFormerAPI = isset($options['baseURLFormerAPI']) ? $options['baseURLFormerAPI'] : '';
      // $user = isset($options['user']) ? $options['user'] : '';
      // $password = isset($options['password']) ? $options['password'] : '';

      // $baseURL = isset($options['baseURL']) ? $options['baseURL'] : '';
      // $token = isset($options['token']) ? $options['token'] : '';

      $baseURLGraphQl = isset($options['baseURLGraphQl']) ? $options['baseURLGraphQl'] : '';

      $detailPageID = isset($options['detailPageID']) ? $options['detailPageID'] : '';
      $site = isset($options['site']) ? $options['site'] : '';
      $itemsPerPage = isset($options['itemsPerPage']) ? $options['itemsPerPage'] : '';

      $playerLibrary = isset($options['playerLibrary']) ? $options['playerLibrary'] : '';
      $playerLicense = isset($options['playerLicense']) ? $options['playerLicense'] : '';

      $overlayBackgroundColor = isset($options['overlayBackgroundColor']) ? $options['overlayBackgroundColor'] : '';
      $overlayHeight = isset($options['overlayHeight']) ? $options['overlayHeight'] : '';
      $descriptionColor = isset($options['descriptionColor']) ? $options['descriptionColor'] : '';
      $descriptionLines = isset($options['descriptionLines']) ? $options['descriptionLines'] : '';
    ?>

    <?php
      settings_fields( $this->plugin_name );
      do_settings_sections( $this->plugin_name );
    ?>

    <h2 class="title">API credentials</h2>
    <table class="form-table">
      <tbody>
        <!-- <tr>
          <th scope="row"><label for="baseURLFormerAPI">Base URL former API</label></th>
          <td><input name="<?php echo $this->plugin_name;?>[baseURLFormerAPI]" type="text" id="baseURLFormerAPI" aria-describedby="tag-description" value="<?php echo $baseURLFormerAPI;?>" class="regular-text">
        </tr> -->
        <!-- <tr>
          <th scope="row"><label for="user">User</label></th>
          <td><input name="<?php echo $this->plugin_name;?>[user]" type="text" id="user" value="<?php echo $user;?>" class="regular-text">
        </tr>
        <tr>
          <th scope="row"><label for="password">Password</label></th>
          <td><input name="<?php echo $this->plugin_name;?>[password]" type="password" id="password" value="<?php echo $password;?>" class="regular-text">
        </tr> -->
        <!-- <tr>
          <th scope="row"><label for="baseURL">Base URL new API</label></th>
          <td><input name="<?php echo $this->plugin_name;?>[baseURL]" type="text" id="baseURL" aria-describedby="tag-description" value="<?php echo $baseURL;?>" class="regular-text">
        </tr> -->
        <!-- <tr>
          <th scope="row"><label for="token">Bearer token</label></th>
          <td><input name="<?php echo $this->plugin_name;?>[token]" type="text" id="token" value="<?php echo $token;?>" class="regular-text">
        </tr> -->
        <tr>
          <th scope="row"><label for="baseURLGraphQl">Base URL GraphQl</label></th>
          <td><input name="<?php echo $this->plugin_name;?>[baseURLGraphQl]" type="text" id="baseURLGraphQl" aria-describedby="tag-description" value="<?php echo $baseURLGraphQl;?>" class="regular-text">
        </tr>
      </tbody>
    </table>
    <h2 class="title">Website settings</h2>
    <table class="form-table">
      <tbody>
        <tr>
          <th scope="row"><label for="detailPageID">Detail page ID</label></th>
          <td><input name="<?php echo $this->plugin_name;?>[detailPageID]" type="text" id="detailPageID" aria-describedby="tag-description" value="<?php echo $detailPageID;?>" class="regular-text">
          <p class="description" id="tag-description"><?php esc_attr_e( 'This is the page that will be shown when the user click a recording', $this->plugin_name ); ?></p></td>
        </tr>
        <tr>
          <th scope="row"><label for="site">Site ID</label></th>
          <td><input name="<?php echo $this->plugin_name;?>[site]" type="number" id="site" aria-describedby="tag-description" value="<?php echo $site;?>" class="regular-text">
        </tr>
        <tr>
          <th scope="row"><label for="itemsPerPage">Items per page</label></th>
          <td><input name="<?php echo $this->plugin_name;?>[itemsPerPage]" type="number" id="itemsPerPage" value="<?php echo $itemsPerPage;?>" class="regular-text">
        </tr>
      </tbody>
    </table>
    <h2 class="title">JWPlayer settings</h2>
    <table class="form-table">
      <tbody>
        <tr>
          <th scope="row"><label for="playerLibrary">Player library</label></th>
          <td><input name="<?php echo $this->plugin_name;?>[playerLibrary]" type="text" id="playerLibrary" aria-describedby="tag-description" value="<?php echo $playerLibrary;?>" class="regular-text">
        </tr>
        <tr>
          <th scope="row"><label for="playerLicense">Player licence</label></th>
          <td><input name="<?php echo $this->plugin_name;?>[playerLicense]" type="text" id="playerLicense" aria-describedby="tag-description" value="<?php echo $playerLicense;?>" class="regular-text">
        </tr>
      </tbody>
    </table>
    <h2 class="title">Grid settings</h2>
    <table class="form-table">
      <tbody>
        <tr>
          <th scope="row"><label for="overlayBackgroundColor">Overlay background color</label></th>
          <td><input name="<?php echo $this->plugin_name;?>[overlayBackgroundColor]" type="text" id="overlayBackgroundColor" aria-describedby="tag-description" value="<?php echo $overlayBackgroundColor;?>" class="regular-text">
        </tr>
        <tr>
          <th scope="row"><label for="overlayHeight">Overlay height</label></th>
          <td><input name="<?php echo $this->plugin_name;?>[overlayHeight]" type="text" id="overlayHeight" aria-describedby="tag-description" value="<?php echo $overlayHeight;?>" class="regular-text">
        </tr>
        <tr>
          <th scope="row"><label for="descriptionColor">Description color</label></th>
          <td><input name="<?php echo $this->plugin_name;?>[descriptionColor]" type="text" id="descriptionColor" value="<?php echo $descriptionColor;?>" class="regular-text">
        </tr>
        <tr>
          <th scope="row"><label for="descriptionLines">Description lines</label></th>
          <td><input name="<?php echo $this->plugin_name;?>[descriptionLines]" type="number" id="descriptionLines" value="<?php echo $descriptionLines;?>" class="regular-text">
        </tr>
      </tbody>
    </table>

    <?php submit_button(__('Save all changes', $this->plugin_name), 'primary','submit', TRUE); ?>

  </form>

</div>
