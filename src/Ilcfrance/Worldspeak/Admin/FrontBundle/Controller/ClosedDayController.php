<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Controller;

use DateTime;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\ClosedDay;
use Ilcfrance\Worldspeak\Shared\ResBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * ClosedDay Controller
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class ClosedDayController extends BaseController
{

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->addTwigVar('menu_active', 'closedDay');
    }

    /**
     * Planning Action
     *
     * @param Request $request
     * @param integer $year
     * @param integer $month
     *
     * @return RedirectResponse|Response
     */
    public function listAction(Request $request, $year = null, $month = null)
    {
        $urlFrom = $this->getReferer($request);
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('Admin__default_homepage');
        }

        if (null == $year || $year < 1) {
            $currentYear = intval(date("Y"));
            $currentMonth = intval(date("n"));

            return $this->redirect($this->generateUrl('Admin__closedDay_list', array(
                'year' => $currentYear,
                'month' => $currentMonth
            )));
        } elseif (null == $month) {
            $currentYear = $year;
            $currentMonth = intval(date("n"));

            return $this->redirect($this->generateUrl('Admin__closedDay_list', array(
                'year' => $currentYear,
                'month' => $currentMonth
            )));
        } elseif ($month < 1 || $month > 12) {
            $currentYear = $year;
            $currentMonth = 1;

            return $this->redirect($this->generateUrl('Admin__closedDay_list', array(
                'year' => $currentYear,
                'month' => $currentMonth
            )));
        } else {
            $currentYear = $year;
            $currentMonth = $month;
        }

        $prevYear = $currentYear;
        $prevMonth = $currentMonth - 1;
        if ($prevMonth <= 0) {
            $prevMonth = 12;
            $prevYear--;
        }

        $nextYear = $currentYear;
        $nextMonth = $currentMonth + 1;
        if ($nextMonth >= 12) {
            $nextMonth = 1;
            $nextYear++;
        }

        $countDaysInCurrentMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);

        $monthDays = $this->daysInMonth($currentMonth, $currentYear);

        $currentFWeekNum = (int) date('W', $monthDays[0]->getTimestamp());
        $currentFYearNum = (int) date('Y', $monthDays[0]->getTimestamp());

        $currentMWeekNum = (int) date('W', $monthDays[15]->getTimestamp());
        // $currentMYearNum = (int) date('Y', $monthDays[15]->getTimestamp());

        $currentLWeekNum = (int) date('W', $monthDays[$countDaysInCurrentMonth - 1]->getTimestamp());
        $currentLYearNum = (int) date('Y', $monthDays[$countDaysInCurrentMonth - 1]->getTimestamp());

        if ($currentFWeekNum > $currentMWeekNum) {
            $currentFYearNum--;
        }
        if ($currentLWeekNum < $currentMWeekNum) {
            $currentLYearNum++;
        }

        $firstWeek = $this->daysInWeek($currentFWeekNum, $currentFYearNum);
        $lastWeek = $this->daysInWeek($currentLWeekNum, $currentLYearNum);

        $em = $this->getEntityManager();

        $closedDays = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:ClosedDay')->getAllBetween($firstWeek[0], $lastWeek[6], false);

        $this->addTwigVar('currentYear', $currentYear);
        $this->addTwigVar('currentMonth', $currentMonth);
        $this->addTwigVar('prevMonth', $prevMonth);
        $this->addTwigVar('prevYear', $prevYear);
        $this->addTwigVar('nextMonth', $nextMonth);
        $this->addTwigVar('nextYear', $nextYear);
        $this->addTwigVar('monthDays', $monthDays);
        $this->addTwigVar('closedDays', $closedDays);

        $txtmonth = $this->translate('_month' . $currentMonth);

        $this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__closedDay_list', array(
            '%year%' => $currentYear,
            '%month%' => $txtmonth
        )));

        $this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__closedDay_list_txt', array(
            '%year%' => $currentYear,
            '%month%' => $txtmonth
        )));

        return $this->render('IlcfranceWorldspeakAdminFrontBundle:ClosedDay:list.html.twig', $this->getTwigVars());
    }

    /**
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function ajaxAddAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $dtClosedDay = $request->request->get('closedDay');
            list ($yy, $mm, $dd) = explode("-", $dtClosedDay);
            if (is_numeric($yy) && is_numeric($mm) && is_numeric($dd) && checkdate($mm, $dd, $yy)) {
                $em = $this->getEntityManager();
                $day = new DateTime();
                $day->setDate($yy, $mm, $dd);
                $day->setTime(0, 0, 0);

                $dateFormatter = $this->get('ilc_france_worldspeak_shared_res.date_formatter');
                $dayTrans = $dateFormatter->format($day, 'full');

                $closedDay = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:ClosedDay')->findOneBy(array(
                    'day' => $day
                ));

                if (null == $closedDay) {
                    $closedDay = new ClosedDay();
                    $closedDay->setDay($day);
                    $em->persist($closedDay);
                    $em->flush();

                    $response = new Response();
                    $msg = $this->translate('ClosedDay.addSuccess', array(
                        '%closedDay%' => $dayTrans
                    ));

                    $response->setContent($msg);

                    return $response;
                } else {
                    $msg = $this->translate('ClosedDay.addFailureUnique', array(
                        '%closedDay%' => $dayTrans
                    ));

                    $response = new Response();
                    $response->setStatusCode(409);
                    $response->setContent($msg);

                    return $response;
                }
            } else {
                $msg = $this->translate('ClosedDay.addFailureInvalid');
                $response->setStatusCode(415);
                $response->setContent($msg);

                return $response;
            }
        } else {
            return $this->redirect($this->generateUrl('Admin__closedDay_list'));
        }
    }

    /**
     *
     * @param Request $request
     *
     * @return Response|RedirectResponse
     */
    public function ajaxDeleteAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $dtClosedDay = $request->request->get('closedDay');
            list ($yy, $mm, $dd) = explode("-", $dtClosedDay);
            if (is_numeric($yy) && is_numeric($mm) && is_numeric($dd) && checkdate($mm, $dd, $yy)) {
                $em = $this->getEntityManager();
                $day = new DateTime();
                $day->setDate($yy, $mm, $dd);
                $day->setTime(0, 0, 0);
                $dateFormatter = $this->get('ilc_france_worldspeak_shared_res.date_formatter');
                $dayTrans = $dateFormatter->format($day, 'full');
                $closedDay = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:ClosedDay')->findOneBy(array(
                    'day' => $day
                ));

                if (null != $closedDay) {
                    $em->remove($closedDay);
                    $em->flush();
                    $response = new Response();
                    $msg = $this->translate('ClosedDay.deleteSuccess', array(
                        '%closedDay%' => $dayTrans
                    ));

                    $response->setContent($msg);

                    return $response;
                } else {
                    $msg = $this->translate('ClosedDay.deleteFailureNotFound', array(
                        '%closedDay%' => $dayTrans
                    ));

                    $response = new Response();
                    $response->setStatusCode(404);
                    $response->setContent($msg);

                    return $response;
                }
            } else {
                $msg = $this->translate('ClosedDay.deleteFailureInvalid');
                $response->setStatusCode(415);
                $response->setContent($msg);

                return $response;
            }
        } else {
            return $this->redirect($this->generateUrl('Admin__closedDay_list'));
        }
    }
}
