<?php
/**
 * Common class
 *
 * @copyright  Copyright (c) Yoshika
 * @author     Yoshika (@rnsk)
 * @package    FacebookPortal
 * @license    GPL2
 */
class FacebookPortalCommon
{

/**
 * File types to allow
 *
 * @var array
 */
    public $fileTypes = array(
        'jpg|jpeg|jpe' => 'image/jpeg',
        'gif' => 'image/gif',
        'png' => 'image/png'
    );

/**
 * Construct
 *
 * @return void
 */
    public function __construct()
    {
        // 共通クラス読み込み
        $classesPath = plugin_dir_path(FacebookPortal::PLUGIN_FILE) . 'classes' . DS;
        if (!class_exists('PluginHelper')) {
            require_once $classesPath . 'helpers.php';
        }
        $this->Helper = new PluginHelper;
    }

/**
 * Save the data as a post
 *
 * @param array $data Save data
 * @return array|boolean
 */
    public function savePost($data = array())
    {
        if (empty($data)) return false;

        // タイトル生成
        preg_match('/^.*?(。|\n|$)/', $data['message'], $match);
        $title = $match[0];
        $content = $data['message'];

        if ($data['link_text'] == true) {
            $content .= '<p class="facebook-post-url"><a href="' . $data['permalink'] . '">' . __('Facebook', FacebookPortal::TEXT_DOMAIN) . '</a></p>';
        }

        if ($data['auto_link'] == true) {
            $content = $this->Helper->autoLinkUrls($content, array('escape' => false));
        }

        $_data = array(
            'post_status' => 'publish',
            'post_author' => $data['post_author'],
            'post_content' => $content,
            'post_title' => $title,
            'post_category' => json_decode($data['post_category']),
            'post_date' => FacebookPortal::date('Y-m-d H:i:s', $data['timestamp']),
        );

        if ($post_id = wp_insert_post($_data)) {
            add_post_meta($post_id, 'facebook_post_id', $data['id']);
            add_post_meta($post_id, 'facebook_page_id', $data['facebook_page_id']);
            $_data['ID'] = $post_id;
            return $_data;
        }
        return false;
    }

/**
 * Save the attached image of the post
 *
 * @param string $photo_url Attachment image URL
 * @param integer $parent_post_id Post ID
 * @param integer $post_author Post author ID
 * @return integer|boolean
 */
    public function savePhoto($photo_url = null, $parent_post_id = null, $post_author = null)
    {
        if (empty($photo_url)) return false;

        $upload_dir = wp_upload_dir();

        $photo_path = parse_url($photo_url, PHP_URL_PATH);
        $file_type = wp_check_filetype($photo_path, $this->fileTypes);
        if ((!empty($file_type['ext'])) && (!empty($file_type['type']))) {
            $img_data = file_get_contents($photo_url);
            $file_name = substr((md5(microtime())), 0, 20) . '.' . $file_type['ext'];

            $upload_file = $upload_dir['path'] . DS . $file_name;
            if (file_put_contents($upload_file, $img_data)) {
                $attachment = array(
                    'guid' => $upload_dir['url'] . DS . $file_name,
                    'post_mime_type' => $file_type['type'],
                    'post_title' => preg_replace('/\.[^.]+$/', '', $file_name),
                    'post_content' => '',
                    'post_status' => 'inherit',
                    'post_author' => $post_author,
                );
                if ($attachment_id = wp_insert_attachment($attachment, $upload_file, $parent_post_id)) {
                    require_once ABSPATH . 'wp-admin/includes/image.php';
                    $attach_data = wp_generate_attachment_metadata($attachment_id, $upload_file);
                    wp_update_attachment_metadata($attachment_id, $attach_data);
                    return $attachment_id;
                }
            }
        }
        return false;
    }
}
