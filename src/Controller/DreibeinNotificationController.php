<?php
/**
 * Created by PhpStorm.
 * User: thomasvoggenreiter
 * Date: 29.01.18
 * Time: 17:04
 * License: LGPL-3.0+
 */

namespace Dreibein\BeCalendarBundle\Controller;


use Dreibein\BeCalendarBundle\Resources\contao\cron\CronNotification;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DreibeinNotificationController extends Controller
{
    /**
     * @Route("/dreibein/becalendar/notification/send", name="dreibein_becalendar_notification_send")
     *
     * @return Response
     */
    public function sendAction()
    {
        $cron = new CronNotification();
        $cron->sendMails();
        $cron->sendMailsAfterOneWeek();
        $cron->sendSms();

        return new Response();
    }
}