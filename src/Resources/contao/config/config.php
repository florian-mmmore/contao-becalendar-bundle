<?php
/**
 * Created by PhpStorm.
 * User: thomasvoggenreiter
 * Date: 26.01.18
 * Time: 13:49
 * License: LGPL-3.0+
 */

namespace Dreibein\BeCalendarBundle\Resources\contao\config;


use Dreibein\BeCalendarBundle\Resources\contao\models\DreibeinBecalendarModel;
use Dreibein\BeCalendarBundle\Resources\contao\models\DreibeinBecalendarSettingsModel;
use Dreibein\BeCalendarBundle\Resources\contao\modules\ModuleBecalendarSettings;

$GLOBALS['BE_MOD']['dreibein_becalendar'] = [
    'dreibein_becalendar_settings' => [
        'tables' => ['tl_dreibein_becalendar_settings'],
        'icon'   => 'bundles/dreibeinbecalendar/icons/settings-gears.png'
    ],
    'dreibein_becalendar' => [
        'tables' => ['tl_dreibein_becalendar'],
        'icon'   => 'bundles/dreibeinbecalendar/icons/cal.png'
    ],
    'dreibein_becalendar_viewer' => [
        'callback' => ModuleBecalendarSettings::class,
        'icon'   => 'bundles/dreibeinbecalendar/icons/calendar.png'
    ],
    'dreibein_becalendar_viewer_me' => [
        'callback' => ModuleBecalendarSettings::class,
        'icon'   => 'bundles/dreibeinbecalendar/icons/calendar.png'
    ]
];

$GLOBALS['TL_MODELS']['tl_dreibein_becalendar']          = DreibeinBecalendarModel::class;
$GLOBALS['TL_MODELS']['tl_dreibein_becalendar_settings'] = DreibeinBecalendarSettingsModel::class;