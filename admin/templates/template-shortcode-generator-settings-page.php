<?php

namespace DmytroBezkrovnyi\SportsWidgets\View;

use DmytroBezkrovnyi\SportsWidgets\Shortcode\LeagueStandingsShortcode;
use DmytroBezkrovnyi\SportsWidgets\Shortcode\SportsFixturesShortcode;

if (empty($args)) {
    return;
}
?>
    <div class="vcsw-wrap">
        <h1><?php echo $args['page_title']; ?></h1>
        <div class="vcsw-wrap-desc-text">
            <p><?php echo __('On this page you can generate a shortcode to display Sports Fixtures.',
                    VCSW_DOMAIN
                ); ?></p>
        </div>
        <div class="vcsw-settings-wrapper vcsw-row">
            <div class="col s6">
                <h5><?php echo __('Select options before generate the preview.', VCSW_DOMAIN); ?></h5>
                <div class="input-field-row">
                    <p>
                        <strong>
                            <?php echo __('Select shortcode to preview.', VCSW_DOMAIN); ?>
                        </strong>
                    </p>
                    <div class="input-field">
                        <label for="js--vcsw-shortcode-generator-shortcode-name">
                            <?php echo __('Here is you can choose the shortcode.', VCSW_DOMAIN); ?>
                        </label>
                        <select class="vcsw-admin-select js--shortcode-generator-select"
                                id="js--vcsw-shortcode-generator-shortcode-name" name="shortcode_name">
                            <?php foreach ($args['shortcodes_list'] as $index => $sc_name): ?>
                                <option
                                    value="<?php echo $sc_name; ?>" <?php echo $index === 0 ? 'selected' : ''; ?> ><?php echo ucwords(str_replace('_',
                                        ' ',
                                        $sc_name
                                    )
                                    ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="input-field-row">
                    <p>
                        <strong>
                            <?php echo __('Select Sport Type.', VCSW_DOMAIN); ?>
                        </strong>
                    </p>
                    <div class="input-field">
                        <label for="js--vcsw-shortcode-generator-sports-type">
                            <?php echo __('Here is you can choose sport type.', VCSW_DOMAIN); ?>
                        </label>
                        <select class="vcsw-admin-select js--shortcode-generator-select js--shortcode-attribute"
                                id="js--vcsw-shortcode-generator-sports-type"
                                name="sports_type">
                            <?php foreach ($args['sports_types'] as $sport_type): ?>
                                <option
                                    value="<?php echo $sport_type; ?>" <?php echo $sport_type === 'football' ? 'selected' : ''; ?> ><?php echo ucwords($sport_type
                                    ); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="input-field-row">
                    <p>
                        <strong>
                            <?php echo __('Select a season.', VCSW_DOMAIN); ?>
                        </strong>
                    </p>
                    <div class="input-field">
                        <label for="js--vcsw-shortcode-generator-season">
                            <?php echo __('Here is you can choose a season.', VCSW_DOMAIN); ?>
                        </label>
                        <select class="vcsw-admin-select js--shortcode-generator-select"
                                id="js--vcsw-shortcode-generator-season" name="season">
                            <option value="" selected>Select a season</option>
                            <?php foreach ($args['seasons'] as $season): ?>
                                <option value="<?php echo $season; ?>"><?php echo $season; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="input-field-row">
                    <p>
                        <strong>
                            <?php echo __(' Select a competition.', VCSW_DOMAIN); ?>
                        </strong>
                    </p>
                    <div class="input-field">
                        <label for="js--vcsw-shortcode-generator-competition">
                            <?php echo __('Here is you can choose a competition.', VCSW_DOMAIN); ?>
                        </label>
                        <select class="vcsw-admin-select js--shortcode-generator-select js--shortcode-attribute"
                                id="js--vcsw-shortcode-generator-competition" name="competition">
                            <option value selected><?php echo __('Select a competition', VCSW_DOMAIN); ?></option>
                            <?php foreach ($args['competitions'] as $competition): ?>
                                <option data-season="<?php echo $competition['season']; ?>"
                                        data-sports-type="<?php echo $competition['sports_type']; ?>"
                                        value="<?php echo $competition['_id']; ?>"><?php
                                    echo $competition['competition'] . ' | '
                                         . $competition['country'] . ' | '
                                         . $competition['season'];
                                    ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="input-field-row">
                    <p>
                        <strong>
                            <?php echo __('Generated shortcode:', VCSW_DOMAIN); ?>
                        </strong>
                    </p>
                    <div class="vcsw-copy-block">
                        <div class="vcsw-copy-box">
                            <code class="vcsw-copy-value" id="js--vcsw-shortcode-generator-result">
                                []
                            </code>
                            <button type="button" class="vcsw-copy-action js--click-to-copy"
                                    data-copy-from="#js--vcsw-shortcode-generator-result" title="Click to copy">
                                <svg class="vcsw-copy-icon" version="1.1" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 115.77 122.88"
                                     xml:space="preserve">
											<g>
                                                <path class="st0"
                                                      d="M89.62,13.96v7.73h12.19h0.01v0.02c3.85,0.01,7.34,1.57,9.86,4.1c2.5,2.51,4.06,5.98,4.07,9.82h0.02v0.02 v73.27v0.01h-0.02c-0.01,3.84-1.57,7.33-4.1,9.86c-2.51,2.5-5.98,4.06-9.82,4.07v0.02h-0.02h-61.7H40.1v-0.02 c-3.84-0.01-7.34-1.57-9.86-4.1c-2.5-2.51-4.06-5.98-4.07-9.82h-0.02v-0.02V92.51H13.96h-0.01v-0.02c-3.84-0.01-7.34-1.57-9.86-4.1 c-2.5-2.51-4.06-5.98-4.07-9.82H0v-0.02V13.96v-0.01h0.02c0.01-3.85,1.58-7.34,4.1-9.86c2.51-2.5,5.98-4.06,9.82-4.07V0h0.02h61.7 h0.01v0.02c3.85,0.01,7.34,1.57,9.86,4.1c2.5,2.51,4.06,5.98,4.07,9.82h0.02V13.96L89.62,13.96z M79.04,21.69v-7.73v-0.02h0.02 c0-0.91-0.39-1.75-1.01-2.37c-0.61-0.61-1.46-1-2.37-1v0.02h-0.01h-61.7h-0.02v-0.02c-0.91,0-1.75,0.39-2.37,1.01 c-0.61,0.61-1,1.46-1,2.37h0.02v0.01v64.59v0.02h-0.02c0,0.91,0.39,1.75,1.01,2.37c0.61,0.61,1.46,1,2.37,1v-0.02h0.01h12.19V35.65 v-0.01h0.02c0.01-3.85,1.58-7.34,4.1-9.86c2.51-2.5,5.98-4.06,9.82-4.07v-0.02h0.02H79.04L79.04,21.69z M105.18,108.92V35.65v-0.02 h0.02c0-0.91-0.39-1.75-1.01-2.37c-0.61-0.61-1.46-1-2.37-1v0.02h-0.01h-61.7h-0.02v-0.02c-0.91,0-1.75,0.39-2.37,1.01 c-0.61,0.61-1,1.46-1,2.37h0.02v0.01v73.27v0.02h-0.02c0,0.91,0.39,1.75,1.01,2.37c0.61,0.61,1.46,1,2.37,1v-0.02h0.01h61.7h0.02 v0.02c0.91,0,1.75-0.39,2.37-1.01c0.61-0.61,1-1.46,1-2.37h-0.02V108.92L105.18,108.92z"/>
                                            </g>
										</svg>
                                <svg class="vcsw-copied-icon" version="1.1" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 122.879 109.721"
                                     xml:space="preserve">
											<g>
                                                <path
                                                    d="M31.989,55.49c-4.336-3.752-4.808-10.31-1.057-14.646c3.753-4.335,10.311-4.81,14.646-1.057l16.2,14.045l43.211-45.69 c3.933-4.156,10.49-4.338,14.647-0.408c4.156,3.932,4.34,10.49,0.408,14.646l-49.55,52.394l-0.173,0.197 c-3.751,4.336-10.309,4.809-14.644,1.059L31.989,55.49L31.989,55.49z M18.819,0h72.087h0.005v0.036h0.029L73.203,18.284H18.819 h-0.014V18.25c-0.104,0-0.234,0.075-0.354,0.195l-0.018-0.018l-0.018,0.018c-0.102,0.102-0.166,0.233-0.166,0.367h0.036v0.005 v72.087v0.012H18.25c0,0.104,0.077,0.234,0.197,0.355l-0.018,0.018l0.018,0.016c0.102,0.104,0.233,0.166,0.365,0.166v-0.033h0.007 h72.087h0.012v0.033c0.129,0,0.27-0.074,0.39-0.195c0.103-0.102,0.166-0.232,0.166-0.367h-0.034v-0.004V73.059l18.284-18.813 v36.658v0.004h-0.037c-0.002,5.184-2.117,9.893-5.521,13.293c-3.379,3.379-8.068,5.479-13.247,5.484v0.035h-0.012H18.819h-0.007 v-0.035c-5.175-0.004-9.874-2.111-13.275-5.506l-0.018,0.018c-3.379-3.379-5.477-8.08-5.483-13.281H0v-0.012V18.817v-0.005h0.036 c0.002-5.177,2.111-9.873,5.506-13.274L5.524,5.52c3.378-3.381,8.077-5.479,13.282-5.483V0H18.819L18.819,0z"/>
                                            </g>
										</svg>
                            </button>
                        </div>
                        <span class="vcsw-info-copy vcsw-message-success"><?php echo __('Copied!',
                                VCSW_DOMAIN
                            ); ?></span>
                    </div>
                </div>
            </div>
            <div class="col s6">
                <h5><?php echo __('Shortcode preview', VCSW_DOMAIN); ?></h5>
                <div class="js--shortcode-generator-previews">
                    <div
                        class="js--shortcode-generator-preview"
                        id="js--preview-<?php echo SportsFixturesShortcode::getShortcodeName(); ?>"
                        style="display: block;"
                    >
                        <?php echo __('Sports Fixtures shortcode', VCSW_DOMAIN); ?>
                        <div class="vcsw-sports-widgets" id="js--shortcode-generator-preview">
                            <script type="application/json">
                                {
                                    "sports_type": "football",
                                    "competition": "",
                                    "date": "",
                                    "widget_type": "fixtures"
                                }
                            </script>
                        </div>
                    </div>
                    <div
                        class="js--shortcode-generator-preview"
                        id="js--preview-<?php echo LeagueStandingsShortcode::getShortcodeName(); ?>"
                        style="display: none;"
                    >
                        <?php echo __('League standings shortcode', VCSW_DOMAIN); ?>
                        <div class="vcsw-sports-widgets" id="js--shortcode-generator-preview-table">
                            <script type="application/json">
                                {
                                    "sports_type": "football",
                                    "competition": "",
                                    "widget_type": "league_table"
                                }
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="vcsw-buttons-holder">
            <div class="vcsw-button-block">
                <a
                    href="<?php echo admin_url('admin.php?page=vcsw_settings_shortcode_generator'); ?>"
                    class="vcsw-btn vcsw-btn-with-loading vcsw-setting-btn"
                >
                    <?php echo __('Reset', VCSW_DOMAIN); ?>
                </a>
            </div>
        </div>
    </div>
<?php
