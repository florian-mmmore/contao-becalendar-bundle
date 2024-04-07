<?php
/**
 * Created by PhpStorm.
 * User: thomasvoggenreiter
 * Date: 29.01.18
 * Time: 17:02
 * License: LGPL-3.0+
 */

namespace Dreibein\BeCalendarBundle\Resources\contao\cron;


use Contao\Email;
use Dreibein\BeCalendarBundle\Resources\contao\models\DreibeinBecalendarModel;
use Dreibein\BeCalendarBundle\Resources\contao\models\DreibeinBecalendarSettingsModel;

class CronNotification
{
    /**
     * function to send emails once a day
     */
    public function sendMails()
    {
        $time      = time();
        $calendars = DreibeinBecalendarModel::findByDateBetween($time, $time+24*60*60);
        $setting   = DreibeinBecalendarSettingsModel::findActive();
        if ($calendars && $setting) {
            $emailText = $setting->email_reminder;

            /** @var DreibeinBecalendarModel $calendar */
            foreach ($calendars as $calendar) {
                if ($calendar->reminderinfosent < 1 && !$calendar->stop_contact) {
                    $search   = ['{{firstname}}', '{{lastname}}', '{{date}}', '{{time}}'];
                    $replace  = [$calendar->customer_firstname, $calendar->customer_name, date('d.m.Y', $calendar->date_from), date('H:i', $calendar->date_from)];
                    $emailText = str_replace($search, $replace, $emailText);

                    $email = new Email();
                    $email->from     = $GLOBALS['TL_ADMIN_EMAIL'];
                    $email->fromName = 'Kupplung-vor-Ort.com';
                    $email->subject  = 'Ihre Terminerinnerung für den '.date('d.m.Y', $calendar->date_from).' - Wir freuen uns auf Sie!';
                    $email->html     = str_replace('{{content}}', $emailText, $setting->mail_template);
                    $email->sendTo($calendar->customer_email);

                    // set flag, so that this function will not be triggere in future anymore
                    $calendar->reminderinfosent = $time;
                    $calendar->save();
                }
            }
        }
    }

    public function sendMailsAfterOneWeek()
    {
        $time      = time();
        $calendars = DreibeinBecalendarModel::findByDateBetween($time+6*24*60*60, $time+7+24*60*60);
        $setting   = DreibeinBecalendarSettingsModel::findActive();
        if ($calendars && $setting) {
            $emailText = $setting->email_aftersale;

            /** @var DreibeinBecalendarModel $calendar */
            foreach ($calendars as $calendar) {
                if (!$calendar->stop_contact) {
                    $search   = ['{{firstname}}', '{{lastname}}', '{{date}}', '{{time}}'];
                    $replace  = [$calendar->customer_firstname, $calendar->customer_name, date('d.m.Y', $calendar->date_from), date('H:i', $calendar->date_from)];
                    $emailText = str_replace($search, $replace, $emailText);

                    $email = new Email();
                    $email->from     = $GLOBALS['TL_ADMIN_EMAIL'];
                    $email->fromName = 'Kupplung-vor-Ort.com';
                    $email->subject  = 'Vielen Dank für Ihren Besuch am '.date('d.m.Y', $calendar->date_from);
                    $email->html     = str_replace('{{content}}', $emailText, $setting->mail_template);
                    $email->sendTo($calendar->customer_email);
                }
            }
        }
    }

    public function sendSms()
    {
        $time      = time();
        $calendars = DreibeinBecalendarModel::findByDateBetween($time, $time+24*60*60);
        $setting   = DreibeinBecalendarSettingsModel::findActive();
        if ($calendars && $setting) {
            $emailText = $setting->sms_reminder;

            /** @var DreibeinBecalendarModel $calendar */
            foreach ($calendars as $calendar) {
                if ($calendar->firstsmssent < 1 && !$calendar->stop_contact && $calendar->customer_mobilephone) {
                    $search    = ['{{firstname}}', '{{lastname}}', '{{date}}', '{{time}}'];
                    $replace   = [$calendar->customer_firstname, $calendar->customer_name, date('d.m.Y', $calendar->date_from), date('H:i', $calendar->date_from)];
                    $emailText = str_replace($search, $replace, $emailText);

                    // TODO: store sms gateway data in settings

                    file_get_contents('http://gateway.werbe-sms.eu/smsc.php?user=kupplungvorort&pass=5a42d67a2797f96e&from=499933902023&to='.$calendar->customer_mobilephone.'&text='.urlencode(utf8_decode($emailText)));

                    // TODO: WTF, maybe use something more symfony-like
                    $httpStatus = $http_response_header[0];

                    $calendar->firstsmssent = $time;
                    $calendar->smsstatus    = $httpStatus;
                    $calendar->save();
                }
            }
        }
    }
}