<?php

namespace DmytroBezkrovnyi\SportsWidgets\View;

if (empty($args)) {
    return;
}
?>
    <div class="vcsw-wrap">
        <h1><?php echo $args['page_title']; ?></h1>
        <form action="#" class="vcsw-styles-form">
            <div class="vcsw-settings-wrapper vcsw-settings-wrapper--theme">
                <div id="js--styles-preview" class="vcsw-sports-widgets">
                    <div>
                        <span><?php echo __('Sports Fixtures preview', VCSW_DOMAIN); ?></span>
                        <div class="vcsw-fixtures-widget" id="js--fixture-preview">
                            <script type="application/json">
                                {
                                    "sports_type": "football",
                                    "competition": "",
                                    "date": ""
                                }
                            </script>
                        </div>
                    </div>
                    <div>
                        <span><?php echo __('League standings preview', VCSW_DOMAIN); ?></span>
                        <div class="vcsw-league-standings" id="js--league-standings-preview">
                            <script type="application/json">
                            {
                            "sports_type": "football",
                            "competition": "<?php echo $args['default_competition_id']; ?>"
                            }
                        </script>
                        </div>
                    </div>
                </div>
                <input type="checkbox" id="styles-settings-input" checked class="styles-settings-input">
                <div class="vcsw-editor-style-settings">
                    <label for="styles-settings-input" class="collapse-button">
                        <svg height="800px" width="800px" xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 185.343 185.343">
                            <g>
                                <g>
                                    <path d="M51.707,185.343c-2.741,0-5.493-1.044-7.593-3.149c-4.194-4.194-4.194-10.981,0-15.175
                                        l74.352-74.347L44.114,18.32c-4.194-4.194-4.194-10.987,0-15.175c4.194-4.194,10.987-4.194,15.18,0l81.934,81.934
                                        c4.194,4.194,4.194,10.987,0,15.175l-81.934,81.939C57.201,184.293,54.454,185.343,51.707,185.343z"/>
                                </g>
                            </g>
                        </svg>
                    </label>
                    <div class="js--vcsw-theme">
                        <h6>Font</h6>
                        <div class="input-field">
                            <label for="--vcsw-font-family" class="vcsw-styles-label">
                                <?php echo __('Select Font', VCSW_DOMAIN); ?>
                            </label>
                            <select class="vcsw-admin-select" id="--vcsw-font-family">
                                <option value disabled selected><?php echo __('Select Font for all blocks',
                                        VCSW_DOMAIN
                                    ); ?></option>
                                <?php
                                foreach ($args['fonts'] as $font): ?>
                                    <option value="<?php echo $font; ?>" <?php echo $font === $args['selected_font']
                                        ? 'selected' : ''; ?>><?php echo $font; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <h6><?php echo __('Main colors', VCSW_DOMAIN); ?></h6>
                        <div class="js--vcsw-theme-main-vars"></div>
                        <h6><?php echo __('Full Customization', VCSW_DOMAIN); ?></h6>
                        <div class="js--vcsw-theme-full-vars"></div>
                    </div>
                </div>
            </div>
            <div class="vcsw-button-block">
                <button type="button"
                        id="js--save-styles-settings"
                        class="vcsw-btn vcsw-btn-with-loading vcsw-setting-btn"
                        data-nonce="<?php echo wp_create_nonce('vcsw_save_styles_options'); ?>">
                    <?php echo __('Update', VCSW_DOMAIN); ?>
                    <div class="vcsw-lds-ellipsis">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </button>
                <button type="reset"
                        id="js--reset-styles-settings"
                        class="vcsw-btn vcsw-btn-danger vcsw-btn-with-loading"> <?php echo __('Reset', VCSW_DOMAIN); ?>
                </button>
                <span class="vcsw-message-success"><?php echo __('Saved successfully!', VCSW_DOMAIN); ?></span>
                <span class="vcsw-message-error"><?php echo __('Error! Please try again!', VCSW_DOMAIN); ?></span>
            </div>
        </form>

    </div>
<?php
