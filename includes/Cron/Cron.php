<?php

namespace DmytroBezkrovnyi\SportsWidgets\Cron;

use DmytroBezkrovnyi\SportsWidgets\PluginLoader;
use DmytroBezkrovnyi\SportsWidgets\Service\BookmakerService;
use DmytroBezkrovnyi\SportsWidgets\Service\CompetitionService;

class Cron
{
    protected PluginLoader $loader;
    
    /**
     * Allowed arguments for constructor.
     *
     * @see __construct
     * @var array
     */
    protected static array $default_args = [
        'id'            => '',
        'auto_activate' => true,
        'events'        => [
            'hook_name' => [
                'callback'      => [__CLASS__, 'default_callback'],
                'args'          => [],
                'interval_name' => '',
                'interval_sec'  => 0,
                'interval_desc' => '',
                'start_time'    => 0,
            ],
        ],
    ];
    
    /**
     * Collects every cron options called with this class.
     * It may be useful for any external cases.
     *
     * @var array
     */
    public static array $instances = [];
    
    /**
     * Must be 0 on production.
     * For debugging go to: http://site.com/wp-cron.php
     */
    public const DEBUG = false;
    
    /**
     * ID cron args. Internal - not uses for cron.
     *
     * @var string
     */
    protected string $id;
    
    /**
     * Current instance args.
     *
     * @var array
     */
    protected array $args;
    
    /**
     * Constructor.
     *
     * @type mixed $args What parameters should be passed to the cron task function.
     */
    public function __construct(PluginLoader $loader)
    {
        $this->loader = $loader;
        
        $args = [
            'id'            => 'vcsw_cron_jobs',
            'auto_activate' => true,
            'events'        => [
                'sync_bookmakers'   => [
                    'callback'      => [(new BookmakerService()), 'syncDataWithCore'],
                    'interval_name' => 'monthly',
                ],
                'sync_competitions' => [
                    'callback'      => [(new CompetitionService()), 'syncDataWithCore'],
                    'interval_name' => 'monthly',
                ]
            ],
        ];
        
        // if passed simple array of events
        if (empty($args['events'])) {
            $args = ['events' => $args];
        }
        
        // complete passed args using defaults
        $args += [
            'id'            => implode('|', array_keys($args['events'])),
            'auto_activate' => true,
        ];
        
        // complete each passed 'event' using defaults
        foreach ($args['events'] as $index => $_event) {
            $args['events'][$index] += self::$default_args['events']['hook_name'];
        }
        
        $this->args = $args;
        
        self::$instances[$this->args['id']] = $this;
        
        // after self::$opts
        $this->loader->add_filter('cron_schedules', $this, '_add_monthly_interval');
        $this->loader->add_filter('cron_schedules', $this, '_add_intervals');
        
        // after 'cron_schedules'
        // activate only in: admin | WP_CLI | DOING_CRON
        if ($args['auto_activate'] && (is_admin() || defined('WP_CLI') || defined('DOING_CRON'))) {
            $this->activate();
        }
        
        // add cron hooks
        foreach ($args['events'] as $hook_name => $task_data) {
            $this->loader->add_action($hook_name,
                $task_data['callback'][0],
                $task_data['callback'][1],
                10,
                count($task_data['args'])
            );
        }
        
        self::debug_info();
    }
    
    /**
     * Removes cron task.
     * Should be called on plugin deactivation.
     *
     */
    public function deactivate()
    {
        
        foreach ($this->args['events'] as $hook => $data) {
            wp_clear_scheduled_hook($hook, $data['args']);
        }
    }
    
    /**
     * Add cron task.
     * Should be called on plugin activation.
     * Can be called somewhere else, for example, when updating the settings.
     *
     */
    public function activate()
    {
        
        foreach ($this->args['events'] as $hook => $data) {
            
            if (wp_next_scheduled($hook, $data['args'])) {
                continue;
            }
            
            if ($data['interval_name']) {
                wp_schedule_event($data['start_time'] ? : time(), $data['interval_name'], $hook, $data['args']);
            } // single event
            elseif (! $data['start_time']) {
                trigger_error(__CLASS__ . ' ERROR: Start time not specified for single event');
            }
            elseif ($data['start_time'] > time()) {
                wp_schedule_single_event($data['start_time'], $hook, $data['args']);
            }
        }
    }
    
    public function _add_monthly_interval($schedules)
    {
        if (empty($schedules['monthly'])) {
            $schedules['monthly'] = [
                'interval' => 2635200,
                'display'  => __('Once a month')
            ];
        }
        
        return $schedules;
    }
    
    public function _add_intervals($schedules)
    {
        
        foreach ($this->args['events'] as $data) {
            
            $interval_name = $data['interval_name'];
            
            if (
                // it is a single event.
                ! $interval_name
                // already exists
                || isset($schedules[$interval_name])
                // internal WP intervals
                || in_array($interval_name, ['hourly', 'twicedaily', 'daily'])
            ) {
                continue;
            }
            
            // allow set only `interval_name` parameter like: 10_min, 2_hours, 5_days, 2_month
            if (! $data['interval_sec']) {
                
                if (preg_match('/^(\d+)[ _-](min(?:ute)?|hour|day|month)s?/', $interval_name, $mm)) {
                    $min   = $minute = 60;
                    $hour  = $min * 60;
                    $day   = $hour * 24;
                    $month = $day * 30;
                    
                    $data['interval_sec'] = $mm[1] * ${$mm[2]};
                }
                else {
                    /** @noinspection ForgottenDebugOutputInspection */
                    wp_die('ERROR: Cron required `interval_sec` parameter not set. ' . print_r(debug_backtrace(), 1));
                }
            }
            
            $schedules[$interval_name] = [
                'interval' => $data['interval_sec'],
                'display'  => $data['interval_desc'] ? : $data['interval_name'],
            ];
        }
        
        return $schedules;
    }
    
    public static function default_callback()
    {
        
        echo "ERROR: One of Cron callback function not set.\n\nCron::\$instance = " .
             print_r(self::$instances, 1) . "\n\n\n\n_get_cron_array() =" .
             print_r(_get_cron_array(), 1);
    }
    
    private static function debug_info() : void
    {
        
        if (! (self::DEBUG && defined('DOING_CRON'))) {
            return;
        }
        
        add_action('wp_loaded', function () {
            
            echo sprintf(
                "Current time: %s\n\n\nExisting Intervals:\n%s\n\n\n%s",
                time(),
                print_r(wp_get_schedules(), 1),
                print_r(_get_cron_array(), 1)
            );
        });
    }
    
}
