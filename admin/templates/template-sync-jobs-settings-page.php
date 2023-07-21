<?php

namespace DmytroBezkrovnyi\SportsWidgets\View;

use DmytroBezkrovnyi\SportsWidgets\PluginI18N;
use DmytroBezkrovnyi\SportsWidgets\Request\Request;

if(empty($args)) {
    return;
}

$currentCoreDataSource = ucfirst(get_option(Request::$optionName));
$currentSiteLanguage = ucfirst(get_option(PluginI18N::$optionName));
?>
<div class="vcsw-wrap">
    <h1><?php echo $args['page_title']; ?></h1>
    <div class="vcsw-settings-wrapper">
        <div class="sync-jobs-row">
            <p>
                <?php echo __('Current data source: ', VCSW_DOMAIN); ?><i><?php echo $currentCoreDataSource; ?></i>.
            </p>
            <p>
                <?php echo __('Current plugin language: ', VCSW_DOMAIN); ?><i><?php echo $currentSiteLanguage; ?></i>.
            </p>
            <hr/>
        </div>
		<div class="sync-jobs-row">
            <h5><?php echo __('Bookmakers jobs', VCSW_DOMAIN); ?></h5>
            <p><?php echo __('Here is you can sync or remove all bookmakers.', VCSW_DOMAIN); ?></p>
            <div class="vcsw-row">
                <div class="col s6">
                    <div class="buttons-row">
                        <div class="vcsw-button-block">
                            <button
                                class="vcsw-btn vcsw-btn-with-loading vcsw-setting-btn"
                                id="js--sync-bookmakers"
                                data-nonce="<?php echo wp_create_nonce('vcsw_sync_bookmakers'); ?>">
                                <?php echo __('Sync Bookmakers', VCSW_DOMAIN); ?>
                                <div class="vcsw-lds-ellipsis"><div></div><div></div><div></div><div></div></div>
                            </button>
                            <span class="vcsw-message-success"> <?php echo __('All bookmakers are sync successfully!', VCSW_DOMAIN); ?></span>
                            <span class="vcsw-message-error"> <?php echo __('Error! Please try again!', VCSW_DOMAIN); ?></span>
                        </div>
                    </div>
                    <div class="warnings-block">
                        <p> <?php echo __('<strong class="vcsw-warning">Warning: </strong> after pressing this button, the bookmakers will be synchronized with the  API data.', VCSW_DOMAIN); ?>
                        </p>
                        <p><?php echo __('Data to be updated: post title, slug, publication date, bookmaker&#039;s unique identifier in API core.', VCSW_DOMAIN); ?></p>
                        <p><?php echo __('If a bookmaker post with the same name already exists, it will be updated, otherwise a new post will be added to the database.', VCSW_DOMAIN); ?></p>
                    </div>
                </div>
                <div class="col s6">
                    <div class="buttons-row">
                        <div class="vcsw-button-block">
                            <button
                                class="vcsw-btn vcsw-btn-danger vcsw-btn-with-loading vcsw-setting-btn"
                                id="js--delete-bookmakers"
                                data-nonce="<?php echo wp_create_nonce('vcsw_delete_bookmakers'); ?>">
                                <?php echo __('Delete all Bookmakers', VCSW_DOMAIN); ?>
                                <div class="vcsw-lds-ellipsis"><div></div><div></div><div></div><div></div></div>
                            </button>
                            <span class="vcsw-message-success"><?php echo __('All bookmakers are deleted', VCSW_DOMAIN); ?></span>
                            <span class="vcsw-message-error"><?php echo __('Error! Please try again!', VCSW_DOMAIN); ?></span>
                        </div>
                    </div>
                    <div class="warnings-block">
                        <p class="vcsw-warning">
                            <?php echo __('<strong>Warning: </strong> after pressing this button - all bookmakers will be deleted from the database!', VCSW_DOMAIN); ?>
                        </p>
                        <p class="vcsw-warning">
                            <?php echo __('This action cannot be undone!', VCSW_DOMAIN); ?>
                        </p>
                        <p class="vcsw-warning">
                            <?php echo __('If you really want to do this - create a local backup of all existing data in any way convenient for you!', VCSW_DOMAIN); ?>
                        </p>
                        <p><?php echo __('You can try to use built-in', VCSW_DOMAIN); ?>

                            <a href="<?php echo admin_url('import.php') ?>" target="_blank"><?php echo __('WP Import', VCSW_DOMAIN); ?></a>
                            |
                            <a href="<?php echo admin_url('export.php') ?>" target="_blank"><?php echo __('WP Export', VCSW_DOMAIN); ?></a>
                            <?php echo __('tools OR any other plugin of your choice.', VCSW_DOMAIN); ?>
                        </p>
                    </div>
                </div>
            </div>
            <hr/>
		</div>
		<div class="sync-jobs-row">
            <h5><?php echo __('Competitions jobs', VCSW_DOMAIN); ?></h5>
            <p><?php echo __('Here is you can sync or remove all competitions.', VCSW_DOMAIN); ?></p>
            <div class="vcsw-row">
                <div class="col s6">
                    <div class="buttons-row">
                        <div class="vcsw-button-block">
                            <button
                                class="vcsw-btn vcsw-btn-with-loading vcsw-setting-btn"
                                id="js--sync-competitions"
                                data-nonce="<?php echo wp_create_nonce('vcsw_sync_competitions'); ?>">
                                <?php echo __('Sync Competitions', VCSW_DOMAIN); ?>
                                <div class="vcsw-lds-ellipsis"><div></div><div></div><div></div><div></div></div>
                            </button>
                            <span class="vcsw-message-success"> <?php echo __('All competitions are sync successfully!', VCSW_DOMAIN); ?></span>
                            <span class="vcsw-message-error"><?php echo __('Error! Please try again!', VCSW_DOMAIN); ?></span>
                        </div>
                    </div>
                    <div class="warnings-block">
                        <p><?php echo __('<strong class="vcsw-warning">Warning: </strong>after pressing this button, the competitions will be synchronized with the  API data.', VCSW_DOMAIN); ?>
                        </p>
                        <p><?php echo __('Data to be updated: competition&#039;s unique identifier (_id), competition name, country, season, sport type, start and end dates.', VCSW_DOMAIN); ?></p>
                        <p><?php echo __('If a competition with the same competition ID (_id field) already exists, it will be updated, otherwise a new row will be added to the database.', VCSW_DOMAIN); ?></p>
                    </div>
                </div>
                <div class="col s6">
                    <div class="buttons-row">
                        <div class="vcsw-button-block">
                            <button
                                class="vcsw-btn vcsw-btn-danger vcsw-btn-with-loading vcsw-setting-btn"
                                id="js--delete-competitions"
                                data-nonce="<?php echo wp_create_nonce('vcsw_delete_competitions'); ?>">
                                <?php echo __('Delete all Competitions', VCSW_DOMAIN); ?>
                                <div class="vcsw-lds-ellipsis"><div></div><div></div><div></div><div></div></div>
                            </button>
                            <span class="vcsw-message-success"><?php echo __('All competitions are deleted', VCSW_DOMAIN); ?></span>
                            <span class="vcsw-message-error"><?php echo __('Error! Please try again!', VCSW_DOMAIN); ?></span>
                        </div>
                    </div>
                    <div class="warnings-block vcsw-warning">
                        <p><?php echo __('<strong>Warning: </strong> after pressing this button - all competitions will be deleted from the database!', VCSW_DOMAIN); ?></p>
                        <p>
                            <?php echo __('This action cannot be undone!', VCSW_DOMAIN); ?>
                        </p>
                        <p>
                            <?php echo __('If you really want to do this - create a local backup of all existing data in any way convenient for you!', VCSW_DOMAIN); ?>
                        </p>
                    </div>
                </div>
            </div>
            <hr/>
		</div>
		<div class="sync-jobs-row">
            <h5><?php echo __('Markets jobs', VCSW_DOMAIN); ?></h5>
            <p><?php echo __('Here is you can sync or remove all markets.', VCSW_DOMAIN); ?></p>
            <div class="vcsw-row">
                <div class="col s6">
                    <div class="buttons-row">
                        <div class="vcsw-button-block">
                            <button class="vcsw-btn vcsw-btn-with-loading vcsw-setting-btn"
                                    id="js--sync-markets"
                                    data-nonce="<?php echo wp_create_nonce('vcsw_sync_markets'); ?>">
                                <?php echo __('Sync Markets', VCSW_DOMAIN); ?><div class="vcsw-lds-ellipsis"><div></div><div></div><div></div><div></div></div>
                            </button>
                            <span class="vcsw-message-success"><?php echo __('All markets are sync successfully!', VCSW_DOMAIN); ?></span>
                            <span class="vcsw-message-error"><?php echo __('Error! Please try again!', VCSW_DOMAIN); ?></span>
                        </div>
                    </div>
                    <div class="warnings-block">
                        <p><?php echo __('<strong class="vcsw-warning">Warning: </strong> after pressing this button, all the markets will be synchronized with the  API data.', VCSW_DOMAIN); ?></p>
                        <p><?php echo __('Data to be updated: market ID and market name according to the site selected language.', VCSW_DOMAIN); ?></p>
                        <p><?php echo __('If a market with the same market ID already exists, it will be updated, otherwise a new row will be added to the database.', VCSW_DOMAIN); ?></p>
                    </div>
                </div>
                <div class="col s6">
                    <div class="buttons-row">
                        <div class="vcsw-button-block">
                            <button
                                class="vcsw-btn vcsw-btn-danger vcsw-btn-with-loading vcsw-setting-btn"
                                id="js--delete-markets"
                                data-nonce="<?php echo wp_create_nonce('vcsw_delete_markets'); ?>">
                                <?php echo __('Delete all Markets', VCSW_DOMAIN); ?><div class="vcsw-lds-ellipsis"><div></div><div></div><div></div><div></div></div>
                            </button>
                            <span class="vcsw-message-success"><?php echo __('All markets are deleted', VCSW_DOMAIN); ?></span>
                            <span class="vcsw-message-error"><?php echo __('Error! Please try again!', VCSW_DOMAIN); ?></span>
                        </div>
                    </div>
                    <div class="vcsw-warning warnings-block">
                        <p>
                            <?php echo __(' <strong>Warning: </strong> after pressing this button - all markets will be deleted from the database!', VCSW_DOMAIN); ?></p>
                        <p>
                            <?php echo __(' This action cannot be undone!', VCSW_DOMAIN); ?>
                        </p>
                        <p>
                            <?php echo __('If you really want to do this - create a local backup of all existing data in any way convenient for you!', VCSW_DOMAIN); ?>
                        </p>
                    </div>
                </div>
            </div>
		</div>
    </div>
</div>
<?php
