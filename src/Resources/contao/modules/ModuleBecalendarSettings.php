<?php
/**
 * Created by PhpStorm.
 * User: thomasvoggenreiter
 * Date: 29.01.18
 * Time: 15:56
 * License: LGPL-3.0+
 */

namespace Dreibein\BeCalendarBundle\Resources\contao\modules;


use Contao\BackendModule;
use Contao\BackendUser;
use Contao\Input;
use Contao\System;
use Dreibein\BeCalendarBundle\Resources\contao\models\DreibeinBecalendarModel;
use Dreibein\BeCalendarBundle\Resources\contao\models\DreibeinBecalendarSettingsModel;
use Dreibein\BeCalendarBundle\Resources\contao\models\DreibeinUserModel;

/**
 * Class ModuleBecalendarSettings
 * @package Dreibein\BeCalendarBundle\Resources\contao\modules
 */
class ModuleBecalendarSettings extends BackendModule
{
    /**
     * @var string
     */
    protected $strTemplate = 'be_becalendar_viewer';

    private $requestToken;

    /**
     * no specific generate function
     *
     * @return string
     */
    public function generate()
    {
        return parent::generate();
    }

    /**
     *
     */
    protected function compile()
    {
        /** @var BackendUser $user */
        $user      = BackendUser::getInstance();
        $allowEdit = false;
        /** @var DreibeinBecalendarSettingsModel $setting */
        $setting   = DreibeinBecalendarSettingsModel::findActive();

        if ($user->isAdmin || ($setting && $user->isMemberOf($setting->user_group_setevents))) {
            $allowEdit = true;
        }

        $container = System::getContainer();
        $this->requestToken = $container->get('security.csrf.token_manager')->getToken($container->getParameter('contao.csrf_token_name'))->getValue();
        $dateString = '';

        $mode = Input::get('do');
        if ($mode == 'dreibein_becalendar_viewer_me') {
            $calendar = DreibeinBecalendarModel::findByMonteur($user->id);
            if ($calendar) {
                $dateString .= $this->getDates($calendar);
            }
        } else {
            $calendars = DreibeinBecalendarModel::findAll();

            if ($calendars) {
                foreach ($calendars as $calendar) {
                    $dateString .= $this->getDates($calendar);
                }
            }
        }

        $userData = [];
        if ($setting) {
            $groups = unserialize($setting->user_group);
            $users  = DreibeinUserModel::findAll();

            if ($users) {
                /** @var DreibeinUserModel $user */
                foreach ($users as $user) {
                    $userGroups = unserialize($user->groups);
                    if (is_array($userGroups)) {
                        foreach ($userGroups as $userGroup) {
                            if (in_array($userGroup, $groups)) {
                                $userData[] = [
                                    'id' => $user->id,
                                    'name' => $user->name,
                                    'color' => $user->dreibein_color
                                ];
                                break;
                            }
                        }
                    }
                }
            }
        }

        $this->Template->allowEdit = $allowEdit;
        $this->Template->dates     = $dateString;
        $this->Template->users     = $userData;
        $this->Template->rt        = $this->requestToken;

        $GLOBALS['TL_CSS'][] = '/bundles/dreibeinbecalendar/css/fullcalendar.min.css';
        //$GLOBALS['TL_CSS'][] = '/bundles/dreibeinbecalendar/css/fullcalendar.print.min.css';
        $GLOBALS['TL_CSS'][] = '/bundles/dreibeinbecalendar/css/becalendar.css';

        $GLOBALS['TL_JAVASCRIPT'][] = '/bundles/dreibeinbecalendar/js/moment.min.js';
        $GLOBALS['TL_JAVASCRIPT'][] = '/bundles/dreibeinbecalendar/js/jquery.min.js';
        $GLOBALS['TL_JAVASCRIPT'][] = '/bundles/dreibeinbecalendar/js/jquery-ui.min.js';
        $GLOBALS['TL_JAVASCRIPT'][] = '/bundles/dreibeinbecalendar/js/fullcalendar.min.js';
        $GLOBALS['TL_JAVASCRIPT'][] = '/bundles/dreibeinbecalendar/js/de.js';
    }

    /**
     * @param DreibeinBecalendarModel $calendar
     *
     * @return string
     */
    private function getDates($calendar)
    {
        $color      = '';
        $dateString = '';

        /** @var DreibeinUserModel $user */
        $user = DreibeinUserModel::findById($calendar->monteur);
        if ($user) {
            $color = $user->dreibein_color;
        }

        $dateString .= "{\n";
        $dateString .= "title: '".$calendar->customer_name." ".$calendar->customer_firstname."',\n";
        $dateString .= "backgroundColor: '#".$color."',\n";
        $dateString .= "textColor: '#fff"."',\n";
        $dateString .= "url: 'contao?do=tl_dreibein_becalendar&act=edit&id=".$calendar->id ."&rt=".$this->requestToken."',\n";
        $dateString .= "start: '".date('Y-m-d', $calendar->date_from)."T".date('H:i:s', $calendar->date_from)."',\n";
        $dateString .= "end: '".date('Y-m-d', $calendar->date_to)."T".date('H:i:s', $calendar->date_to)."',\n";
        $dateString .= "},\n";

        return $dateString;
    }
}