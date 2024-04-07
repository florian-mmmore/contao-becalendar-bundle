<?php
/**
 * Created by PhpStorm.
 * User: thomasvoggenreiter
 * Date: 26.01.18
 * Time: 09:18
 * License: LGPL-3.0+
 */

namespace Dreibein\BeCalendarBundle\Resources\contao\dca;


use Contao\Backend;
use Contao\DataContainer;
use Contao\Input;
use Dreibein\BeCalendarBundle\Resources\contao\models\DreibeinBecalendarSettingsModel;

$table = 'tl_dreibein_becalendar_settings';

$GLOBALS['TL_DCA'][$table] = [
    'config' => [
        'dataContainer'    => 'Table',
        'enableVersioning' => true,
        'onload_callback'  => [
            [tl_dreibein_becalendar_settings::class, 'checkConfig'],
        ],
        'sql' => [
            'keys' => [
                'id' => 'primary'
            ]
        ]
    ],

    // List
    'list' => [
        'sorting' => [
            'mode'   => 1,
            'fields' => ['id'],
            'flag'   => 1
        ],
        'label' => [
            'fields' => ['id']
        ],
        'global_operations' => [
            'all' => [
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="e"'
            ]
        ],
        'operations' => [
            'edit' => [
                'label' => &$GLOBALS['TL_LANG'][$table]['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.svg'
            ],
            'copy' => [
                'label' => &$GLOBALS['TL_LANG'][$table]['copy'],
                'href'  => 'act=copy',
                'icon'  => 'copy.svg'
            ],
            'delete' => [
                'label'      => &$GLOBALS['TL_LANG'][$table]['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.svg',
                'attributes' => 'onclick="if(!confirm(\''.$GLOBALS['TL_LANG']['MSC']['deleteConfirm'].'\'))return false;Backend.getScrollOffset()"'
            ],
            'show' => [
                'label' => &$GLOBALS['TL_LANG'][$table]['show'],
                'href'  => 'act=show',
                'icon'  => 'show.svg'
            ]
        ]
    ],

    // Palettes
    'palettes' => [
        'default' => '{title_legend},user_group,user_group_setevents;{email_legend},email_text,email_reminder,email_aftersale,mail_template;{sms_legend},sms_reminder;'
    ],

    // Fields
    'fields' => [
        'id' => [
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ],
        'user_group' => [
            'label'      => &$GLOBALS['TL_LANG'][$table]['user_group'],
            'exclude'    => true,
            'inputType'  => 'checkboxWizard',
            'foreignKey' => 'tl_user_group.name',
            'relation'   => ['type'=>'hasMany', 'load'=>'lazy'],
            'eval'       => ['mandatory'=>true, 'multiple'=>true],
            'sql'        => "BLOB NULL"
        ],
        'user_group_setevents' => array
        (
            'label'      => &$GLOBALS['TL_LANG'][$table]['user_group'],
            'exclude'    => true,
            'inputType'  => 'radio',
            'foreignKey' => 'tl_user_group.name',
            'relation'   => ['type'=>'hasMany', 'load'=>'lazy'],
            'eval'       => ['mandatory'=>true, 'multiple'=>false],
            'sql'        => "int(10) NOT NULL default '0'"
        ),
        'email_text' => [
            'label'     => &$GLOBALS['TL_LANG'][$table]['email_text'],
            'exclude'   => true,
            'inputType' => 'textarea',
            'eval'      => ['mandatory'=>true, 'rte'=>'tinyMCE'],
            'sql'       => "TEXT NULL"
        ],
        'email_reminder' => [
            'label'     => &$GLOBALS['TL_LANG'][$table]['email_reminder'],
            'exclude'   => true,
            'inputType' => 'textarea',
            'eval'      => ['mandatory'=>true, 'rte'=>'tinyMCE'],
            'sql'       => "TEXT NULL"
        ],
        'email_aftersale' => [
            'label'     => &$GLOBALS['TL_LANG'][$table]['email_aftersale'],
            'exclude'   => true,
            'inputType' => 'textarea',
            'eval'      => ['mandatory'=>true, 'rte'=>'tinyMCE'],
            'sql'       => "TEXT NULL"
        ],
        'sms_reminder' => [
            'label'     => &$GLOBALS['TL_LANG'][$table]['sms_reminder'],
            'exclude'   => true,
            'inputType' => 'textarea',
            'eval'      => ['mandatory'=>true],
            'sql'       => "TEXT NULL"
        ],
        'mail_template' => [
            'label'     => &$GLOBALS['TL_LANG'][$table]['mail_template'],
            'exclude'   => true,
            'inputType' => 'textarea',
            'eval'      => ['mandatory'=>true, 'allowHtml'=>true, 'preserveTags'=>true],
            'sql'       => "TEXT NULL"
        ]
    ]
];

class tl_dreibein_becalendar_settings extends Backend
{
    /**
     * check if there is already a calendar-configuration else create one
     *
     * @param DataContainer $dc
     */
    public function checkConfig(DataContainer $dc)
    {
        if (Input::get('key')) {
            return;
        }

        /** @var DreibeinBecalendarSettingsModel $setting */
        $setting = DreibeinBecalendarSettingsModel::findActive();

        if (!$setting && !Input::get('act')) {
            self::redirect(self::addToUrl('act=create'));
        }

        if (!Input::get('id') && !Input::get('act')) {
            self::redirect(self::addToUrl('act=edit&id='.$setting->id));
        }
    }
}