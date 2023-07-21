<?php

namespace DmytroBezkrovnyi\SportsWidgets\Model;

class TranslationModel
{
    public static function getAllTranslations() : array
    {
        return [
            'bet_now'            => __('Bet now', VCSW_DOMAIN),
            'best_odds'          => __('Best odds', VCSW_DOMAIN),
            'other_odds'         => __('Other odds', VCSW_DOMAIN),
            'all_bookmakers'     => __('All bookmakers', VCSW_DOMAIN),
            'odds_not_available' => __('Odds aren\'t available', VCSW_DOMAIN),
            'events_not_found'   => __('Events aren\'t found', VCSW_DOMAIN),
            'date'               => __('Date', VCSW_DOMAIN),
            'competition'        => __('Competition', VCSW_DOMAIN),
            'prev'               => __('Prev', VCSW_DOMAIN),
            'next'               => __('Next', VCSW_DOMAIN),
            'current'            => __('Current', VCSW_DOMAIN),
            'ft'                 => __('FT', VCSW_DOMAIN),
            'load_more'          => __('Load more', VCSW_DOMAIN),
            'close'              => __('Close', VCSW_DOMAIN),
            'decimal'            => __('Decimal', VCSW_DOMAIN),
            'fractional'         => __('Fractional', VCSW_DOMAIN),
            'american'           => __('American', VCSW_DOMAIN),
            'statuses'           => [
                'not_started'         => __('Not started', VCSW_DOMAIN),
                'in_progress'         => __('In progress', VCSW_DOMAIN),
                'finished'            => __('Finished', VCSW_DOMAIN),
                'abandoned'           => __('Abandoned', VCSW_DOMAIN),
                'postponed'           => __('Postponed', VCSW_DOMAIN),
                'awaiting_extra_time' => __('Awaiting extra time', VCSW_DOMAIN),
                'awaiting_penalties'  => __('Awaiting penalties', VCSW_DOMAIN),
                'extra_time_halftime' => __('Extra time halftime', VCSW_DOMAIN),
                'finished_extra_time' => __('Finished extra time', VCSW_DOMAIN),
                'finished_penalties'  => __('Finished penalties', VCSW_DOMAIN),
                '1st_half'            => __('1st half', VCSW_DOMAIN),
                '1st_extra'           => __('1st extra', VCSW_DOMAIN),
                'halftime'            => __('Halftime', VCSW_DOMAIN),
                'interrupted'         => __('Interrupted', VCSW_DOMAIN),
                'overtime'            => __('Overtime', VCSW_DOMAIN),
                'penalties'           => __('Penalties', VCSW_DOMAIN),
                '2nd_half'            => __('2nd half', VCSW_DOMAIN),
                '2nd_extra'           => __('2nd extra', VCSW_DOMAIN),
                'start_delayed'       => __('Start delayed', VCSW_DOMAIN),
            ],
            'team'               => __('Team', VCSW_DOMAIN),
            'pl'                 => __('Pl', VCSW_DOMAIN),
            'w'                  => __('W', VCSW_DOMAIN),
            'd'                  => __('D', VCSW_DOMAIN),
            'l'                  => __('L', VCSW_DOMAIN),
            'f'                  => __('F', VCSW_DOMAIN),
            'a'                  => __('A', VCSW_DOMAIN),
            'gd'                 => __('GD', VCSW_DOMAIN),
            'pts'                => __('Pts', VCSW_DOMAIN),
            'last_5'             => __('Last 5', VCSW_DOMAIN),
            'last_updated'       => __('Last updated', VCSW_DOMAIN),
        ];
    }
}
