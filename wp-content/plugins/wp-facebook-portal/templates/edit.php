<div class="wrap">
    <h2>Facebook Portal - <?php _e('Edit page', FacebookPortal::TEXT_DOMAIN); ?></h2>

    <div id="ajax-response"><?php $this->theAlert(); ?></div>

    <div id="edit-form">
        <h3><?php _e('Page information', FacebookPortal::TEXT_DOMAIN); ?></h3>

        <form method="post" action="<?php echo admin_url('admin.php?page=wpfb_admin&amp;action=edit&amp;id=' . $id); ?>">
            <?php  wp_nonce_field('wpfb_admin_edit'); ?>

            <table class="form-table">
                <tbody>
                    <tr>
                        <th>Facebook <?php _e('Page name', FacebookPortal::TEXT_DOMAIN); ?></th>
                        <td><?php echo $name; ?></td>
                    </tr>
                    <tr>
                        <th>Facebook <?php _e('Username', FacebookPortal::TEXT_DOMAIN); ?></th>
                        <td><?php echo $username; ?></td>
                    </tr>
                    <tr>
                        <th><?php _e('Page URL', FacebookPortal::TEXT_DOMAIN); ?></th>
                        <td><a href="<?php echo $page_url; ?>" target="_blank"><?php echo $page_url; ?></a></td>
                    </tr>
                    <tr>
                        <th><?php _e('Handling of the attached image', FacebookPortal::TEXT_DOMAIN); ?></th>
                        <td>
                            <p><label><input name="image_type" type="radio" value="attachment"<?php if ($image_type == 'attachment') echo ' checked'; ?>> <?php _e('Featured Images'); ?></label></p>
                            <p><label><input name="image_type" type="radio" value="insert"<?php if ($image_type == 'insert') echo ' checked'; ?>> <?php _e('Insert into Post'); ?></label></p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Size of the attached image', FacebookPortal::TEXT_DOMAIN); ?></th>
                        <td>
                            <p><label><input name="image_size" type="radio" value="thumbnail"<?php if ($image_size == 'thumbnail') echo ' checked'; ?>> <?php _e('Thumbnail', FacebookPortal::TEXT_DOMAIN); ?></label></p>
                            <p><label><input name="image_size" type="radio" value="medium"<?php if ($image_size == 'medium') echo ' checked'; ?>> <?php _e('Medium size', FacebookPortal::TEXT_DOMAIN); ?></label></p>
                            <p><label><input name="image_size" type="radio" value="large"<?php if ($image_size == 'large') echo ' checked'; ?>> <?php _e('Large size', FacebookPortal::TEXT_DOMAIN); ?></label></p>
                            <p><label><input name="image_size" type="radio" value="full"<?php if ($image_size == 'full') echo ' checked'; ?>> <?php _e('Full size', FacebookPortal::TEXT_DOMAIN); ?></label></p>
                            <p class="description"><?php _e('Please select an image size to inserted into the article.', FacebookPortal::TEXT_DOMAIN); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Auto Link', FacebookPortal::TEXT_DOMAIN); ?></th>
                        <td>
                            <label for="auto_link">
                                <?php $auto_link_check = (!empty($auto_link)) ? ' checked' : ''; ?>
                                <input type="checkbox" id="auto_link" name="auto_link"<?php echo $auto_link_check; ?>>
                                <?php esc_html_e('Adds links (<a href=....)  by finding URL in the post.', FacebookPortal::TEXT_DOMAIN); ?>
                            </label>
                            <p class="description"><?php _e('Is required blanks or line breaks after the URL.', FacebookPortal::TEXT_DOMAIN); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Link to the Facebook post', FacebookPortal::TEXT_DOMAIN); ?></th>
                        <td>
                            <label for="link_text">
                                <?php $link_text_check = (!empty($link_text)) ? ' checked' : ''; ?>
                                <input type="checkbox" id="link_text" name="link_text"<?php echo $link_text_check; ?>>
                                <?php _e('Add a link to the article on Facebook post', FacebookPortal::TEXT_DOMAIN); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Author'); ?></th>
                        <td>
                            <select name="post_author">
<?php
    $users = get_users(array('orderby' => 'ID', 'fields' => array('ID', 'display_name')));
    foreach ($users as $user) {
        $selected = '';
        if ($user->ID == $post_author) {
            $selected = ' selected';
        }
        echo '<option value=' . $user->ID . $selected . '>' . $user->display_name . '</option>';
    }
?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Categories', FacebookPortal::TEXT_DOMAIN); ?></th>
                        <td>
                            <div id="taxonomy-category" class="categorydiv">
                                <div id="category-all" class="tabs-panel">
                                    <ul id="categorychecklist" data-wp-lists="list:category" class="categorychecklist form-no-clear">
<?php
    $cats = get_categories(array('hide_empty' => false));
    foreach($cats as $cat) {
        $cid = 'category-' . $cat->cat_ID;
        $checked = '';
        if ((is_array($data['post_category'])) && (in_array($cat->cat_ID, $data['post_category']))) {
            $checked = ' checked';
        }
        echo '<li id=' . $cid . '><label for="in-' . $cid . '">' . '<input type="checkbox" value="' . $cat->cat_ID . '" id="in-' . $cid . '" name="post_category[]"' . $checked . '> ' . $cat->cat_name . '</label></li>';
    }
?>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">

<?php submit_button(__('Save'), 'primary'); ?>

        </form>
    </div>
</div>
