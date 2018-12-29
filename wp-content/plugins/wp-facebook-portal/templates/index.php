<div class="wrap">
    <h2>Facebook Portal <a href="<?php echo admin_url('admin.php?page=wpfb_admin&amp;action=add'); ?>" class="add-new-h2"><?php _e('Add New', FacebookPortal::TEXT_DOMAIN); ?></a></h2><br />

    <div id="ajax-response"><?php $this->theAlert(); ?></div>

<?php if (!empty($data)) : ?>
    <table class="wp-list-table widefat fixed fb-pages">
        <thead>
            <tr>
                <th class="manage-column column-icon"></th>
                <th class="manage-column column-title"><?php _e('Page name', FacebookPortal::TEXT_DOMAIN); ?></th>
                <th class="manage-column column-author"><?php _e('Page ID', FacebookPortal::TEXT_DOMAIN); ?></th>
                <th class="manage-column column-date"><?php _e('Last Updated'); ?></th>
                <th class="manage-column column-url"><?php _e('Page URL', FacebookPortal::TEXT_DOMAIN); ?></th>
            </tr>
        </thead>

        <tfoot>
            <tr>
                <th class="manage-column column-icon"></th>
                <th class="manage-column column-title"><?php _e('Page name', FacebookPortal::TEXT_DOMAIN); ?></th>
                <th class="manage-column column-author"><?php _e('Page ID', FacebookPortal::TEXT_DOMAIN); ?></th>
                <th class="manage-column column-date"><?php _e('Last Updated'); ?></th>
                <th class="manage-column column-url"><?php _e('Page URL', FacebookPortal::TEXT_DOMAIN); ?></th>
            </tr>
        </tfoot>

        <tbody id="the-list">
<?php
    $nonce = wp_create_nonce('wpfb_admin_delete');
    foreach ($data as $date) :
?>
            <tr>
                <td class="column-icon media-icon"><a href="<?php echo admin_url('admin.php?page=wpfb_admin&amp;action=edit&amp;id=' . $date['id']); ?>" title="<?php echo sprintf(__('Edit &#8220;%s&#8221;', FacebookPortal::TEXT_DOMAIN), $date['name']); ?>"><img src="<?php echo $date['pic']; ?>" /></a></td>
                <td class="title column-title">
                    <strong><a href="<?php echo admin_url('admin.php?page=wpfb_admin&amp;action=edit&amp;id=' . $date['id']); ?>" title="<?php echo sprintf(__('Edit &#8220;%s&#8221;', FacebookPortal::TEXT_DOMAIN), $date['name']); ?>"><?php echo $date['name']; ?></a></strong>
                    <div class="row-actions">
                        <span class="edit"><a href="<?php echo admin_url('admin.php?page=wpfb_admin&amp;action=edit&amp;id=' . $date['id']); ?>" title="<?php _e('Edit this item', FacebookPortal::TEXT_DOMAIN); ?>"><?php _e('Edit'); ?></a> | </span>
                        <span class="delete"><a href="<?php echo admin_url('admin.php?page=wpfb_admin&amp;action=delete&amp;_wpnonce=' . $nonce . '&amp;id=' . $date['id']); ?>" title="<?php _e('Delete this item', FacebookPortal::TEXT_DOMAIN); ?>"><?php _e('Delete'); ?></a> | </span>
                        <span class="update"><a href="<?php echo admin_url('admin.php?page=wpfb_admin&amp;action=update&amp;id=' . $date['id']); ?>" title="<?php echo sprintf(__('Update &#8220;%s&#8221;', FacebookPortal::TEXT_DOMAIN), $date['name']); ?>"><?php _e('Update'); ?></a> | </span>
                        <span class="view"><a href="<?php echo $date['page_url']; ?>" title="<?php echo sprintf(__('View &#8220;%s&#8221;', FacebookPortal::TEXT_DOMAIN), $date['name']); ?>" target="_blank"><?php _e('View'); ?></a></span>
                    </div>
                </td>
                <td class="page_id column-page_id"><?php echo $date['facebook_page_id']; ?></td>
                <td class="date column-date"><?php echo ($date['post_updated']) ? FacebookPortal::date('Y-m-d H:i:s', $date['post_updated']) : ''; ?></td>
                <td class="column-url"><a href="<?php echo $date['page_url']; ?>" title="<?php echo sprintf(__('View &#8220;%s&#8221;', FacebookPortal::TEXT_DOMAIN), $date['name']); ?>" target="_blank"><?php echo $date['page_url']; ?></a></td>
            </tr>
<?php endforeach; ?>
       </tbody>
    </table>
<?php endif; ?>
</div>
