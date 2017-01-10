<?php
/**
 * Admin settings page.
 */

class ascSettingsPage {
  /**
  * Holds the values to be used in the fields callbacks
  */
  private $options;

  /**
  * Start up
  */
  public function __construct() {
    add_action('admin_menu', array($this, 'asc_add_plugin_page' ));
    add_action('admin_init', array($this, 'asc_page_init'));
  }

  /**
  * Add options page
  */
  public function asc_add_plugin_page() {
    // This page will be under "Settings"
    add_options_page(
      __('Appointment Scheduling Cron Settings', 'asc'),
      __('Appointment Scheduling Cron', 'asc'),
      'manage_options',
      'asc-setting-admin',
      array($this, 'asc_reate_admin_page')
    );
  }

  /**
  * Options page callback
  */
  public function asc_reate_admin_page() {
    // Set class property
    $this->options = get_option('asc_board_settings');

    ?>
    <div class="wrap">
      <h1><?php echo __('Appointment Scheduling Cron Plugin settings', 'asc') ?></h1>
      <form method="post" action="options.php">
      <?php
        // This prints out all hidden setting fields
        settings_fields('asc_option_config');
        do_settings_sections('asc-setting-admin');
        submit_button();
      ?>
      </form>
      <h2><?php echo __('Update content from Appointment Scheduling', 'asc') ?></h2>
      <form id="update_appointment" name="update_appointment" method="post" action="">
        <input class="button button-primary" type="submit" name="action" value="Update Appointment">
      </form>
    </div>
    <?php
  }

  /**
  * Register and add settings
  */
  public function asc_page_init() {
    register_setting('asc_option_config', 'asc_board_settings');

    // Setting ID
    add_settings_section(
      'asc_section_id', // ID
      __('Schedule API', 'asc'), // Title
      array( $this, 'asc_print_section_info' ), // Callback
      'asc-setting-admin' // Page
    );

    add_settings_field(
      'asc_user_id',
      __('User ID API <br><i style="font-size: 10px; color: #72777c; font-weight: 400;">Get User ID in: <a href="https://secure.acuityscheduling.com/app.php?action=settings&key=api" target="_blank">Click Here</a></i>', 'asc'),
      array( $this, 'asc_form_textfield' ), // Callback
      'asc-setting-admin', // Page
      'asc_section_id',
      'asc_user_id'
    );

    add_settings_field(
      'asc_user_key',
      __('User Key API <br><i style="font-size: 10px; color: #72777c; font-weight: 400;">Get User Key in: <a href="https://secure.acuityscheduling.com/app.php?action=settings&key=api" target="_blank">Click Here</a></i>', 'asc'),
      array( $this, 'asc_form_textfield' ), // Callback
      'asc-setting-admin', // Page
      'asc_section_id',
      'asc_user_key'
    );
  }

  /**
  * Print the Section text
  */
  public function asc_print_section_info() {
    echo __("", 'asc');
  }

  /**
  * Get the settings option array and print one of its values
  */
  public function asc_form_textfield($name) {
    $value = isset($this->options[$name]) ? esc_attr($this->options[$name]) : '';
    printf('<input type="text" size=60 id="form-id-%s" name="asc_board_settings[%s]" value="%s" />', $name, $name, $value);
  }

  public function asc_form_textarea($name) {
    $value = isset($this->options[$name]) ? esc_attr($this->options[$name]) : '';
    printf('<textarea cols="100%%" rows="3" type="textarea" id="form-id-%s" name="asc_board_settings[%s]">%s</textarea>', $name, $name, $value);
  }
}
