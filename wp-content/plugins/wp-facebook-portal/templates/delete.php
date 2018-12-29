<script>
jQuery(document).ready(function($) {
    var submit = $('#submit').prop('disabled', true);
    $('input[name=delete_option]').one('change', function() {
        submit.prop('disabled', false);
    });
});
</script>

<div class="wrap">
    <h2>Facebook Portal - <?php _e('Delete page', FacebookPortal::TEXT_DOMAIN); ?></h2>

    <div id="ajax-response"><?php $this->theAlert(); ?></div>

    <div class="form-wrap">
        <form method="post" action="<?php echo admin_url('admin.php?page=wpfb_admin&amp;action=delete&amp;id=' . $id); ?>">
            <?php  wp_nonce_field('wpfb_admin_delete'); ?>

            <p><?php _e('You are about to delete following page.', FacebookPortal::TEXT_DOMAIN); ?></p>
            <ul>
                <li><?php echo $name; ?></li>
            </ul>

            <fieldset>
                <p><legend><?php _e('What should be done with content owned by this page?', FacebookPortal::TEXT_DOMAIN); ?></legend></p>
                <ul style="list-style:none;">
                    <li><label><input type="radio" id="delete_option0" name="delete_option" value="delete" />
                        <?php _e('Delete all posts.', FacebookPortal::TEXT_DOMAIN); ?></label></li>
                    <li><label for="delete_option1"><input type="radio" id="delete_option1" name="delete_option" value="reassign" />
                        <?php _e('Assign to the user of the next all posts', FacebookPortal::TEXT_DOMAIN); ?>:</label>
<?php wp_dropdown_users(array('name' => 'reassign_user')); ?></li>
                    <li><label><input type="radio" id="delete_option2" name="delete_option" value="skip" />
                        <?php _e('Leave as it is.', FacebookPortal::TEXT_DOMAIN); ?></label></li>
                </ul>
            </fieldset>
            <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
            <input type="hidden" id="post_author" name="post_author" value="<?php echo $post_author; ?>">

<?php submit_button(__('Confirm Deletion'), 'secondary'); ?>

        </form>
    </div>
</div>
