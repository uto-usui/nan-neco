<?php
/**
 * Admin Controller
 *
 * @copyright  Copyright (c) Yoshika
 * @author     Yoshika (@rnsk)
 * @package    FacebookPortal
 * @license    GPL2
 */
class FacebookPortalAdmin
{

/**
 * Instance of Model class
 *
 * @var object
 */
    protected $Model;

/**
 * Instance of View class
 *
 * @var object
 */
    protected $View;

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
        // 共通クラス読み込み
        $classesPath = plugin_dir_path(FacebookPortal::PLUGIN_FILE) . 'classes' . DS;
        if (!class_exists('PluginModel')) {
            require_once $classesPath . 'models.php';
        }
        $this->Model = new PluginModel(FacebookPortal::DB_TABLE);

        if (!class_exists('PluginView')) {
            require_once $classesPath . 'views.php';
        }
        $this->View = new PluginView;

        // メニュー追加
        add_action('admin_menu', array($this, 'admin_menu'));
        // Ajax リクエスト
        add_action('wp_ajax_wpfb_pre_add', array($this, 'pre_add'));

        $this->Common = FacebookPortal::uses('FacebookPortalCommon', 'class-common.php');
        $access_token = get_option(FacebookPortal::OPTION_FB_ACCESS_TOKEN);
        $this->Facebook = FacebookPortal::uses('FacebookPortalApi', 'facebook.php', 'libraries', $access_token);
    }

/**
 * Add menu for admin
 *
 * @return void
 */
    public function admin_menu()
    {
        // メインメニュー
        add_menu_page(
            __('Facebook Portal', FacebookPortal::TEXT_DOMAIN),
            __('FB Portal', FacebookPortal::TEXT_DOMAIN),
            'administrator',
            'wpfb_admin',
            array($this, 'action')
        );
        // サブメニュー
        add_submenu_page(
            'wpfb_admin',
            __('Setting', FacebookPortal::TEXT_DOMAIN),
            __('Setting', FacebookPortal::TEXT_DOMAIN),
            'administrator',
            'wpfb_admin_setting',
            array($this, 'setting')
        );
    }

/**
 * Set up actions
 *
 * @return void
 */
    public function action()
    {
        if (!empty($_GET['action'])) {
            switch($_GET['action']) {
                case 'add':
                    self::add();
                    break;
                case 'edit':
                    self::edit($_GET['id']);
                    break;
                case 'delete':
                    self::delete($_GET['id']);
                    break;
                case 'update':
                    self::update($_GET['id']);
                    break;
                default:
                    self::index();
            }
        } else {
            self::index();
        }
    }

/**
 * Plugin index action
 *
 * @return void
 */
    public function index()
    {
        $data = array();

        if (get_option(FacebookPortal::OPTION_FB_ACCESS_TOKEN) == '') {
            $format = '%s<a href="' . admin_url('admin.php?page=wpfb_admin_setting') . '">%s</a>';
            $message = sprintf($format, __('Please first setting.', FacebookPortal::TEXT_DOMAIN), __('Setting', FacebookPortal::TEXT_DOMAIN));
            $this->View->setAlert(array($message), 'error');
        } else {
            $fields = 'id,name,facebook_page_id,page_url,pic,post_updated';
            $results = $this->Model->getAll($fields);

            foreach ($results as $result) {
                $data[$result['id']] = $result;
            }
        }

        $this->View->render($data, __FUNCTION__);
    }

/**
 * Plugin setting action
 *
 * @return void
 */
    public function setting()
    {
        $data = array();

        if (($_SERVER['REQUEST_METHOD'] == 'POST') && (!empty($_POST))) {
            if (!check_admin_referer('wpfb_admin_setting')) die(__('Security check', FacebookPortal::TEXT_DOMAIN));

            // 保存処理
            $_data = $_POST;

            $err_flag = false;
            $err_mes = array();

            // 未入力チェック
            $required = array('facebook_app_id', 'facebook_app_secret');
            // 数字チェック
            $number = array('facebook_app_id');
            // 半角英数字チェック
            $alphanumeric = array('facebook_app_secret');

            foreach ($_data as $key => $value) {
                if ((in_array($key, $required)) && (empty($value))) {
                    $err_flag = true;
                    $err_mes[] = sprintf(__('%s is not entered.', FacebookPortal::TEXT_DOMAIN), __($key, FacebookPortal::TEXT_DOMAIN));
                }
                if (in_array($key, $number)) {
                    if (!preg_match('/^[0-9]+$/', $value)) {
                        $err_flag = true;
                        $err_mes[] = sprintf(__('Please enter only the numbers %s', FacebookPortal::TEXT_DOMAIN), __($key, FacebookPortal::TEXT_DOMAIN));
                    }
                }
                if (in_array($key, $alphanumeric)) {
                    if (!preg_match('/^[a-zA-Z0-9]+$/', $value)) {
                        $err_flag = true;
                        $err_mes[] = sprintf(__('Please enter alphanumeric characters to %s', FacebookPortal::TEXT_DOMAIN), __($key, FacebookPortal::TEXT_DOMAIN));
                    }
                }
            }

            if (($err_flag) || (!empty($err_mes))) {
                // エラーがあった場合はメッセージを表示
                $this->View->setAlert($err_mes, 'error');
                // フォームデータ代入
                $data = $_data;
            } else {
                try {
                    $config = array(
                        'appId' => $_data['facebook_app_id'],
                        'secret' => $_data['facebook_app_secret']
                    );
                    if (!$access_token = $this->Facebook->getAccessToken($config)) {
                        throw new Exception(__('An access token was not able to be acquired.', FacebookPortal::TEXT_DOMAIN));
                    } else {
                        update_option(FacebookPortal::OPTION_FB_ACCESS_TOKEN, $access_token);
                        update_option(FacebookPortal::OPTION_FB_APP_ID, $_data['facebook_app_id']);
                        update_option(FacebookPortal::OPTION_FB_APP_SECRET, $_data['facebook_app_secret']);
                        $this->View->setAlert(array(__('The access token was acquired.', FacebookPortal::TEXT_DOMAIN)), 'updated');
                    }
                } catch(Exception $e) {
                    $this->View->setAlert(array($e->getMessage()), 'error');
                }
            }
        }

        if (!$data) {
            $facebook_access_token = get_option(FacebookPortal::OPTION_FB_ACCESS_TOKEN);
            $data = array(
                'facebook_app_id' => get_option(FacebookPortal::OPTION_FB_APP_ID),
                'facebook_app_secret' => get_option(FacebookPortal::OPTION_FB_APP_SECRET),
                'facebook_access_token' => $facebook_access_token,
                'facebook_access_token_text' => ($facebook_access_token) ? __('Acquired', FacebookPortal::TEXT_DOMAIN) : __('Not acquired', FacebookPortal::TEXT_DOMAIN)
            );
        }

        $this->View->render($data, __FUNCTION__);
    }

/**
 * 新規登録
 * Facebook ページの設定を保存
 */
    public function add()
    {
        $data = array();

        if (($_SERVER['REQUEST_METHOD'] == 'POST') && (!empty($_POST))) {
            if (!check_admin_referer('wpfb_admin_add')) die(__('Security check', FacebookPortal::TEXT_DOMAIN));

            // 保存処理
            $_data = array(
                'name' => $_POST['name'],
                'facebook_page_id' => $_POST['facebook_page_id'],
                'username' => $_POST['username'],
                'page_url' => $_POST['page_url'],
                'pic' => $_POST['pic'],
                'post_category' => (array_key_exists('post_category', $_POST)) ? json_encode($_POST['post_category']) : null,
                'image_type' => $_POST['image_type'],
                'image_size' => $_POST['image_size'],
                'auto_link' => (!empty($_POST['auto_link'])) ? true : false,
                'link_text' => (!empty($_POST['link_text'])) ? true : false,
                'created' => FacebookPortal::date('Y-m-d H:i:s'),
                'updated' => FacebookPortal::date('Y-m-d H:i:s')
            );

            $err_flag = false;
            $err_mes = array();

            // 未入力チェック
            $required = array('facebook_page_id');
            // 数字チェック
            $number = array('facebook_page_id');
            // 重複チェック
            $exist = array('facebook_page_id');

            foreach ($_data as $key => $value) {
                if ((in_array($key, $required)) && (empty($value))) {
                    $err_flag = true;
                    $err_mes[] = sprintf(__('%s is not entered.', FacebookPortal::TEXT_DOMAIN), __($key, FacebookPortal::TEXT_DOMAIN));
                }
                if (in_array($key, $number)) {
                    if (!preg_match('/^[0-9]+$/', $value)) {
                        $err_flag = true;
                        $err_mes[] = sprintf(__('Please enter only the numbers %s', FacebookPortal::TEXT_DOMAIN), __($key, FacebookPortal::TEXT_DOMAIN));
                    }
                }
                if (in_array($key, $exist)) {
                    if ($this->Model->getField('facebook_page_id', array($key . ' =' => $value))) {
                        $err_flag = true;
                        $err_mes[] = __($key, FacebookPortal::TEXT_DOMAIN) . __('%s is already registered.', FacebookPortal::TEXT_DOMAIN);
                    }
                }
            }

            if (($err_flag) || (!empty($err_mes))) {
                // エラーがあった場合はメッセージを表示
                $this->View->setAlert($err_mes, 'error');
                // フォームデータ代入
                $data = $_data;
            } else {
                try {
                    // User登録
                    if (!$user = get_user_by('login', $_data['facebook_page_id'])) {
                        $_user = array(
                            'user_login' => $_data['facebook_page_id'],
                            'user_pass' => wp_generate_password(),
                            'user_url' => $_data['page_url'],
                            'display_name' => $_data['name'],
                            'nickname' => $_data['name'],
                            'role' => 'contributor'
                        );
                        if (!$user_id = wp_insert_user($_user)) {
                            throw new Exception(__('Failed to add the user.', FacebookPortal::TEXT_DOMAIN));
                        }
                    } else {
                        $user_id = $user->ID;
                    }

                    // ページ情報登録
                    $_data['post_author'] = $user_id;
                    global $wpdb;
                    $table_name = $wpdb->prefix . FacebookPortal::DB_TABLE;
                    if (!$result = $wpdb->insert($table_name, $_data)) {
                        throw new Exception(__('Failed to add the page information.', FacebookPortal::TEXT_DOMAIN));
                    }
                    $id = $wpdb->insert_id;

                    $this->View->setAlert(array(__('Saved successfully.', FacebookPortal::TEXT_DOMAIN)), 'updated');
                } catch(Exception $e) {
                    $this->View->setAlert(array($e->getMessage()), 'error');
                }
                $this->View->redirect(admin_url('admin.php?page=wpfb_admin'));
            }
        } else {
            $data['facebook_page_id'] = null;
        }

        if (get_option(FacebookPortal::OPTION_FB_ACCESS_TOKEN) == '') {
            $format = '%s<a href="' . admin_url('admin.php?page=wpfb_admin_setting') . '">%s</a>';
            $message = sprintf($format, __('Please first setting.', FacebookPortal::TEXT_DOMAIN), __('Setting', FacebookPortal::TEXT_DOMAIN));
            $this->View->setAlert(array($message), 'error');
        }

        $this->View->render($data, __FUNCTION__);
    }

/**
 * 情報取得
 * Facebook ページの情報を取得
 */
    public function pre_add()
    {
        $page_id = trim($_POST['facebook_page_id']);
        $result = $this->Facebook->getFacebookpage($page_id);
        $result = json_encode($result);
        die($result);
    }

/**
 * 編集
 * Facebook ページの設定を編集
 */
    public function edit($id = null)
    {
        if ((is_null($id)) || (!is_numeric($id))) {
            die(__('Security check', FacebookPortal::TEXT_DOMAIN));
        }

        global $wpdb;
        $table_name = $wpdb->prefix . FacebookPortal::DB_TABLE;
        $data = array();

        if (($_SERVER['REQUEST_METHOD'] == 'POST') && (!empty($_POST))) {
            if (!check_admin_referer('wpfb_admin_edit')) die(__('Security check', FacebookPortal::TEXT_DOMAIN));

            // 保存処理
            $_data = array(
                'post_author' => $_POST['post_author'],
                'post_category' => (array_key_exists('post_category', $_POST)) ? json_encode($_POST['post_category']) : null,
                'image_type' => $_POST['image_type'],
                'image_size' => $_POST['image_size'],
                'auto_link' => (!empty($_POST['auto_link'])) ? true : false,
                'link_text' => (!empty($_POST['link_text'])) ? true : false,
                'updated' => FacebookPortal::date('Y-m-d H:i:s')
            );

            $err_flag = false;
            $err_mes = array();

            // 未入力チェック
            $required = array('post_author');
            // 数字チェック
            $number = array('post_author');

            foreach ($_data as $key => $value) {
                if ((in_array($key, $required)) && (empty($value))) {
                    $err_flag = true;
                    $err_mes[] = sprintf(__('%s is not entered.', FacebookPortal::TEXT_DOMAIN), __($key, FacebookPortal::TEXT_DOMAIN));
                }
                if (in_array($key, $number)) {
                    if (!preg_match('/^[0-9]+$/', $value)) {
                        $err_flag = true;
                        $err_mes[] = sprintf(__('Please enter only the numbers %s', FacebookPortal::TEXT_DOMAIN), __($key, FacebookPortal::TEXT_DOMAIN));
                    }
                }
            }

            if (($err_flag) || (!empty($err_mes))) {
                // エラーがあった場合はメッセージを表示
                $this->View->setAlert($err_mes, 'error');
                // フォームデータ代入
                $data = $_data;
            } else {
                try {
                    // ページ情報保存
                    $id = $_POST['id'];
                    if (!$result = $wpdb->update($table_name, $_data, array('id' => $id))) {
                        throw new Exception(__('Failed to save the information of the facebook page.', FacebookPortal::TEXT_DOMAIN));
                    }

                    $this->View->setAlert(array(__('Saved successfully.', FacebookPortal::TEXT_DOMAIN)), 'updated');
                } catch(Exception $e) {
                    $this->View->setAlert(array($e->getMessage()), 'error');
                }
            }
            $this->View->redirect(admin_url('admin.php?page=wpfb_admin'));
        }

        if (!$data) {
            $data = $this->Model->getFirst($id);
            $data['post_category'] = json_decode($data['post_category']);
        }

        if (get_option(FacebookPortal::OPTION_FB_ACCESS_TOKEN) == '') {
            $format = '%s<a href="' . admin_url('admin.php?page=wpfb_admin_setting') . '">%s</a>';
            $message = sprintf($format, __('Please first setting.', FacebookPortal::TEXT_DOMAIN), __('Setting', FacebookPortal::TEXT_DOMAIN));
            $this->View->setAlert(array($message), 'error');
        }

        $this->View->render($data, __FUNCTION__);
    }

/**
 * 削除
 * Facebook ページの設定を削除
 */
    public function delete($id = null)
    {
        if ((is_null($id)) || (!is_numeric($id))) {
            die(__('Security check', FacebookPortal::TEXT_DOMAIN));
        }

        if (($_SERVER['REQUEST_METHOD'] == 'POST') && (!empty($_POST))) {
            if (!check_admin_referer('wpfb_admin_delete')) die(__('Security check', FacebookPortal::TEXT_DOMAIN));

            $id = $_POST['id'];
            $post_author = $_POST['post_author'];
            $delete_option = $_POST['delete_option'];

            try {
                if (!$this->Model->delete($id)) {
                    throw new Exception(__('Your facebook page have not been deleted. Please investigate.', FacebookPortal::TEXT_DOMAIN));
                }

                switch($delete_option) {
                    case 'delete':
                        if (get_userdata($post_author)) {
                            if (!wp_delete_user($post_author)) {
                                throw new Exception(__('Failed to delete user.', FacebookPortal::TEXT_DOMAIN));
                            }
                        }
                        $this->View->setAlert(array(__('Data deleted.', FacebookPortal::TEXT_DOMAIN)), 'updated');
                    break;
                    case 'reassign':
                        $reassign_user = $_POST['reassign_user'];
                        if (!wp_delete_user($post_author, $reassign_user)) {
                            throw new Exception(__('An error occurred during assignment of articles or to delete a user.', FacebookPortal::TEXT_DOMAIN));
                        }
                        $this->View->setAlert(array(__('Assigned.', FacebookPortal::TEXT_DOMAIN)), 'updated');
                    break;
                    default:
                        $this->View->setAlert(array(__('Data deleted.', FacebookPortal::TEXT_DOMAIN)), 'updated');
                }
            } catch(Exception $e) {
                $this->View->setAlert(array($e->getMessage()), 'error');
            }
            $this->View->redirect(admin_url('admin.php?page=wpfb_admin'));
        }

        $fields = 'id,name,post_author';
        $data = $this->Model->getFirst($id, $fields);
        $this->View->render($data, __FUNCTION__);
    }

/**
 * 更新
 * Facebook ページの記事を取得
 */
    public function update($id = null)
    {
        if ((is_null($id)) || (!is_numeric($id))) {
            die(__('Security check', FacebookPortal::TEXT_DOMAIN));
        }
        global $wpdb;
        $facebook_post_ids = $wpdb->get_col("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = 'facebook_post_id'");

        if (($_SERVER['REQUEST_METHOD'] == 'POST') && (!empty($_POST))) {
            if (!check_admin_referer('wpfb_admin_update')) die(__('Security check', FacebookPortal::TEXT_DOMAIN));

            // 保存処理
            $ids = $_POST['ids'];
            if (is_array($ids)) {
                $_post = $_SESSION['feeds'];
                unset($_SESSION['feeds']);
                $post_ids = array();
                foreach ($ids as $fb_post_id) {
                    if (in_array($fb_post_id, $facebook_post_ids)) {
                        continue;
                    }

                    // 投稿保存
                    if ($_data = $this->Common->savePost($_post[$fb_post_id])) {
                        $post_ids[] = $_data['ID'];
                        if (($_post[$fb_post_id]['type'] === 'photo') && (isset($_post[$fb_post_id]['object_id']))) {
                            // 添付画像の取り扱い設定がなければ処理をスキップ
                            if (empty($_post[$fb_post_id]['image_type'])) {
                                continue;
                            }

                            // 添付画像取得保存
                            if ($photoUrls = $this->Facebook->getPhotoUrl($_post[$fb_post_id]['object_id'])) {
                                $attachment_ids = array();
                                foreach ($photoUrls as $photoUrl) {
                                    if ($attachment_id = $this->Common->savePhoto($photoUrl, $_data['ID'], $_data['post_author'])) {
                                        $attachment_ids[] = $attachment_id;
                                    }
                                }
                                if (!empty($attachment_ids)) {
                                    // アイキャッチ画像
                                    if ($_post[$fb_post_id]['image_type'] == 'attachment') {
                                        $attachment_id = reset($attachment_ids);
                                        set_post_thumbnail($_data['ID'], $attachment_id);
                                    }
                                    // 記事に挿入
                                    if ($_post[$fb_post_id]['image_type'] == 'insert') {
                                        $attachment_id = reset($attachment_ids);
                                        $image_tag = wp_get_attachment_image($attachment_id, $_post[$fb_post_id]['image_size']);
                                        $content = $_data['post_content'] . '<p>' . $image_tag . '</p>';
                                        $params = $_data;
                                        $params['post_content'] = $content;
                                        wp_insert_post($params);
                                    }
                                    // ギャラリーショートコード
                                    if ($_post[$fb_post_id]['image_type'] == 'gallery') {
                                        //$ids = implode(',', $attachment_ids);
                                        //$option = (!empty($_post[$fb_post_id]['gallery_options'])) ? ' ' . stripslashes($_post[$fb_post_id]['gallery_options']) : '';
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
                $num = count($post_ids);
                if ($num > 0) {
                    $this->View->setAlert(array(sprintf(__('Saved the data of %d', FacebookPortal::TEXT_DOMAIN), $num)), 'updated');
                    $this->View->redirect(admin_url('admin.php?page=wpfb_admin'));
                }
            }
            $this->View->setAlert(__('Failed to save the data.', FacebookPortal::TEXT_DOMAIN));
            $this->View->redirect(admin_url('admin.php?page=wpfb_admin&action=update&id=' . $id));

        } else {
            // ページ情報を取得
            $fields = 'facebook_page_id,post_author,post_category,image_type,image_size,link_text,auto_link';
            $result = $this->Model->getFirst($id, $fields);

            // 記事取得
            $response = $this->Facebook->getFacebookFeed($result['facebook_page_id']);
            if (!$response) {
                $message = __('Failed to get the data.', FacebookPortal::TEXT_DOMAIN);
                $this->View->setAlert(array($message), 'error');
            } else {
                foreach ($response as $feed) {
                    // 記事存在チェック
                    $feed['post_exist'] = false;
                    if (in_array($feed['id'], $facebook_post_ids)) {
                        $feed['post_exist'] = true;
                    }

                    // 投稿日をタイムスタンプに変換
                    $feed['timestamp'] = FacebookPortal::strtotime($feed['created_time']);

                    // デフォルト設定を代入
                    $feed['post_author'] = $result['post_author'];
                    $feed['post_category'] = $result['post_category'];
                    $feed['image_type'] = $result['image_type'];
                    $feed['image_size'] = $result['image_size'];
                    $feed['link_text'] = $result['link_text'];
                    $feed['auto_link'] = $result['auto_link'];
                    $feed['facebook_page_id'] = $result['facebook_page_id'];

                    $data['feeds'][$feed['id']] = $feed;
                }
                $_SESSION['feeds'] = $data['feeds'];
            }
            $data['id'] = $id;
            $this->View->render($data, __FUNCTION__);
        }
    }
}
