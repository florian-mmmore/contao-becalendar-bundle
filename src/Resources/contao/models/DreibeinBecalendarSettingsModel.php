<?php
/**
 * Created by PhpStorm.
 * User: thomasvoggenreiter
 * Date: 26.01.18
 * Time: 10:43
 * License: LGPL-3.0+
 */

namespace Dreibein\BeCalendarBundle\Resources\contao\models;


use Contao\Model;

/**
 * Class DreibeinBecalendarSettingsModel
 * @package Dreibein\BeCalendarBundle\Resources\contao\models
 *
 * @property int $id
 * @property int $tstamp
 * @property string $user_group
 * @property int $user_group_setevents
 * @property string $email_text
 * @property string $email_reminder
 * @property string $email_aftersale
 * @property string $sms_reminder
 * @property string $mail_template
 */
class DreibeinBecalendarSettingsModel extends Model
{
    /**
     * @var string $strTable
     */
    protected static $strTable = 'tl_dreibein_becalendar_settings';

    /**
     * @param int $id
     *
     * @return static|Model|null
     */
    public function findById($id)
    {
        return parent::findOneBy('id', $id);
    }

    /**
     * @return static|Model|null
     */
    public static function findActive()
    {
        return parent::findOneBy(['id > 0'], []);
    }
}