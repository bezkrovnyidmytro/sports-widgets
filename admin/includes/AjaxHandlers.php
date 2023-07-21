<?php

namespace DmytroBezkrovnyi\SportsWidgets\Admin\includes;

use DmytroBezkrovnyi\SportsWidgets\Entity\Popup;
use DmytroBezkrovnyi\SportsWidgets\Model\BookmakerModel;
use DmytroBezkrovnyi\SportsWidgets\Model\CompetitionModel;
use DmytroBezkrovnyi\SportsWidgets\Model\FontsModel;
use DmytroBezkrovnyi\SportsWidgets\Model\MarketModel;
use DmytroBezkrovnyi\SportsWidgets\Model\SportTypeModel;
use DmytroBezkrovnyi\SportsWidgets\Model\StylesModel;
use DmytroBezkrovnyi\SportsWidgets\PluginI18N;
use DmytroBezkrovnyi\SportsWidgets\PluginLoader;
use DmytroBezkrovnyi\SportsWidgets\Request\Request;
use DmytroBezkrovnyi\SportsWidgets\Service\BookmakerService;
use DmytroBezkrovnyi\SportsWidgets\Service\CompetitionService;
use DmytroBezkrovnyi\SportsWidgets\Service\MarketService;
use DmytroBezkrovnyi\SportsWidgets\Shortcode\SportsFixturesShortcode;

class AjaxHandlers
{
    protected PluginLoader $loader;
    
    public function __construct(PluginLoader $loader)
    {
        $this->loader = $loader;
        $this->initAjaxWPHooks();
    }
    
    private function initAjaxWPHooks()
    {
        $this->loader->add_action('wp_ajax_vcsw_save_general_options', $this, 'saveGeneralOptions');
        $this->loader->add_action('wp_ajax_vcsw_save_styles_options', $this, 'saveStylesOptions');
        $this->loader->add_action('wp_ajax_vcsw_save_bookmakers_options', $this, 'saveBookmakersOptions');
        $this->loader->add_action('wp_ajax_vcsw_save_competitions_options', $this, 'saveCompetitionsOptions');
        $this->loader->add_action('wp_ajax_vcsw_save_markets_options', $this, 'saveMarketsOptions');
        $this->loader->add_action('wp_ajax_vcsw_sync_bookmakers', $this, 'syncBookmakers');
        $this->loader->add_action('wp_ajax_vcsw_delete_bookmakers', $this, 'deleteBookmakers');
        $this->loader->add_action('wp_ajax_vcsw_sync_competitions', $this, 'syncCompetitions');
        $this->loader->add_action('wp_ajax_vcsw_delete_competitions', $this, 'deleteCompetitions');
        $this->loader->add_action('wp_ajax_vcsw_sync_markets', $this, 'syncMarkets');
        $this->loader->add_action('wp_ajax_vcsw_delete_markets', $this, 'deleteMarkets');
        $this->loader->add_action('wp_ajax_vcsw_save_popup_options', $this, 'savePopupOptions');
        $this->loader->add_action('wp_ajax_vcsw_shortcode_generator_generate_preview',
            $this,
            'shortcodeGeneratorGeneratePreview'
        );
        $this->loader->add_action('wp_ajax_vcsw_save_competition_content', $this, 'saveCompetitionContent');
    }
    
    public function saveGeneralOptions()
    {
        $response = [
            'html'    => '',
            'success' => false,
        ];
        
        if (! wp_verify_nonce($_POST['nonce'], 'vcsw_save_general_options')) {
            wp_send_json($response);
        }
        
        $selectedCoreDataSource = ! empty($_POST['core_data_source'])
            ? filter_var($_POST['core_data_source'], FILTER_SANITIZE_STRING)
            : '';
        
        $selectedLanguage = ! empty($_POST['language_select'])
            ? filter_var($_POST['language_select'], FILTER_SANITIZE_STRING)
            : '';
        
        $selectedSportTypes = ! empty($_POST['sport_types'])
            ? (array) $_POST['sport_types']
            : [];
        
        if (! empty($selectedCoreDataSource)
            && in_array($selectedCoreDataSource, ['staging', 'production'])
        ) {
            delete_option(Request::$optionName);
            $response['success'] = update_option(Request::$optionName, $selectedCoreDataSource);
        }
        
        if (! empty($selectedLanguage)) {
            delete_option(PluginI18N::$optionName);
            $response['success'] = update_option(PluginI18N::$optionName, $selectedLanguage);
        }
        
        if (! empty($selectedSportTypes)) {
            delete_option(SportTypeModel::$optionName);
            $response['success'] = update_option(SportTypeModel::$optionName, $selectedSportTypes);
        }
        
        wp_send_json($response);
    }
    
    public function saveStylesOptions()
    {
        $response = [
            'html'    => '',
            'success' => false,
        ];
        
        if (! wp_verify_nonce($_POST['nonce'], 'vcsw_save_styles_options')
            || empty($_POST['props'])) {
            wp_send_json($response);
        }
        
        $customProps = (array) $_POST['props'];
        
        // $customProps['--vcsw-font-family'] can be empty value == ""
        // if content manager select "Site default" option with empty value
        if (isset($customProps['--vcsw-font-family'])) {
            $customFont = (string) $customProps['--vcsw-font-family'];
            FontsModel::setCustomFont($customFont);
            unset($customProps['--vcsw-font-family']);
        }
        
        $customStyles = implode(';',
            array_map(
                function ($v, $k) { return $k && $v ? $k . ':' . $v : false; },
                $customProps,
                array_keys($customProps)
            )
        );
        
        if (! empty($customStyles)) {
            StylesModel::setCustomStyles($customStyles);
        }
        
        $response['success'] = true;
        wp_send_json($response);
    }
    
    public function saveBookmakersOptions()
    {
        $response = [
            'html'    => '',
            'success' => false,
        ];
        
        if (! wp_verify_nonce($_POST['nonce'], 'vcsw_save_bookmakers_options')) {
            wp_send_json($response);
        }
        
        $selectedBookmakers = ! empty($_POST['selected_bookmakers'])
            ? (array) $_POST['selected_bookmakers']
            : [];
        
        delete_option(BookmakerModel::$optionName);
        $response['success'] = update_option(BookmakerModel::$optionName, $selectedBookmakers);
        
        wp_send_json($response);
    }
    
    public function saveCompetitionsOptions()
    {
        $response = [
            'html'    => '',
            'success' => false,
        ];
        
        if (! wp_verify_nonce($_POST['nonce'], 'vcsw_save_competitions_options')) {
            wp_send_json($response);
        }
        
        $selectedCompetitions = ! empty($_POST['selected_competitions'])
            ? (array) $_POST['selected_competitions']
            : [];
        
        delete_option(CompetitionModel::$optionName);
        $response['success'] = update_option(CompetitionModel::$optionName, $selectedCompetitions);
        
        wp_send_json($response);
    }
    
    public function saveMarketsOptions()
    {
        $response = [
            'html'    => '',
            'success' => false,
        ];
        
        if (! wp_verify_nonce($_POST['nonce'], 'vcsw_save_markets_options')) {
            wp_send_json($response);
        }
        
        $selectedMarkets = ! empty($_POST['selected_markets'])
            ? (array) $_POST['selected_markets']
            : [];
        
        delete_option(MarketModel::$optionName);
        $response['success'] = update_option(MarketModel::$optionName, $selectedMarkets);
        
        wp_send_json($response);
    }
    
    public function syncBookmakers()
    {
        $response = [
            'html'    => '',
            'success' => false,
        ];
        
        if (! wp_verify_nonce($_POST['nonce'], 'vcsw_sync_bookmakers')) {
            wp_send_json($response);
        }
        
        if (BookmakerService::syncDataWithCore()) {
            $response['success'] = true;
        }
        
        wp_send_json($response);
    }
    
    public function deleteBookmakers()
    {
        $response = [
            'html'    => '',
            'success' => false,
        ];
        
        if (! wp_verify_nonce($_POST['nonce'], 'vcsw_delete_bookmakers')) {
            wp_send_json($response);
        }
        
        BookmakerModel::truncateDatabaseTable();
        delete_option(BookmakerModel::$optionName);
        
        $response['success'] = true;
        
        wp_send_json($response);
    }
    
    public function syncCompetitions()
    {
        $response = [
            'html'    => '',
            'success' => false,
        ];
        
        if (! wp_verify_nonce($_POST['nonce'], 'vcsw_sync_competitions')) {
            wp_send_json($response);
        }
        
        if (CompetitionService::syncDataWithCore()) {
            $response['success'] = true;
        }
        
        wp_send_json($response);
    }
    
    public function deleteCompetitions()
    {
        $response = [
            'html'    => '',
            'success' => false,
        ];
        
        if (! wp_verify_nonce($_POST['nonce'], 'vcsw_delete_competitions')) {
            wp_send_json($response);
        }
        
        CompetitionModel::truncateDatabaseTable();
        delete_option(CompetitionModel::$optionName);
        
        $response['success'] = true;
        
        wp_send_json($response);
    }
    
    public function syncMarkets()
    {
        $response = [
            'html'    => '',
            'success' => false,
        ];
        
        if (! wp_verify_nonce($_POST['nonce'], 'vcsw_sync_markets')) {
            wp_send_json($response);
        }
        
        if (MarketService::syncDataWithCore()) {
            $response['success'] = true;
        }
        
        wp_send_json($response);
    }
    
    public function deleteMarkets()
    {
        $response = [
            'html'    => '',
            'success' => false,
        ];
        
        if (! wp_verify_nonce($_POST['nonce'], 'vcsw_delete_markets')) {
            wp_send_json($response);
        }
        
        MarketModel::truncateDatabaseTable();
        delete_option(MarketModel::$optionName);
        
        $response['success'] = true;
        
        wp_send_json($response);
    }
    
    public function savePopupOptions()
    {
        $response = [
            'html'    => '',
            'success' => false,
        ];
        
        if (! wp_verify_nonce($_POST['nonce'], 'vcsw_save_popup_options')) {
            wp_send_json($response);
        }
        
        $popupDisclaimerText = ! empty($_POST['popup_disclaimer_text'])
            ? filter_var($_POST['popup_disclaimer_text'], FILTER_SANITIZE_STRING)
            : '';
        
        if (! empty($popupDisclaimerText)) {
            Popup::setDisclaimerText($popupDisclaimerText);
        }
        
        $popupImages = (! empty($_POST['popup_logo']) && is_array($_POST['popup_logo'])) ? $_POST['popup_logo'] : [];
        
        foreach ($popupImages as &$popupImage) {
            foreach ($popupImage as &$item) {
                $item = filter_var($item, FILTER_SANITIZE_URL);
            }
        }
        unset($popupImage, $item);
        
        if (! empty($popupImages)) {
            Popup::setImages($popupImages);
        }
        
        $defaultMarket = ! empty($_POST['default_market'])
            ? filter_var($_POST['default_market'], FILTER_SANITIZE_STRING)
            : '';
        
        if (! empty($defaultMarket)) {
            Popup::setDefaultMarket($defaultMarket);
        }
        
        $defaultPopupOpenerLabelText = ! empty($_POST['default_popup_opener_label_text'])
            ? filter_var($_POST['default_popup_opener_label_text'], FILTER_SANITIZE_STRING)
            : '';
        
        if (! empty($defaultPopupOpenerLabelText)) {
            Popup::setButtonOpenerText($defaultPopupOpenerLabelText);
        }
        
        $response['success'] = true;
        
        wp_send_json($response);
    }
    
    public function shortcodeGeneratorGeneratePreview()
    {
        $response = [
            'html'    => '',
            'success' => false,
        ];
        
        if (! wp_verify_nonce($_POST['nonce'], 'vcsw_shortcode_generator_generate_preview')) {
            wp_send_json($response);
        }
        
        $shortcode = ! empty($_POST['shortcode'])
            ? htmlspecialchars_decode(stripslashes(filter_var($_POST['shortcode'], FILTER_SANITIZE_STRING)), ENT_QUOTES)
            : '';
        
        if (empty($shortcode)
            || ! shortcode_exists(SportsFixturesShortcode::getShortcodeName())
            || ! has_shortcode($shortcode, SportsFixturesShortcode::getShortcodeName())
        ) {
            wp_send_json($response);
        }
        
        $shortcodeHtml = do_shortcode($shortcode);
        
        if (empty($shortcodeHtml)) {
            wp_send_json($response);
        }
        
        $response = [
            'html'    => trim(preg_replace('/\s+/', ' ', $shortcodeHtml)),
            'success' => true,
        ];
        
        wp_send_json($response);
    }
    
    public function saveCompetitionContent()
    {
        $response = [
            'success' => false,
        ];
        
        $competitionCoreId = ! empty($_POST['competition_id']) ? (string) $_POST['competition_id'] : null;
        
        if (empty($competitionCoreId) || strlen($competitionCoreId) !== CompetitionService::CORE_ID_LENGTH) {
            wp_send_json($response);
        }
        
        $competitionContentHtml = ! empty($_POST['content']) ? esc_sql($_POST['content']) : '';
        
        $response['success'] = CompetitionModel::updateCompetitionContent(
            $competitionCoreId,
            $competitionContentHtml
        );
        
        wp_send_json($response);
    }
}
