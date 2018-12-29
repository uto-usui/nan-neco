<script>
jQuery(document).ready(function($) {
    $('#pre_add').submit(function(event) {
        event.preventDefault();

        var form = $(this);
        var button = form.find('button');
        var message;

        // 送信処理
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            cache: false,
            timeout: 10000,

            beforeSend: function(xhr, settings) {
                button.attr('disabled', true);
            },
            complete: function(xhr, textStatus) {
                button.attr('disabled', false);
            },

            success: function(response, textStatus, xhr) {
                if (response == false) {
                    message = '<div class="error"><p><strong><?php _e('A page information was not able to be acquired.', FacebookPortal::TEXT_DOMAIN); ?></strong></p></div>';
                } else {
                    message = '<div class="updated"><p><strong><?php _e('The page information was acquired.', FacebookPortal::TEXT_DOMAIN); ?></strong></p></div>';
                    for (var key in response) {
                        $('#' + key).val(response[key]);
                    }
                    var page_url_text = '<a href="' + response.link + '" target="_blank">' + response.link + '</a>';
                    $('#page_url_text').html(page_url_text);
                    $('#add-form').show();
                }
                $('#ajax-response').html(message);
            },
            
            error: function(xhr, textStatus, error) {
                message = '<div class="error"><p><strong><?php _e('Failed to sending for data.', FacebookPortal::TEXT_DOMAIN); ?></strong></p></div>';
                $('#ajax-response').html(message);
            }
        });
    });
});
</script>

<div class="wrap">
    <h2>Facebook Portal - <?php _e('Add page', FacebookPortal::TEXT_DOMAIN); ?></h2>

    <div id="ajax-response"><?php $this->theAlert(); ?></div>

    <form id="pre_add" method="post" action="<?php echo admin_url('admin-ajax.php'); ?>">
        <table class="form-table">
            <tbody>
                <tr>
                    <th>Facebook <?php _e('Page ID', FacebookPortal::TEXT_DOMAIN); ?></th>
                    <td>
                        <input type="text" id="facebook_page_id" name="facebook_page_id" value="<?php echo $facebook_page_id; ?>" class="regular-text code">
                        <p><?php _e('Please enter the Facebook page ID.', FacebookPortal::TEXT_DOMAIN); ?></p>
                    </td>
                </tr>
            </tbody>
        </table>
        <input type="hidden" name="action" value="wpfb_pre_add"/>

<?php submit_button(__('Confirm', FacebookPortal::TEXT_DOMAIN), 'primary'); ?>

    </form>

    <div id="add-form" style="display:none;">
        <h3><?php _e('Page information', FacebookPortal::TEXT_DOMAIN); ?></h3>
        <p><?php _e('Please press the save button if there is no problem to contents.', FacebookPortal::TEXT_DOMAIN); ?></p>

        <form method="post" action="<?php echo admin_url('admin.php?page=wpfb_admin&amp;action=add'); ?>">
            <?php  wp_nonce_field('wpfb_admin_add'); ?>

            <table class="form-table">
                <tbody>
                    <tr>
                        <th>Facebook <?php _e('Page name', FacebookPortal::TEXT_DOMAIN); ?></th>
                        <td>
                            <input type="text" id="name" name="name" class="regular-text code" readonly>
                        </td>
                    </tr>
                    <tr>
                        <th>Facebook <?php _e('Username', FacebookPortal::TEXT_DOMAIN); ?></th>
                        <td>
                            <input type="text" id="username" name="username" class="regular-text code" readonly>
                            <p><?php _e('It is displayed only when have set.', FacebookPortal::TEXT_DOMAIN); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th>Facebook <?php _e('Page URL', FacebookPortal::TEXT_DOMAIN); ?></th>
                        <td>
                            <span id="page_url_text"></span>
                            <input type="hidden" id="link" name="page_url">
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Handling of the attached image', FacebookPortal::TEXT_DOMAIN); ?></th>
                        <td>
                            <p><label><input name="image_type" type="radio" value="attachment" checked> <?php _e('Featured Images'); ?></label></p>
                            <p><label><input name="image_type" type="radio" value="insert"> <?php _e('Insert into Post'); ?></label></p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Size of the attached image', FacebookPortal::TEXT_DOMAIN); ?></th>
                        <td>
                            <p><label><input name="image_size" type="radio" value="thumbnail" checked> <?php _e('Thumbnail', FacebookPortal::TEXT_DOMAIN); ?></label></p>
                            <p><label><input name="image_size" type="radio" value="medium"> <?php _e('Medium size', FacebookPortal::TEXT_DOMAIN); ?></label></p>
                            <p><label><input name="image_size" type="radio" value="large"> <?php _e('Large size', FacebookPortal::TEXT_DOMAIN); ?></label></p>
                            <p><label><input name="image_size" type="radio" value="full"> <?php _e('Full size', FacebookPortal::TEXT_DOMAIN); ?></label></p>
                            <p class="description"><?php _e('Please select an image size to inserted into the article.', FacebookPortal::TEXT_DOMAIN); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Auto Link', FacebookPortal::TEXT_DOMAIN); ?></th>
                        <td>
                            <label for="auto_link">
                                <input type="checkbox" id="auto_link" name="auto_link">
                                <?php esc_html_e('Adds links (<a href=....)  by finding URL in the post.', FacebookPortal::TEXT_DOMAIN); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Link to the Facebook post', FacebookPortal::TEXT_DOMAIN); ?></th>
                        <td>
                            <label for="link_text">
                                <input type="checkbox" id="link_text" name="link_text">
                                <?php _e('Add a link to the article on Facebook post', FacebookPortal::TEXT_DOMAIN); ?>
                            </label>
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
        echo '<li id=' . $cid . '><label for="in-' . $cid . '">' . '<input type="checkbox" value="' . $cat->cat_ID . '" id="in-' . $cid . '" name="post_category[]"> ' . $cat->cat_name . '</label></li>';
    }
?>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <input type="hidden" id="id" name="facebook_page_id">
            <input type="hidden" id="picture" name="pic">

<?php submit_button(__('Save'), 'primary'); ?>

        </form>
    </div>
</div>
