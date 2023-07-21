<?php

namespace DmytroBezkrovnyi\SportsWidgets\View;

if (empty($args)) {
    return;
}

$countAllBookmakers = ! empty($args['bookmakers']) && is_countable($args['bookmakers'])
    ? count($args['bookmakers'])
    : 0;
$countSelectedBookmakers = ! empty($args['selected_bookmakers']) && is_countable($args['selected_bookmakers'])
    ? count($args['selected_bookmakers'])
    : 0;
?>
    <div class="vcsw-wrap">
        <h1><?php echo $args['page_title']; ?></h1>
        <div class="vcsw-wrap-desc-text">
            <p><?php echo __('On this page you can change order of bookmakers with drag & drop and activate/deactivate it.',
                    VCSW_DOMAIN
                ); ?></p>
            <p><?php echo __('Total number of bookmakers in the database:', VCSW_DOMAIN); ?>
                <?php echo $countAllBookmakers; ?>.
            </p>
            <p><?php echo __('Total number of selected bookmakers in the database:', VCSW_DOMAIN); ?>
                <?php echo $countSelectedBookmakers; ?>.
            </p>
        </div>
        <div class="vcsw-sticky-parent">
            <div class="vcsw-settings-wrapper">
                <ul class="vcsw-settings-list vcsw-drag-n-drop-container vcsw-bookmakers-list">
                    <li>
                        <div class="row heading-row">
                            <div class="col s2">
                                <span><?php echo __('Logo', VCSW_DOMAIN); ?></span>
                            </div>
                            <div class="col s2">
                                <span><?php echo __('Name', VCSW_DOMAIN); ?></span>
                            </div>
                            <div class="col s2">
                                <span><?php echo __('Location', VCSW_DOMAIN); ?></span>
                            </div>
                            <div class="col s2">
                                <span><?php echo __('Affiliate link', VCSW_DOMAIN); ?></span>
                            </div>
                            <div class="col s1">
                                <span><?php echo __('Edit', VCSW_DOMAIN); ?></span>
                            </div>
                            <div class="col s1">
                                <span><?php echo __('Status', VCSW_DOMAIN); ?></span>
                            </div>
                        </div>
                    </li>
                    <?php if (! empty($args['bookmakers'])): ?>
                        <?php foreach ($args['bookmakers'] as $bookmaker):
                            // get all post meta directly per row
                            $bookmaker_core_id = get_post_meta($bookmaker->ID, 'bookmaker_id', true) ? : '';
                            $logo_bg_color = get_post_meta($bookmaker->ID, 'bg_color', true) ? : '';
                            $affiliate_link = get_post_meta($bookmaker->ID, 'affiliate_link', true) ? : '';
                            $locations_list = get_the_terms($bookmaker->ID, 'vcsw_geo_location');
                            $isSelected = in_array($bookmaker_core_id, $args['selected_bookmakers']);
                            ?>
                            <li class="vcsw-drag-n-drop-item" draggable="true">
                                <svg class="vcsw-draggable-icon" viewBox="0 0 1024 1024" version="1.1"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M469.333333 256a85.333333 85.333333 0 1 1-85.333333-85.333333 85.333333 85.333333 0 0 1 85.333333 85.333333z m-85.333333 170.666667a85.333333 85.333333 0 1 0 85.333333 85.333333 85.333333 85.333333 0 0 0-85.333333-85.333333z m0 256a85.333333 85.333333 0 1 0 85.333333 85.333333 85.333333 85.333333 0 0 0-85.333333-85.333333z m256-341.333334a85.333333 85.333333 0 1 0-85.333333-85.333333 85.333333 85.333333 0 0 0 85.333333 85.333333z m0 85.333334a85.333333 85.333333 0 1 0 85.333333 85.333333 85.333333 85.333333 0 0 0-85.333333-85.333333z m0 256a85.333333 85.333333 0 1 0 85.333333 85.333333 85.333333 85.333333 0 0 0-85.333333-85.333333z"/>
                                </svg>
                                <div class="<?php echo $isSelected === false ? 'row vcsw-inactive-row' : 'row'; ?>">
                                    <div class="col s2">
                                        <?php if (has_post_thumbnail($bookmaker->ID)): ?>
                                            <div
                                                class="vcsw-bookmaker-logo" <?php echo $logo_bg_color ? 'style="background-color:' . $logo_bg_color . ';"' : ''; ?>>
                                                <?php echo get_the_post_thumbnail($bookmaker->ID,
                                                    'full',
                                                    ['alt' => $bookmaker->post_title, 'title' => $bookmaker->post_title]
                                                ); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col s2">
                                        <span
                                            class="vcsw-subtitle black-text"><?php echo $bookmaker->post_title; ?></span>
                                    </div>
                                    <div class="col s2">
                                        <div class="vcsw-countries-list">
                                            <?php echo ! empty($locations_list)
                                                ? join(', ', wp_list_pluck($locations_list, 'slug'))
                                                : '<span class="vcsw-all-countries">' . __('All countries',
                                                    VCSW_DOMAIN
                                                ) . '</span>'; ?>
                                        </div>
                                    </div>
                                    <div class="col s2">
                                        <?php echo ! empty($affiliate_link)
                                            ? '<a class="vcsw-affiliate-link" href="' . $affiliate_link . '" target="_blank" rel="noindex nofollow noopener">' . $affiliate_link . '</a>'
                                            : '<span>-</span>'; ?>
                                    </div>
                                    <div class="col s1">
                                        <a href="<?php echo get_edit_post_link($bookmaker->ID); ?>" target="_blank"
                                           class="edit-bookmaker-link">
                                            <span class="dashicons dashicons-edit"></span>
                                        </a>
                                    </div>
                                    <div class="col s1">
                                        <div class="switch">
                                            <label for="bookmaker-<?php echo $bookmaker->ID; ?>">
                                                <input
                                                    class="js--vcsw-selectable-bookmakers js--vcsw-checkboxes"
                                                    type="checkbox"
                                                    id="bookmaker-<?php echo $bookmaker->ID; ?>"
                                                    name="bookmaker-<?php echo $bookmaker->ID; ?>"
                                                    value="<?php echo $bookmaker_core_id; ?>"
                                                    <?php echo $isSelected === true ? 'checked' : ''; ?>
                                                >
                                                <span class="lever"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>
                            <p><?php echo __('Seems like there are no bookmakers in the database at the moment',
                                    VCSW_DOMAIN
                                ); ?>
                            </p>
                            <p>
                                <?php echo __('You can try to sync manually at', VCSW_DOMAIN); ?>
                                <a href="<?php menu_page_url('vcsw_settings_sync_jobs'); ?>">
                                    <?php echo __('sync page', VCSW_DOMAIN); ?>
                                </a>
                            </p>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="vcsw-button-block vcsw-sticky-bottom-block">
                <button
                    type="button"
                    id="js--save-bookmakers-settings"
                    class="vcsw-btn vcsw-btn-with-loading vcsw-setting-btn"
                    data-nonce="<?php echo wp_create_nonce('vcsw_save_bookmakers_options'); ?>"
                    <?php echo empty($countAllBookmakers) ? 'disabled' : ''; ?>
                >
                    <?php echo __('Update', VCSW_DOMAIN); ?>
                    <div class="vcsw-lds-ellipsis">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </button>
                <span class="vcsw-message-success"><?php echo __('Saved successfully!', VCSW_DOMAIN); ?></span>
                <span class="vcsw-message-error"><?php echo __('Error! Please try again!', VCSW_DOMAIN); ?></span>
            </div>
        </div>
    </div>
<?php
