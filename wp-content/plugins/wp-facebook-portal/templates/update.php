<div class="wrap">
    <h2>Facebook Portal - <?php _e('Update page', FacebookPortal::TEXT_DOMAIN); ?></h2>

    <div id="ajax-response"><?php $this->theAlert(); ?></div>

<?php if (array_key_exists('feeds', $data)) : ?>
    <form method="post" action="<?php echo admin_url('admin.php?page=wpfb_admin&amp;action=update&amp;id=' . $id); ?>">
        <?php  wp_nonce_field('wpfb_admin_update'); ?>

<?php submit_button(__('Save the data checked.', FacebookPortal::TEXT_DOMAIN), 'primary'); ?>

        <table class="wp-list-table widefat fixed">
            <thead>
                <tr>
                    <th class="check-column"><input type="checkbox"></th>
                    <th><?php _e('Content', FacebookPortal::TEXT_DOMAIN); ?></th>
                    <th><?php _e('Date', FacebookPortal::TEXT_DOMAIN); ?></th>
                    <th><?php _e('Post type', FacebookPortal::TEXT_DOMAIN); ?></th>
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <th class="check-column"><input type="checkbox"></th>
                    <th><?php _e('Content', FacebookPortal::TEXT_DOMAIN); ?></th>
                    <th><?php _e('Date', FacebookPortal::TEXT_DOMAIN); ?></th>
                    <th><?php _e('Post type', FacebookPortal::TEXT_DOMAIN); ?></th>
                </tr>
            </tfoot>

            <tbody id="the-list">
<?php foreach ($data['feeds'] as $date) : ?>
                <tr>
                    <th class="check-column">
                        <?php if (!$date['post_exist']) : ?>
                        <input type="checkbox" name="ids[]" value="<?php echo $date['id']; ?>">
                    <?php endif; ?>
                    </th>
                    <td><?php echo $date['message']; ?></td>
                    <td class="date column-date"><?php echo FacebookPortal::date('Y-m-d H:i:s', $date['timestamp']); ?></td>
                    <td class="type column-type"><?php echo $date['type']; ?></td>
                </tr>
<?php endforeach; ?>
            </tbody>
        </table>

<?php submit_button(__('Save the data checked.', FacebookPortal::TEXT_DOMAIN), 'primary'); ?>

    </form>
<?php endif; ?>

</div>
