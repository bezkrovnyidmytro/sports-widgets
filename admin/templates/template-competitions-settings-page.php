<?php

namespace DmytroBezkrovnyi\SportsWidgets\View;

if (empty($args)) {
    return;
}
?>
    <div class="vcsw-wrap">
        <h1><?php echo $args['page_title']; ?></h1>
        <div class="vcsw-wrap-desc-text">
            <p><?php echo __('On this page you can change order of competitions with drag & drop and activate/deactivate it.',
                    VCSW_DOMAIN
                ); ?></p>
            <p><?php echo __('Total number of competitions in the database:', VCSW_DOMAIN); ?>
                <?php echo $args['count_all_competitions']; ?>.
            </p>
            <p><?php echo __('Total number of selected competitions in the database:', VCSW_DOMAIN); ?>
                <?php echo $args['count_selected_competitions']; ?>.
            </p>
        </div>
        <div class="vcsw-sticky-parent">
            <div class="vcsw-settings-wrapper">
                <div class="vcsw-filter-container">
                    <ul class="vcsw-settings-list vcsw-drag-n-drop-container vcsw-counter-list">
                        <li>
                            <div class="col-number">
                                <span class="number-heading">#</span>
                            </div>
                            <div class="row heading-row vcsw-filters-headings">
                                <div class="col s2">
                                    <span><?php echo __('Name', VCSW_DOMAIN); ?></span>
                                </div>
                                <div class="col s2 vcsw-has-filter">
                                    <span><?php echo __('Country', VCSW_DOMAIN); ?></span>
                                </div>
                                <div class="col s2">
                                    <span><?php echo __('Season', VCSW_DOMAIN); ?></span>
                                </div>
                                <div class="col s2 vcsw-has-filter">
                                    <span><?php echo __('Sport type', VCSW_DOMAIN); ?></span>
                                </div>
                                <div class="col s3">
                                    <span><?php echo __('Content', VCSW_DOMAIN); ?></span>
                                </div>
                                <div class="col s1">
                                    <label class="vcsw-check-all" for="vcsw--select-all-competitions">
                                        <input type="checkbox" data-chk-parent="grid-group-1"
                                               id="vcsw--select-all-competitions">
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </li>
                        <?php if (! empty($args['competitions_list'])): ?>
                            <?php foreach ($args['competitions_list'] as $competition):
                                $isSelected = $competition['selected'] ?? false;
                                $content = ! empty($competition['content']) ? $competition['content'] : '';
                                ?>
                                <li class="vcsw-drag-n-drop-item vcsw-filters-row" draggable="true">
                                    <div class="col-number">
                                        <svg class="vcsw-draggable-icon" viewBox="0 0 1024 1024" version="1.1"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M469.333333 256a85.333333 85.333333 0 1 1-85.333333-85.333333 85.333333 85.333333 0 0 1 85.333333 85.333333z m-85.333333 170.666667a85.333333 85.333333 0 1 0 85.333333 85.333333 85.333333 85.333333 0 0 0-85.333333-85.333333z m0 256a85.333333 85.333333 0 1 0 85.333333 85.333333 85.333333 85.333333 0 0 0-85.333333-85.333333z m256-341.333334a85.333333 85.333333 0 1 0-85.333333-85.333333 85.333333 85.333333 0 0 0 85.333333 85.333333z m0 85.333334a85.333333 85.333333 0 1 0 85.333333 85.333333 85.333333 85.333333 0 0 0-85.333333-85.333333z m0 256a85.333333 85.333333 0 1 0 85.333333 85.333333 85.333333 85.333333 0 0 0-85.333333-85.333333z"/>
                                        </svg>
                                    </div>
                                    <div
                                        class="vcsw-filters-cells <?php echo $isSelected ? 'row' : 'row vcsw-inactive-row'; ?>">
                                        <div class="col s2">
                                            <span class="vcsw-subtitle black-text"
                                                  data-core-id="<?php echo $competition['_id']; ?>">
                                                <?php echo $competition['competition']; ?>
                                            </span>
                                        </div>
                                        <div class="col s2">
                                            <span><?php echo $competition['country']; ?></span>
                                        </div>
                                        <div class="col s2">
                                            <span><?php echo $competition['season']; ?></span>
                                        </div>
                                        <div class="col s2">
                                            <span class="vcsw-sport-type">
                                                <?php echo $competition['sports_type']; ?>
                                            </span>
                                        </div>
                                        <div class="col s3">
                                            <div class="vcsw-edit-competition">
                                                <span
                                                    class="dashicons dashicons-edit js--add-additional-content"></span>
                                                <div class="competition-content js--competition-content-wrapper hidden">
                                                    <label
                                                        for="competition_content_editor_<?php echo $competition['_id']; ?>"></label>
                                                    <textarea
                                                        class="js--competition-content"
                                                        id="competition_content_editor_<?php echo $competition['_id']; ?>"
                                                    >
                                                    <?php echo $content; ?>
                                                </textarea>
                                                    <button
                                                        type="button"
                                                        class="vcsw-btn js--save-competition-content"
                                                        data-competition-id="<?php echo $competition['_id']; ?>"
                                                    >
                                                        <?php echo __('Save', VCSW_DOMAIN); ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col s1">
                                            <div class="switch">
                                                <label for="competition-<?php echo $competition['id']; ?>">
                                                    <input
                                                        data-chk-child-group="grid-group-1"
                                                        class="js--vcsw-selectable-competitions js--vcsw-checkboxes"
                                                        type="checkbox"
                                                        id="competition-<?php echo $competition['id']; ?>"
                                                        name="competition-<?php echo $competition['id']; ?>"
                                                        value="<?php echo $competition['_id']; ?>"
                                                        <?php echo $isSelected ? 'checked' : ''; ?>
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
                                <p><?php echo __('Seems like there are no competitions in the database at the moment.',
                                        VCSW_DOMAIN
                                    ); ?></p>
                                <p><?php echo __('You can try to sync manually at', VCSW_DOMAIN); ?> <a
                                        href="<?php menu_page_url('vcsw_settings_sync_jobs'
                                        ); ?>"><?php echo __('sync page', VCSW_DOMAIN); ?></a></p>
                            </li>
                        <?php endif; ?>
                        <li class="no-data-item"><?php echo __('No data matches', VCSW_DOMAIN); ?></li>
                    </ul>
                </div>
            </div>
            <div class="vcsw-button-block vcsw-sticky-bottom-block">
                <button
                    type="button" id="js--save-competitions-settings"
                    class="vcsw-btn vcsw-btn-with-loading vcsw-setting-btn"
                    data-nonce="<?php echo wp_create_nonce('vcsw_save_competitions_options'); ?>"
                    <?php echo $args['count_all_competitions'] === 0 ? 'disabled' : ''; ?>
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
