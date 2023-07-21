<?php

namespace DmytroBezkrovnyi\SportsWidgets\View;

use DmytroBezkrovnyi\SportsWidgets\Entity\Popup;

if (empty($args)) {
    return;
}
?>
    <div class="vcsw-wrap">
        <h1><?php echo $args['page_title']; ?></h1>
        <div class="vcsw-settings-wrapper popup-settings">
            <div class="input-field-row">
                <p>
                    <strong>
                        <?php echo __('Popup image', VCSW_DOMAIN); ?>
                    </strong>
                </p>
                <div class="vcsw-image-preview">
                    <?php
                    if (! empty($args['popup_settings']['images'])
                        && is_array($args['popup_settings']['images'])) :
                        foreach ($args['popup_settings']['images'] as $index => $item) : ?>
                            <div class="vcsw--image-block-wrap">
                                <div class="vcsw--image-wrap">
                                            <span class="js--vcsw-remove-popup-image vcsw-remove-popup-image">&#9587;</span>
                                    <img alt="<?php echo $item['alt'] ? : ''; ?>" class="vcsw--popup_logo"
                                         src="<?php echo $item['src'] ? : ''; ?>"/>
                                </div>
                                <div class="input-field">
                                    <label for="js--vcsw-popup-logo-url-<?php echo $index; ?>">
                                        <?php echo __('Here you can paste URL for image', VCSW_DOMAIN); ?>
                                    </label>
                                    <input type="url"
                                           class="js--vcsw-popup-logo-url"
                                           id="js--vcsw-popup-logo-url-<?php echo $index; ?>"
                                           placeholder="<?php echo __('Enter URL', VCSW_DOMAIN); ?>"
                                           value="<?php echo $item['link']; ?>"
                                    />
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <button class="vcsw-btn"
                        id="js--upload_image_button"
                        type="button">
                    <?php echo __('Add popup image', VCSW_DOMAIN); ?>
                </button>
            </div>
            <div class="input-field-row">
                <p><strong><?php echo __('Popup disclaimer text', VCSW_DOMAIN); ?></strong></p>
                <div class="input-field">
                    <label for="popup_disclaimer_text">
                        <?php echo __('Here you can type text which will be shown in the betting popup', VCSW_DOMAIN); ?>
                    </label>
                    <textarea class="vcsw-textarea"
                              id="popup_disclaimer_text"
                              placeholder="<?php echo __('Enter disclaimer text here', VCSW_DOMAIN); ?>"
                              value="<?php echo $args['disclaimer_text'] ?>"
                    ><?php echo $args['popup_settings']['disclaimer_text']; ?></textarea>
                </div>
            </div>
            <div class="input-field-row">
                <div class="input-field">
                    <p><strong><?php echo __('Select default market', VCSW_DOMAIN); ?></strong></p>
                    <label for="js--vcsw-default-market">
                        <?php echo __(' Here you can choose market which will be shown in popup by default', VCSW_DOMAIN); ?>
                    </label>
                    <select class="vcsw-admin-select" id="js--vcsw-default-market">
                        <option value disabled><?php echo __('Select default market', VCSW_DOMAIN); ?></option>
                        <?php foreach ($args['selected_markets'] as $market) : ?>
                            <option
                                value="<?php echo $market['_id']; ?>"
                                <?php echo $market['_id'] === $args['popup_settings']['default_market'] ? 'selected' : ''; ?>
                            ><?php echo $market['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <p><?php echo __(' <strong class="vcsw-warning">Warning: </strong><i>this market will be used by default on the frontend of the site.</i>', VCSW_DOMAIN); ?></p>
            </div>
            <div class="input-field-row">
                <p><strong><?php echo __('Bet button text', VCSW_DOMAIN); ?></strong></p>
                <div class="input-field">
                    <label for="js--vcsw-popup-btn-text">
                        <?php echo __('This text will appear when the button does&#039;nt have odds', VCSW_DOMAIN); ?>
                    </label>
                    <input type="text"
                           id="js--vcsw-popup-btn-text"
                           placeholder="<?php echo __('Enter text for popup button', VCSW_DOMAIN); ?>"
                           value="<?php echo $args['vcsw_popup_btn_text'] ?: __('Bet now', VCSW_DOMAIN); ?>"
                    >
                </div>
            </div>
        </div>
        <div class="vcsw-button-block">
            <button type="button"
                    id="js--save-popup-settings"
                    class="vcsw-btn vcsw-btn-with-loading vcsw-setting-btn"
                    data-nonce="<?php echo wp_create_nonce('vcsw_save_popup_options'); ?>">
                <?php echo __('Update', VCSW_DOMAIN); ?>
                <div class="vcsw-lds-ellipsis"><div></div><div></div><div></div><div></div></div>
            </button>
            <span class="vcsw-message-success"><?php echo __('Saved successfully!', VCSW_DOMAIN); ?></span>
            <span class="vcsw-message-error"><?php echo __('Error! Please try again!', VCSW_DOMAIN); ?></span>
        </div>
    </div>
<?php
