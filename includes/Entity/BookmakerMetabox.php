<?php

namespace DmytroBezkrovnyi\SportsWidgets\Entity;

use DmytroBezkrovnyi\SportsWidgets\CPT\BookmakerCPT;
use DmytroBezkrovnyi\SportsWidgets\PluginLoader;
use WP_Post;

class BookmakerMetabox
{
    protected PluginLoader $loader;
    
    protected string $title = '';
    
    protected string $slug = '';
    
    protected int $post_id = 0;
    
    protected string $context = '';
    
    protected string $priority = '';
    
    public function __construct(PluginLoader $loader)
    {
        $this->loader = $loader;
        
        $this->title    = __('Bookmaker settings', VCSW_DOMAIN);
        $this->slug     = 'bookmaker_settings';
        $this->context  = 'advanced';
        $this->priority = 'high';
        
        $this->initHooks();
    }
    
    private function initHooks()
    {
        $this->loader->add_action('add_meta_boxes_' . BookmakerCPT::$postType, $this, 'addMetaBoxes');
        $this->loader->add_action('save_post_' . BookmakerCPT::$postType, $this, 'saveMetaBoxes');
    }
    
    private function getMetaboxFields(int $pid = 0) : array
    {
        return [
            'affiliate_link' => [
                'name'          => 'affiliate_link',
                'type'          => 'text',
                'title'         => __('Affiliate link', VCSW_DOMAIN),
                'desc'          => __('This field is used to redirect the user to the required resource (also known as <strong>"go"</strong> link) from the frontend part of the site. You can find this link in odds and odds popup.',
                    VCSW_DOMAIN
                ),
                'value'         => get_metadata('post', $pid, 'affiliate_link', true) ? : '',
                'readonly'      => false,
                'filter'        => FILTER_SANITIZE_URL,
                'do_not_update' => false,
            ],
            'bg_color'       => [
                'name'          => 'bg_color',
                'type'          => 'color',
                'title'         => __('Background color', VCSW_DOMAIN),
                'desc'          => __('Select the color of the background image for the bookmaker logo.',
                    VCSW_DOMAIN
                ),
                'value'         => get_metadata('post', $pid, 'bg_color', true) ? : '',
                'readonly'      => false,
                'filter'        => FILTER_SANITIZE_STRING,
                'do_not_update' => false,
            ],
            'bookmaker_id'   => [
                'name'          => 'bookmaker_id',
                'type'          => 'text',
                'title'         => __('Bookmaker  API ID', VCSW_DOMAIN),
                'desc'          => __('This field is used as the bookmaker\'s internal unique identifier to communicate with the  API. <strong style="color:red;">Please never try to change this field!</strong>',
                    VCSW_DOMAIN
                ),
                'value'         => get_metadata('post', $pid, 'bookmaker_id', true) ? : '',
                'readonly'      => true,
                'filter'        => FILTER_SANITIZE_STRING,
                'do_not_update' => true,
            ],
        ];
    }
    
    public function addMetaBoxes()
    {
        add_meta_box(
            $this->slug,
            $this->title,
            [$this, 'render'],
            BookmakerCPT::$postType,
            $this->context,
            $this->priority
        );
    }
    
    public function render(WP_Post $post)
    {
        wp_nonce_field('metabox_' . $this->slug, 'metabox_' . $this->slug . '_nonce');
        
        $fields = $this->getMetaboxFields($post->ID);
        
        foreach ($fields as $field) {
            switch ($field['type']) {
                case 'text':
                    echo $this->renderTextField($field);
                    break;
                case 'color':
                    echo $this->renderColorPickerField($field);
                    break;
            }
        }
    }
    
    private function renderTextField(array $args = [])
    {
        extract($args);
        ob_start();
        ?>
        <div class="vcsw-metabox-wrap">
            <div class="input-field-row">
                <div class="row">
                    <div class="col s12">
                        <p class="vcsw-description"><strong><?php echo $title; ?></strong></p>
                        <label
                            class="vcsw-metabox-label"
                            for="<?php echo $name; ?>">
                            <?php echo $desc ? : $name; ?>
                        </label>
                        <input
                            <?php echo $readonly ? 'readonly' : ''; ?>
                            type="text"
                            name="<?php echo $name; ?>"
                            id="<?php echo $name; ?>"
                            value="<?php echo $value; ?>"
                        />
                    </div>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    private function renderColorPickerField(array $args = [])
    {
        extract($args);
        ob_start();
        ?>
        <div class="vcsw-metabox-wrap color">
            <div class="row">
                <div class="col s12">
                    <p class="vcsw-description"><strong><?php echo $title; ?></strong></p>
                    <label
                        class="vcsw-metabox-label"
                        for="<?php echo $name; ?>">
                        <?php echo $desc ? : $name; ?>
                    </label>
                    <div class="colorpicker-field">
                        <input
                            <?php echo $readonly ? 'readonly' : ''; ?>
                            type="color"
                            name="<?php echo $name; ?>"
                            id="<?php echo $name; ?>"
                            value="<?php echo $value; ?>"
                        />
                    </div>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    public function saveMetaBoxes(int $postId = 0)
    {
        if (! $postId) {
            return $postId;
        }
        
        /*
         * If this is an autosave, our form has not been submitted,
         * so we don't want to do anything.
         */
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $postId;
        }
        
        // Check if our nonce is empty.
        if (empty($_POST['metabox_' . $this->slug . '_nonce'])) {
            return $postId;
        }
        
        $nonce = (string) $_POST['metabox_' . $this->slug . '_nonce'];
        
        // Verify that the nonce is valid.
        if (! wp_verify_nonce($nonce, 'metabox_' . $this->slug)) {
            return $postId;
        }
        
        $metaFields = $this->getMetaboxFields($postId);
        
        foreach ($metaFields as $fieldName => $fieldData) {
            // if field has "do_not_update" flag - we skip it
            // for ex.: bookmaker_id
            if ($fieldData['do_not_update'] || ! array_key_exists($fieldName, $_POST)) {
                continue;
            }
            
            $fieldValue = filter_input(INPUT_POST, $fieldName, $fieldData['filter']) ? : '';
            
            if (empty($fieldName) || ! isset($fieldValue)) {
                continue;
            }
            
            update_post_meta($postId, $fieldName, $fieldValue);
        }
        
        return true;
    }
}
