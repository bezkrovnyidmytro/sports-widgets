<?php

namespace DmytroBezkrovnyi\SportsWidgets\View;

if (empty($args)) {
    return;
}

$countAllMarkets = ! empty($args['markets_list']) && is_countable($args['markets_list']) ? count($args['markets_list']
) : 0;
?>
    <div class="vcsw-wrap">
        <h1><?php echo $args['page_title']; ?></h1>
        <div class="vcsw-wrap-desc-text">
            <p><?php echo __('On this page you can activate/deactivate markets.', VCSW_DOMAIN); ?></p>
            <p><?php echo __('Total number of markets in the database:', VCSW_DOMAIN); ?>
                <?php echo $countAllMarkets; ?>.
            </p>
        </div>
        <div class="vcsw-settings-wrapper">
            <ul class="vcsw-settings-list">
                <li class="row heading-row grey-text">
                    <div class="col s2"><span><?php echo __('Id', VCSW_DOMAIN); ?></span></div>
                    <div class="col s2"><span><?php echo __('Name', VCSW_DOMAIN); ?></span></div>
                    <div class="col s2"><span><?php echo __('Sport type', VCSW_DOMAIN); ?></span></div>
                    <div class="col s2"><span><?php echo __('Status', VCSW_DOMAIN); ?></span></div>
                </li>
                <?php if (! empty($args['markets_list'])): ?>
                    <?php foreach ($args['markets_list'] as $market):
                        $isSelected = in_array($market['_id'], $args['selected_markets']);
                        ?>
                        <li>
                            <div class="<?php echo $isSelected === false ? 'row vcsw-inactive-row' : 'row'; ?>">
                                <div class="col s2">
                                    <span class="vcsw-subtitle black-text"><?php echo $market['_id']; ?></span>
                                </div>
                                <div class="col s2">
                                    <span class="vcsw-subtitle black-text"><?php echo $market['name']; ?></span>
                                </div>
                                <div class="col s2">
                                    <span><?php echo $market['sport_type']; ?></span>
                                </div>
                                <div class="col s2">
                                    <div class="switch">
                                        <label for="market-<?php echo $market['id']; ?>">
                                            <input
                                                class="js--vcsw-selectable-markets js--vcsw-checkboxes"
                                                type="checkbox"
                                                name="market"
                                                value="<?php echo $market['_id']; ?>"
                                                id="market-<?php echo $market['id']; ?>"
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
                        <p><?php echo __('Seems like there are no markets in the database at the moment.',
                                VCSW_DOMAIN
                            ); ?></p>
                        <p><?php echo __('You can try to sync manually at', VCSW_DOMAIN); ?> <a
                                href="<?php menu_page_url('vcsw_settings_sync_jobs'); ?>"><?php echo __('sync page',
                                    VCSW_DOMAIN
                                ); ?></a></p>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="vcsw-button-block">
            <button
                type="button"
                id="js--save-markets-settings"
                class="vcsw-btn vcsw-btn-with-loading vcsw-setting-btn"
                data-nonce="<?php echo wp_create_nonce('vcsw_save_markets_options'); ?>"
                <?php echo empty($countAllMarkets) ? 'disabled' : ''; ?>>
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
