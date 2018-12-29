<?php
/**
 * Cron class
 *
 * @copyright  Copyright (c) Yoshika
 * @author     Yoshika (@rnsk)
 * @package    FacebookPortal
 * @license    GPL2
 */
class FacebookPortalCron
{

/**
 * Instance of Model class
 *
 * @var object
 */
    protected $Model;

/**
 * Instance of Plugin common class
 *
 * @var object
 */
    protected $Common;

/**
 * Instance of Facebook API class
 *
 * @var object
 */
    protected $Facebook;

/**
 * Construct
 *
 * @return void
 */
    public function __construct()
    {
        // Cron action
        add_action('wpfb_auto_update', array($this, 'main'));

        // 共通クラス読み込み
        $classesPath = plugin_dir_path(FacebookPortal::PLUGIN_FILE) . 'classes' . DS;
        if (!class_exists('PluginModel')) {
            require_once $classesPath . 'models.php';
        }
        $this->Model = new PluginModel(FacebookPortal::DB_TABLE);

        $this->Common = FacebookPortal::uses('FacebookPortalCommon', 'class-common.php');
        $access_token = get_option(FacebookPortal::OPTION_FB_ACCESS_TOKEN);
        $this->Facebook = FacebookPortal::uses('FacebookPortalApi', 'facebook.php', 'libraries', $access_token);
    }

/**
 * 記事更新
 * 
 * @return boolean
 */
    public function main()
    {
        // ページ情報を取得
        $fields = 'id,facebook_page_id,post_author,post_category,image_type,image_size,link_text,auto_link,post_updated';

        if ($results = $this->Model->getAll($fields)) {
            global $wpdb;
            $facebook_post_ids = $wpdb->get_col("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = 'facebook_post_id'");
            // Facebook ページの数だけ繰り返し
            foreach ($results as $result) {
                // 記事取得
                $since = ($result['post_updated']) ? $result['post_updated'] : null;
                if (!$response = $this->Facebook->getFacebookFeed($result['facebook_page_id'], $since)) {
                    continue;
                } else {
                    $created_time = array();
                    foreach ($response as $feed) {
                        if (in_array($feed['id'], $facebook_post_ids)) {
                            continue;
                        }

                        // 投稿日をタイムスタンプに変換
                        $feed['timestamp'] = FacebookPortal::strtotime($feed['created_time']);

                        // デフォルト設定を代入
                        $feed['post_author'] = $result['post_author'];
                        $feed['post_category'] = $result['post_category'];
                        $feed['link_text'] = $result['link_text'];
                        $feed['auto_link'] = $result['auto_link'];
                        $feed['facebook_page_id'] = $result['facebook_page_id'];

                        // 投稿保存
                        if ($_data = $this->Common->savePost($feed)) {
                            // 更新確認用タイムスタンプ
                            $created_time[] = $feed['timestamp'];
                            if (($feed['type'] === 'photo') && (isset($feed['object_id']))) {
                                // 添付画像の取り扱い設定がなければ処理をスキップ
                                if (empty($result['image_type'])) {
                                    continue;
                                }

                                // 添付画像取得保存
                                if ($photoUrls = $this->Facebook->getPhotoUrl($feed['object_id'])) {
                                    $attachment_ids = array();
                                    foreach ($photoUrls as $photoUrl) {
                                        if ($attachment_id = $this->Common->savePhoto($photoUrl, $_data['ID'], $_data['post_author'])) {
                                            $attachment_ids[] = $attachment_id;
                                        }
                                    }
                                    if (!empty($attachment_ids)) {
                                        // アイキャッチ画像
                                        if ($result['image_type'] == 'attachment') {
                                            $attachment_id = reset($attachment_ids);
                                            set_post_thumbnail($_data['ID'], $attachment_id);
                                        }
                                        // 記事に挿入
                                        if ($result['image_type'] == 'insert') {
                                            $attachment_id = reset($attachment_ids);
                                            $image_tag = wp_get_attachment_image($attachment_id, $result['image_size']);
                                            $content = $_data['post_content'] . '<p>' . $image_tag . '</p>';
                                            $params = $_data;
                                            $params['post_content'] = $content;
                                            wp_insert_post($params);
                                        }
                                        // ギャラリーショートコード
                                        if ($result['image_type'] == 'gallery') {
                                            //$ids = implode(',', $attachment_ids);
                                            //$option = (!empty($result['gallery_options'])) ? ' ' . stripslashes($result['gallery_options']) : '';
                                            //$content = $_data['post_content'] . '<p>[gallery ids="' . $ids . '"' . $option . ']</p>';
                                            //$params = $_data;
                                            //$params['post_content'] = $content;
                                            //wp_insert_post($params);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if (count($created_time) > 0) {
                        arsort($created_time);
                        $post_updated = array_shift($created_time);
                        $fields = array('post_updated' => $post_updated);
                        $conditions = array('id' => $result['id']);
                        $wpdb->update($wpdb->prefix . FacebookPortal::DB_TABLE, $fields, $conditions);
                    }
                }
            }
        }
    }
}
