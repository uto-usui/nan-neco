<?php
/*
Plugin Name: WP Facebook Portal
Plugin URI: http://wordpress.org/plugins/wp-facebook-portal/
Description: Import the posts of Facebook page.
Author: Yoshika (@rnsk)
Author URI: http://rnsk.net/
Version: 2.3.2
License: GPL2
License URI: license.txt
Text Domain: wpfb
Domain Path: /languages/
*/

if (!defined('ABSPATH')) exit;

// 定数設定
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}

// Start up this plugin
new FacebookPortal;

/**
 * Facebook ページのフィードをインポート（複数ページ対応）
 *
 * @copyright  Copyright (c) Yoshika
 * @author     Yoshika (@rnsk)
 * @package    FacebookPortal
 * @license    GPL2
 */
class FacebookPortal
{

/**
 * Plugin version
 *
 * @var string
 */
    const VERSION = '2.3.2';

/**
 * Plugin DB version
 *
 * @var string
 */
    const DB_VERSION = '2.3';

/**
 * Plugin text domain
 *
 * @var string
 */
    const TEXT_DOMAIN = 'wpfb';

/**
 * Database table name
 *
 * @var string
 */
    const DB_TABLE = 'facebook_pages';

/**
 * WP Option name of the plugin version
 *
 * @var string
 */
    const OPTION_VERSION = 'wpfb_version';

/**
 * WP Option name of the plugin DB version
 *
 * @var string
 */
    const OPTION_DB_VERSION = 'wpfb_db_version';

/**
 * WP Option names
 *
 * @var string
 */
    const OPTION_FB_APP_ID = 'wpfb_app_id';
    const OPTION_FB_APP_SECRET = 'wpfb_app_secret';
    const OPTION_FB_ACCESS_TOKEN = 'wpfb_access_token';

/**
 * File path of this plugin
 *
 * @var string
 */
    const PLUGIN_FILE = __FILE__;

/**
 * Construct
 *
 * @return void
 */
    public function __construct()
    {
        session_start();
        ob_start();

        // プラグイン用翻訳ファイル読み込み
        load_plugin_textdomain(self::TEXT_DOMAIN, false, dirname(plugin_basename(__FILE__)) . '/languages/');

        // プラグイン有効時の処理
        register_activation_hook(__FILE__, array($this, 'pluginActive'));

        // プラグイン無効時の処理
        register_deactivation_hook(__FILE__, array($this, 'pluginDeactive'));

        // プラグインアンインストール時の処理（静的なクラスメソッドまたは関数のみ設定可）
        register_uninstall_hook(__FILE__, array(__CLASS__, 'pluginUninstall'));

        // プラグイン読み込み時の処理
        add_action('plugins_loaded', array($this, 'pluginLoaded'));

        // ファイル読み込み
        if (is_admin()) {
            $class = __CLASS__ . 'Admin';
            $file = 'controller-admin.php';
            if (defined('DOING_AJAX') && DOING_AJAX) {
                //$class = __CLASS__ . 'AdminAjax';
                //$file = 'controller-admin_ajax.php';
            }
            $this->uses($class, $file);
        }
        $this->uses('FacebookPortalCron', 'class-cron.php');

        // Cron job
        add_filter('cron_schedules', array($this, 'cronInterval'));
    }

/**
 * Plugin actived
 *
 * @return void
 */
    public function pluginActive()
    {
        $installed_version = get_option(self::OPTION_VERSION);
        if ($installed_version != self::VERSION) {
            $this->dbCheck();

            update_option(self::OPTION_VERSION, self::VERSION);
        }

        // Cron Job
        if (!wp_next_scheduled('wpfb_auto_update')) {
            wp_schedule_event(time(), '10min', 'wpfb_auto_update');
        }
    }

/**
 * Plugin deactived
 *
 * @return void
 */
    public function pluginDeactive()
    {
        // Cron Job
        wp_clear_scheduled_hook('wpfb_auto_update');
    }

/**
 * Plugin uninstalled
 *
 * @return void
 */
    public static function pluginUninstall()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::DB_TABLE;
        if (!$wpdb->query("DROP TABLE $table_name")) {
            $message = __('Failed to delete the database table.', self::TEXT_DOMAIN);
            add_action('admin_notices', array($this, 'errorNotice', 10, $message));
            exit;
        }

        // WP Options
        delete_option(self::OPTION_FB_APP_ID);
        delete_option(self::OPTION_FB_APP_SECRET);
        delete_option(self::OPTION_FB_ACCESS_TOKEN);

        delete_option(self::OPTION_VERSION);
        delete_option(self::OPTION_DB_VERSION);
    }

/**
 * Plugin loaded
 *
 * @return void
 */
    public function pluginLoaded()
    {
        $this->dbCheck();
    }

/**
 * Cron Interval
 *
 * @param array $schedules Already set cron schedule
 * @return array
 */
    public function cronInterval($schedules)
    {
        $schedules['10min'] = array(
            'interval' => 600,
            'display'  => __('Every 10 Minutes')
        );
        return $schedules;
    }

/**
 * Load the file and create a new instance
 *
 * @param string $class Name of the class
 * @param string $file Name of the PHP file
 * @param mixed $params Parameters to the constructor of class
 * @return object
 */
    public static function uses($class, $file, $dir = 'core', $params = null)
    {
        $filePath = dirname(__FILE__) . DS . $dir . DS . $file;
        if (file_exists($filePath)) {
            if (!class_exists($class)) {
                require_once $filePath;
            }
            $instance = new $class($params);
            return $instance;
        }
    }

/**
 * Check the version of the database
 *
 * @return void
 */
    protected function dbCheck()
    {
        $installed_db_version = get_site_option(self::OPTION_DB_VERSION);
        if ($installed_db_version != self::DB_VERSION) {
            $this->create_db_table();
        }
    }

/**
 * Create a database table
 *
 * @return void
 */
    protected function create_db_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::DB_TABLE;

        $query = <<< EOF
CREATE TABLE {$table_name} (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(100) DEFAULT NULL,
  facebook_page_id varchar(100) DEFAULT NULL,
  username varchar(50) DEFAULT NULL,
  page_url text,
  pic text,
  post_author int(11) DEFAULT NULL,
  post_category text,
  tags_input text,
  image_type varchar(10) DEFAULT 'attachment',
  image_size varchar(10) DEFAULT 'thumbnail',
  link_text tinyint(1) unsigned DEFAULT '0',
  auto_link tinyint(1) unsigned DEFAULT '0',
  post_updated int(20) DEFAULT NULL,
  created datetime DEFAULT NULL,
  updated datetime DEFAULT NULL,
  UNIQUE KEY id (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
EOF;

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($query);

        update_option(self::OPTION_DB_VERSION, self::DB_VERSION);
    }

/**
 * Show an error notice
 *
 * @return void
 */
    protected function errorNotice($message = null)
    {
        if (is_null($message)) return;

        echo '<div class="error"><p>' .
            '<strong>' . __('Attention:', self::TEXT_DOMAIN) . '</strong> ' .
            $message .
            '</p></div>' . "\n";
    }

/**
 * Retrieve the date in localized format
 *
 * @param string $format The format of the outputted date string
 * @param integer $timestamp Unix timestamp
 * @return string|boolean
 */
    public static function date($format, $timestamp = null)
    {
        $preset_timezone = date_default_timezone_get();
        date_default_timezone_set(get_option('timezone_string'));

        if (is_null($timestamp)) {
            $timestamp = time();
        }

        $date = date($format, $timestamp);
        date_default_timezone_set($preset_timezone);
        return $date;
    }

/**
 * Get timestamp by setting the time zone
 *
 * @param string $time A date/time string
 * @param string $now The timestamp that are used as the base
 * @return string|boolean
 */
    public static function strtotime($time, $now = null)
    {
        $preset_timezone = date_default_timezone_get();
        date_default_timezone_set(get_option('timezone_string'));

        if (is_null($now)) {
            $now = time();
        }

        $timestamp = strtotime($time, $now);
        date_default_timezone_set($preset_timezone);
        return $timestamp;
    }
}
