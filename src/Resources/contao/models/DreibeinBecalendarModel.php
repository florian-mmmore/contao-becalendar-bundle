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
 * Class DreibeinBecalendarModel
 * @package Dreibein\BeCalendarBundle\Resources\contao\models
 *
 * @property int $tstamp
 * @property string $customer_name
 * @property string $customer_firstname
 * @property string $customer_email
 * @property string $customer_mobilephone
 * @property int $date_from
 * @property int $date_to
 * @property string $montage
 * @property string $notes
 * @property boolean $stop_contact
 * @property int $monteur
 * @property string $firstinfosent
 * @property string $reminderinfosent
 * @property string $firstsmssent
 * @property string $smsstatus
 *
 */
class DreibeinBecalendarModel extends Model
{
    /**
     * @var string $strTable
     */
    protected static $strTable = 'tl_dreibein_becalendar';

    /**
     * @param int $id
     *
     * @return static|Model
     */
    public static function findById($id)
    {
        return parent::findOneBy('id', $id);
    }

    /**
     * @param int $monteur
     * @return static|Model
     */
    public static function findByMonteur($monteur)
    {
        return parent::findOneBy('monteur', $monteur);
    }

    /**
     * @param int $fromDate
     * @param int $toDate
     * @return DreibeinBecalendarModel|Model\Collection|null|static
     */
    public static function findByDateBetween($fromDate, $toDate)
    {
        return parent::findBy(['date_from >= '.$fromDate, 'date_from <= ',$toDate], []);
    }
}