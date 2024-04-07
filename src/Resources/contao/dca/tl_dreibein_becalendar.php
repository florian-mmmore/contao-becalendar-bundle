<?php
/**
 * Created by PhpStorm.
 * User: thomasvoggenreiter
 * Date: 26.01.18
 * Time: 09:18
 * License: LGPL-3.0+
 */

namespace Dreibein\BeCalendarBundle\Resources\contao\dca;


use Contao\DataContainer;
use Contao\Email;
use Contao\Input;
use Contao\UserModel;
use Dreibein\BeCalendarBundle\Resources\contao\models\DreibeinBecalendarModel;
use Dreibein\BeCalendarBundle\Resources\contao\models\DreibeinBecalendarSettingsModel;
use Dreibein\BeCalendarBundle\Resources\contao\models\DreibeinUserModel;

$table = 'tl_dreibein_becalendar';

$GLOBALS['TL_DCA'][$table] = [
    'config' => [
        'dataContainer'     => 'Table',
        'enableVersioning'  => true,
        'onload_callback'   => [
            [tl_dreibein_becalendar::class, 'onLoad']
        ],
        'onsubmit_callback' => [
            [tl_dreibein_becalendar::class, 'onSubmit']
        ],
        'sql' => [
            'keys' => [
                'id' => 'primary'
            ]
        ]
    ],

    'list' => [
        'sorting' => [
            'mode'        => 2,
            'flag'        => 9,
            'fields'      => ['date_from'],
            'panelLayout' => 'filter,search;sort,limit'
        ],
        'label' => [
            'fields'         => ['customer_name', 'customer_firstname', 'date_from', 'date_to', 'monteur'],
            'showColumns'    => true,
            'format'         => '%s',
            'label_callback' => [tl_dreibein_becalendar::class, 'addLabelView']
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
        ],
    ],
    'palettes' => [
        'default' => '{title_legend},customer_name,customer_firstname,customer_email,customer_mobilephone,montage,notes,stop_contact;{time_legend},date_from,date_to,monteur;{message_legend},firstinfosent,reminderinfosent,firstsmssent,smsstatus;'
    ],

    'fields' => [
        'id' => [
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ],
        'tstamp' => [
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ],

        'customer_name' => [
            'label'     => &$GLOBALS['TL_LANG'][$table]['customer_name'],
            'exclude'   => true,
            'inputType' => 'text',
            'search'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'eval'      => ['mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'],
            'sql'       => "varchar(255) NOT NULL default ''"
        ],
        'customer_firstname' => [
            'label'     => &$GLOBALS['TL_LANG'][$table]['customer_firstname'],
            'exclude'   => true,
            'inputType' => 'text',
            'search'    => true,
            'eval'      => ['mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'],
            'sql'       => "varchar(255) NOT NULL default ''"
        ],
        'date_from' => [
            'label'     => &$GLOBALS['TL_LANG'][$table]['date_from'],
            'exclude'   => true,
            'inputType' => 'text',
            'default'   => time(),
            'sorting'   => true,
            'flag'      => 6,
            'eval'      => ['mandatory'=>true, 'maxlength'=>255, 'rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'],
            'sql'       => "int(10) unsigned NOT NULL default '0'"
        ],
        'date_to' => [
            'label'     => &$GLOBALS['TL_LANG'][$table]['date_to'],
            'exclude'   => true,
            'inputType' => 'text',
            'default'   => time(),
            'sorting'   => true,
            'flag'      => 6,
            'eval'      => ['mandatory'=>true, 'maxlength'=>255, 'rgxp'=>'datim', 'datepicker'=>true, 'tl_class'=>'w50 wizard'],
            'sql'       => "int(10) unsigned NOT NULL default '0'"
        ],
        'customer_email' => [
            'label'     => &$GLOBALS['TL_LANG'][$table]['customer_email'],
            'exclude'   => true,
            'inputType' => 'text',
            'search'    => true,
            'eval'      => ['mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50', 'rgxp'=>'email'],
            'sql'       => "varchar(255) NOT NULL default ''"
        ],
        'customer_mobilephone' => [
            'label'     => &$GLOBALS['TL_LANG'][$table]['customer_mobilephone'],
            'exclude'   => true,
            'inputType' => 'text',
            'search'    => true,
            'eval'      => ['mandatory'=>true, 'maxlength'=>255, 'tl_class'=>'w50'],
            'sql'       => "varchar(255) NOT NULL default ''"
        ],
        'montage' => [
            'label'     => &$GLOBALS['TL_LANG'][$table]['montage'],
            'exclude'   => true,
            'inputType' => 'textarea',
            'search'    => true,
            'eval'      => ['mandatory'=>true, 'tl_class'=>'clr'],
            'sql'       => "TEXT NULL"
        ],
        'notes' => [
            'label'     => &$GLOBALS['TL_LANG'][$table]['notes'],
            'exclude'   => true,
            'inputType' => 'textarea',
            'search'    => true,
            'eval'      => ['mandatory'=>false, 'rte'=>'tinyMCE', 'tl_class'=>'m12'],
            'sql'       => "TEXT NULL"
        ],
        'stop_contact' => [
            'label'     => &$GLOBALS['TL_LANG'][$table]['stop_contact'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'filter'    => true,
            'eval'      => ['mandatory'=>false],
            'sql'       => "varchar(1) NOT NULL default ''"
        ],
        'monteur' => [
            'label'            => &$GLOBALS['TL_LANG'][$table]['monteur'],
            'exclude'          => true,
            'inputType'        => 'select',
            'filter'           => true,
            'options_callback' => [tl_dreibein_becalendar::class, 'getUsers'],
            'eval'             => ['mandatory'=>false, 'tl_class'=>'clr'],
            'sql'              => "int(10) unsigned NOT NULL default '0'"
        ],
        'firstinfosent' => [
            'label'     => &$GLOBALS['TL_LANG'][$table]['firstinfosent'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => ['maxlength'=>255, 'tl_class'=>'w50', 'rgxp'=>'datim', 'disabled'=>false, 'readonly'=>false],
            'sql'       => "varchar(255) NOT NULL default ''"
        ],
        'reminderinfosent' => [
            'label'     => &$GLOBALS['TL_LANG'][$table]['reminderinfosent'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => ['maxlength'=>255, 'tl_class'=>'w50', 'rgxp'=>'datim', 'disabled'=>true, 'readonly'=>true],
            'sql'       => "varchar(255) NOT NULL default ''"
        ],
        'firstsmssent' => [
            'label'     => &$GLOBALS['TL_LANG'][$table]['firstsmssent'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => ['maxlength'=>255, 'tl_class'=>'w50', 'rgxp'=>'datim', 'disabled'=>true, 'readonly'=>true],
            'sql'       => "varchar(255) NOT NULL default ''"
        ],
        'smsstatus' => [
            'label'     => &$GLOBALS['TL_LANG'][$table]['smsstatus'],
            'exclude'   => true,
            'filter'    => true,
            'inputType' => 'text',
            'eval'      => ['maxlength'=>255, 'tl_class'=>'w50', 'disabled'=>true, 'readonly'=>true],
            'sql'       => "varchar(255) NOT NULL default ''"
        ]
    ]
];

/**
 * Class tl_dreibein_becalendar
 * @package Dreibein\BeCalendarBundle\Resources\contao\dca
 */
class tl_dreibein_becalendar
{
    /**
     * set default monteur and default time if load by javascript
     *
     * @param DataContainer $dc
     */
    public function onLoad(DataContainer $dc)
    {
        $id = Input::get('id');
        if ($id) {
            $defaultMonteur = Input::get('event_monteur');
            $defaultTime    = Input::get('event_start');
            if ($defaultMonteur && $defaultTime) {
                $calendar = DreibeinBecalendarModel::findById($id);
                $calendar->monteur   = $defaultMonteur;
                $calendar->date_from = $defaultTime;
                $calendar->date_to   = $defaultTime + 36000;
                $calendar->save();
            }
        }
    }

    /**
     * send first info to customer
     *
     * @param DataContainer $dc
     */
    public function onSubmit(DataContainer $dc)
    {
        /** @var DreibeinBecalendarModel $calendar */
        $calendar = DreibeinBecalendarModel::findById($dc->id);

        // if the first info was not sent yet and contact is not stopped
        if ($calendar->firstinfosent === 0 && !$calendar->stop_contact) {

            /** @var DreibeinBecalendarSettingsModel $setting */
            $setting = DreibeinBecalendarSettingsModel::findActive();

            if ($setting) {
                $mailText = $setting->email_text;
                $search   = ['{{firstname}}', '{{lastname}}', '{{date}}', '{{time}}'];
                $replace  = [$calendar->customer_firstname, $calendar->customer_name, date('d.m.Y', $calendar->date_from), date("H:i", $calendar->date_from)];
                $mailText = str_replace($search, $replace, $mailText);

                $attachment =
                    "BEGIN:VCALENDAR\r\n".
                    "CALSCALE:GREGORIAN\r\n".
                    "VERSION:2.0\r\n".
                    "PRODID:QBAR\r\n".
                    "BEGIN:VEVENT\r\n".
                    "TRANSP:TRANSPARENT\r\n".
                    "DTEND;TZID=Europe/Berlin:".date('Ymd', $calendar->date_to)."T".date('His', $calendar->date_to)."\r\n".
                    "DTSTAMP:".date('Ymd')."T".date('His')."Z\r\n".
                    "DTSTART;TZID=Europe/Berlin:".date('Ymd', $calendar->date_from)."T".date('His', $calendar->date_from)."\r\n".
                    "SUMMARY: Montage Termin bei Kupplung-vor-Ort.com\r\n".
                    "DESCRIPTION: Heute ist es soweit, Ihre Montage findet statt\r\n".
                    "URL;VALUE=URI:http://www.kupplung-vor-ort.com\r\n".
                    "LOCATION:Hitzinger Wiese 1, 94431 Pilsting\r\n".
                    "BEGIN:VALARM\nTRIGGER:-PT15M\nACTION:DISPLAY\nEND:VALARM\n".
                    "PRIORITY:3\r\n".
                    "END:VEVENT\r\n".
                    "END:VCALENDAR";

                $email           = new Email();
                $email->from     = $GLOBALS['TL_ADMIN_EMAIL'];
                $email->fromName = "Kupplung-vor-Ort.com";
                $email->subject  = "Ihre Terminbestätigung für den ".date("d.m.Y", $calendar->date_from)." - Wir freuen uns auf Sie!";
                $email->html     = str_replace("{{content}}", $mailText, $setting->mail_template);
                $email->attachFileFromString($attachment, 'Termin.ics' );
                $email->sendTo($calendar->customer_email);

                $calendar->firstinfosent = time();
                $calendar->save();
            }
        }
    }

    /**
     * get users that have the user-group from the active becalendar-settings
     *
     * @return array
     */
    public function getUsers()
    {
        $data = [];
        $settings = DreibeinBecalendarSettingsModel::findActive();

        if ($settings) {
            $groups = unserialize($settings->user_group);
            $users  = DreibeinUserModel::findAll();

            if ($users) {
                /** @var DreibeinUserModel $user */
                foreach ($users as $user) {
                    $userGroups = unserialize($user->groups);
                    if (is_array($userGroups)) {
                        foreach ($userGroups as $userGroup) {
                            if (in_array($userGroup, $groups)) {
                                $data[$user->id] = $user->name;
                                break;
                            }
                        }
                    }
                }
            }
        }

        return $data;
    }

    /**
     * @param array $row
     * @param string $label
     * @param DataContainer $dc
     * @param array $args
     *
     * @return array
     */
    public function addLabelView($row, $label, DataContainer $dc, $args)
    {
        // date_form
        if ($args[2] > 0) {
            $args[2] = date('d.m.Y H:i', $args[2]);
        }

        // date_to
        if ($args[3] > 0) {
            $args[3] = date('d.m.Y H:i', $args[3]);
        }
        if ($args[4] > 0) {
            $user = UserModel::findById($args[4]);
            if ($user) {
                $args[4] = $user->name;
            }
        }
        return $args;
    }
}