<div class="wrap">
    <h2>Facebook Portal <?php echo __('Setting', FacebookPortal::TEXT_DOMAIN); ?></h2>

    <div id="ajax-response"><?php $this->theAlert(); ?></div>

    <div class="form-wrap">
        <form method="post" action="<?php echo admin_url('admin.php?page=wpfb_admin_setting'); ?>">
            <?php  wp_nonce_field('wpfb_admin_setting'); ?>

            <table class="form-table">
                <tbody>
                    <tr>
                        <th>Facebook App ID</th>
                        <td>
                            <input type="text" id="facebook_app_id" name="facebook_app_id" value="<?php echo $facebook_app_id; ?>" class="regular-text code">
                            <p><?php _e('Please enter the ID of the Facebook application.', FacebookPortal::TEXT_DOMAIN); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th>Facebook App Secret</th>
                        <td>
                            <input type="text" id="facebook_app_secret" name="facebook_app_secret" value="<?php echo $facebook_app_secret; ?>" class="regular-text code">
                            <p><?php _e('Please enter the secret key of the Facebook application.', FacebookPortal::TEXT_DOMAIN); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th>Facebook Access Token</th>
                        <td>
                            <strong><?php echo $facebook_access_token_text; ?></strong>
                            <input type="hidden" id="facebook_access_token" name="facebook_access_token" value="<?php echo $facebook_access_token; ?>">
                        </td>
                    </tr>
                </tbody>
            </table>

<?php submit_button(__('Get the access token', FacebookPortal::TEXT_DOMAIN), 'primary'); ?>

        </form>
    </div>

    <hr />

    <div id="app_setting_sample">
        <p><?php _e('Please check the basic settings screen of the Facebook app to do the settings.', FacebookPortal::TEXT_DOMAIN); ?></p>
        <?php echo $this->Helper->image('setting_app.png'); ?>
        <ul>
            <li>【App ID】を『Facebook App ID』に入力してください。</li>
            <li>【App Secret】を『Facebook App Secret』に入力してください。</li>
            <li>【App Domains】にはこのプラグインを使用するWebサイトのドメインを指定してください。</li>
        </ul>
    </div>

</div>