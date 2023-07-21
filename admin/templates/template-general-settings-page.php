<?php

namespace DmytroBezkrovnyi\SportsWidgets\View;

if (empty($args)) {
    return;
}
?>
    <div class="vcsw-wrap">
        <h1><?php echo $args['page_title']; ?></h1>
        <div class="vcsw-settings-wrapper">
            <div class="input-field-row">
                <p><strong><?php echo __('Select  API data source', VCSW_DOMAIN); ?></strong></p>
                <div class="input-field">
                    <label for="js--vcsw-data-source">
                        <?php echo __('Here is you can choose where it should take data for the sport widget',
                            VCSW_DOMAIN
                        ); ?>
                    </label>
                    <select class="vcsw-admin-select" id="js--vcsw-data-source">
                        <option value="" disabled selected>Select data source</option>
                        <?php foreach ($args['core_data_sources'] as $key => $label): ?>
                            <option
                                value="<?php echo $key; ?>"<?php echo $key === $args['vcsw_core_data_source'] ? 'selected' : ''; ?>><?php echo $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <p><?php echo __('<strong class="vcsw-warning">Warning: </strong><i>after you have changed the data source - you need to synchronize all the data, otherwise the data that users will see will be wrong (bookmakers, competitions and markets)!</i>',
                        VCSW_DOMAIN
                    ); ?></p>
            </div>
            <div class="input-field-row">
                <p><strong><?php echo __('Select the language for the plugin', VCSW_DOMAIN); ?></strong></p>
                <div class="input-field">
                    <label for="js--vcsw-language">
                        <?php echo __('Here is you can choose language for the sport widget', VCSW_DOMAIN); ?>
                    </label>
                    <select id="js--vcsw-language" class="vcsw-admin-select">
                        <?php foreach ($args['languages'] as $key => $label): ?>
                            <option
                                value="<?php echo $key; ?>"<?php echo $key === $args['vcsw_front_end_language'] ? 'selected' : ''; ?>><?php echo $label; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <p><?php echo __('<strong class="vcsw-warning">Warning: </strong><i>after you have changed the language - you need to synchronize all the data (competitions and markets)!</i>',
                        VCSW_DOMAIN
                    ); ?></p>
            </div>
            <div class="input-field-row">
                <p><strong><?php echo __('Select Sport Type', VCSW_DOMAIN); ?></strong></p>
                <div class="input-field">
                    <label for="js--vcsw-sport-type">
                        <?php echo __('Here is you can choose sport types which you want to show on sport widget',
                            VCSW_DOMAIN
                        ); ?>
                    </label>
                    <select class="vcsw-admin-select" id="js--vcsw-sport-type" multiple>
                        <?php foreach ($args['core_supported_sport_types'] as $value): ?>
                            <option value="<?php echo $value['slug']; ?>" <?php echo in_array($value['slug'],
                                $args['vcsw_selected_sport_types']
                            ) ? 'selected' : ''; ?> ><?php echo $value['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <p>
                    <?php echo __('<strong class="vcsw-warning">Warning:</strong> <i>after you have change/add sport type - you need to synchronize all the data, otherwise the data that users will see will be wrong (bookmakers, competitions and markets)!</i>',
                        VCSW_DOMAIN
                    ); ?>
                </p>
            </div>
        </div>
        <div class="vcsw-button-block">
            <button
                type="button"
                id="js--save-general-settings"
                class="vcsw-btn vcsw-btn-with-loading vcsw-setting-btn"
                data-nonce="<?php echo wp_create_nonce('vcsw_save_general_options'); ?>">
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
<?php
