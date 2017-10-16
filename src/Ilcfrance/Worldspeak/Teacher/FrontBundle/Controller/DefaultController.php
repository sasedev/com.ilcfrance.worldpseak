<?php
namespace Ilcfrance\Worldspeak\Teacher\FrontBundle\Controller;

use Ilcfrance\Worldspeak\Shared\ResBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Default Controller
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class DefaultController extends BaseController
{

    /**
     * Class Constructor
     */
    public function __construct()
    {
        $this->addTwigVar('menu_active', 'home');
    }

    /**
     * Default home page
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $currentYear = intval(date("Y"));
        $currentWeek = intval(date("W"));
        $currentMonth = intval(date("n"));

        if ($currentMonth == 12 && $currentWeek == 1) {
            $currentYear++;
        }
        return $this->redirect($this->generateUrl('Teacher__default_planning', array(
            'year' => $currentYear,
            'week' => $currentWeek
        )));
    }

    /**
     *
     * @param Request $request
     * @param integer $year
     * @param integer $week
     * @return RedirectResponse|Response
     */
    public function planningAction(Request $request, $year, $week)
    {
        $urlFrom = $this->getReferer($request);
        if (null == $urlFrom || trim($urlFrom) == '') {
            $urlFrom = $this->generateUrl('Teacher__default_homepage');
        }
        if (null == $year || $year < 1) {
            $currentYear = intval(date("Y"));
            $currentWeek = intval(date("W"));
            $currentMonth = intval(date("n"));

            if ($currentMonth == 12 && $currentWeek == 1) {
                $currentYear++;
            }

            return $this->redirect($this->generateUrl('Teacher__default_planning', array(
                'year' => $currentYear,
                'week' => $currentWeek
            )));
        } elseif (null == $week) {
            $currentYear = $year;
            $currentWeek = intval(date("W"));

            return $this->redirect($this->generateUrl('Teacher__default_planning', array(
                'year' => $currentYear,
                'week' => $currentWeek
            )));
        } elseif ($week < 1) {
            $currentYear = $year;
            $currentWeek = 1;

            return $this->redirect($this->generateUrl('Teacher__default_planning', array(
                'year' => $currentYear,
                'week' => $currentWeek
            )));
        } elseif ($week > $this->getIsoWeeksInYear($year)) {
            $currentYear = $year;
            $currentWeek = $this->getIsoWeeksInYear($year);

            return $this->redirect($this->generateUrl('Teacher__default_planning', array(
                'year' => $currentYear,
                'week' => $currentWeek
            )));
        } else {
            $currentYear = $year;
            $currentWeek = $week;
        }
        try {

            $prevWeek = $currentWeek - 1;
            $prevYear = $currentYear;
            $nextWeek = $currentWeek + 1;
            $nextYear = $currentYear;
            if ($prevWeek < 1) {
                $prevYear--;
                $prevWeek = $this->getIsoWeeksInYear($prevYear);
            }
            if ($nextWeek > $this->getIsoWeeksInYear($nextYear)) {
                $nextYear++;
                $nextWeek = 1;
            }

            $weekDays = $this->daysInWeek($currentWeek, $currentYear);

            $countNextYearWeeks = $this->getIsoWeeksInYear($nextYear);
            $countCurrentYearWeeks = $this->getIsoWeeksInYear($currentYear);
            $countPrevYearWeeks = $this->getIsoWeeksInYear($prevYear);

            $currentUser = $this->getSecurityTokenStorage()
                ->getToken()
                ->getUser();

            $em = $this->getEntityManager();
            $closedDays = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:ClosedDay')->getAllBetween($weekDays[0], $weekDays[6], false);

            $teacherAvailabilities = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TeacherAvailability')->getAllByYearWeekTeacher($currentYear, $currentWeek, $currentUser, false);

            $courses = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Cours')->getAllByYearWeekTeacher($currentYear, $currentWeek, $currentUser, false);

            $this->addTwigVar('currentUser', $currentUser);

            $this->addTwigVar('nextYear', $nextYear);
            $this->addTwigVar('nextWeek', $nextWeek);
            $this->addTwigVar('currentYear', $currentYear);
            $this->addTwigVar('currentWeek', $currentWeek);
            $this->addTwigVar('prevYear', $prevYear);
            $this->addTwigVar('prevWeek', $prevWeek);

            $this->addTwigVar('countPrevYearWeeks', $countPrevYearWeeks);
            $this->addTwigVar('countCurrentYearWeeks', $countCurrentYearWeeks);
            $this->addTwigVar('countNextYearWeeks', $countNextYearWeeks);

            $this->addTwigVar('weekDays', $weekDays);
            $this->addTwigVar('closedDays', $closedDays);
            $this->addTwigVar('teacherAvailabilities', $teacherAvailabilities);
            $this->addTwigVar('courses', $courses);

            $this->addTwigVar('pagetitle_txt', $this->translate('_pagetitle_planning_txt', array(
                '%year%' => $currentYear,
                '%week%' => $currentWeek
            )));

            $this->addTwigVar('pagetitle', $this->translate('_pagetitle_planning', array(
                '%year%' => $currentYear,
                '%week%' => $currentWeek
            )));

            return $this->render('IlcfranceWorldspeakTeacherFrontBundle:Default:index.html.twig', $this->getTwigVars());
        } catch (\Exception $e) {
            $logger = $this->getLogger();
            $logger->addError($e->getLine() . ' ' . $e->getMessage());
            $this->addFlash('error', $this->translate('Default.planningFailure'));
        }

        return $this->redirect($urlFrom);
    }

    /**
     *
     * @param Request $request
     * @param integer $page
     *
     * @return Response
     */
    public function notifsAction(Request $request, $page = 1)
    {
        $teacher = $this->getSecurityTokenStorage()
            ->getToken()
            ->getUser();

        $em = $this->getEntityManager();
        $query = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TeacherNotif')->getAllOldPendingTxtByTeacherQuery($teacher);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $page, 10);
        $pagination->setPageRange(10);
        $this->addTwigVar('notifs', $pagination);

        $this->addTwigVar('menu_active', 'notif');

        $this->addTwigVar('pagetitle_txt', $this->translate('_pagetitle_notifs_list'));
        $this->addTwigVar('pagetitle', $this->translate('_pagetitle_notifs_list'));

        return $this->render('IlcfranceWorldspeakTeacherFrontBundle:Default:notifs.html.twig', $this->getTwigVars());
    }
}
