<?php
namespace Ilcfrance\Worldspeak\Trainee\FrontBundle\Controller;

use Ilcfrance\Worldspeak\Shared\ResBundle\Controller\BaseController;
use Ilcfrance\Worldspeak\Trainee\FrontBundle\Model\AvailabilityIntersection;
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
	 * @return Response
	 */
	public function indexAction(Request $request)
	{
		$em = $this->getEntityManager();
		$user = $this->getSecurityTokenStorage()->getToken()->getUser();

		$timeCredits = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCredit')->getAllByTrainee($user);

		$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitle_index'));
		$this->addTwigVar('pagetitle', $this->translate('_pagetitle_index'));
		$this->addTwigVar('timeCredits', $timeCredits);

		return $this->render('IlcfranceWorldspeakTraineeFrontBundle:Default:index.html.twig', $this->getTwigVars());
	}

	/**
	 * Planning Action
	 *
	 * @param integer $year
	 * @param integer $week
	 *
	 * @return Response
	 */
	public function planningAction($year = null, $week = null, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Trainee__default_homepage');
		}

		if (null == $year || $year < 1) {
			$currentYear = intval(date("Y"));
			$currentWeek = intval(date("W"));
			$currentMonth = intval(date("n"));

			if ($currentMonth == 12 && $currentWeek == 1) {
				$currentYear++;
			}

			return $this->redirect($this->generateUrl('Trainee__default_planning', array(
				'year' => $currentYear,
				'week' => $currentWeek
			)));
		} elseif (null == $week) {
			$currentYear = $year;
			$currentWeek = date("W");

			return $this->redirect($this->generateUrl('Trainee__default_planning', array(
				'year' => $currentYear,
				'week' => $currentWeek
			)));
		} elseif ($week < 1) {
			$currentYear = $year;
			$currentWeek = 1;

			return $this->redirect($this->generateUrl('Trainee__default_planning', array(
				'year' => $currentYear,
				'week' => $currentWeek
			)));
		} elseif ($week > $this->getIsoWeeksInYear($year)) {
			$currentYear = $year;
			$currentWeek = $this->getIsoWeeksInYear($year);

			return $this->redirect($this->generateUrl('Trainee__default_planning', array(
				'year' => $currentYear,
				'week' => $currentWeek
			)));
		} else {
			$currentYear = $year;
			$currentWeek = $week;
		}

		$em = $this->getEntityManager();

		$currentUser = $this->getSecurityTokenStorage()->getToken()->getUser();

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

		$closedDays = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:ClosedDay')->getAllBetween($weekDays[0], $weekDays[6], false);

		$availabilities = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TeacherAvailability')->getAllByYearWeek($currentYear, $currentWeek, false);

		$allCours = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Cours')->getAllByYearWeek($currentYear, $currentWeek, false);

		$avIntersectionsBegin = array();

		// $logger = $this->getLogger();

		foreach ($availabilities as $av) {
			$avTeacher = $av->getTeacher();

			$avCourses = array();

			// liste des cours dans la disponibilitée
			foreach ($allCours as $cours) {
				if (null != $cours->getTeacher() && $cours->getTeacher()->getId() == $avTeacher->getId() && (($cours->getDtStart() >= $av->getDtStart() && $cours->getDtStart() < $av->getDtEnd()) || ($cours->getDtEnd() > $av->getDtStart() && $cours->getDtEnd() < $av->getDtEnd()))) {
					$avCourses[] = $cours;
				}
			}

			if (count($avCourses) == 0) {
				$avIntersectionsBegin[] = $av;
			} else {
				$coursIntersection = array();

				foreach ($avCourses as $cours) {
					$intersection = null;
					$intFound = false;
					$i = 0;
					foreach ($coursIntersection as $ci) {
						if ($cours->getDtStart() >= $ci->getDtStart() && $cours->getDtStart() <= $ci->getDtEnd()) {
							$intFound = true;
							$intersection = $ci;
							if ($intersection->getDtEnd() < $cours->getDtEnd()) {
								$intersection->setDtEnd($cours->getDtEnd());
								$coursIntersection[$i] = $intersection;
							}
						}
						$i++;
					}
					if ($intFound == false) {
						$intersection = new AvailabilityIntersection();
						$intersection->setDtStart($cours->getDtStart());
						$intersection->setDtEnd($cours->getDtEnd());
						$coursIntersection[$i] = $intersection;
					}
				}

				$favIntersection = array();

				$favIntersection[] = $av;

				foreach ($coursIntersection as $ci) {
					$i = 0;
					foreach ($favIntersection as $fav) {
						if ($ci->getDtStart() >= $fav->getDtStart() && $ci->getDtStart() <= $fav->getDtEnd()) {
							array_splice($favIntersection, $i, 1);
							if ($fav->getDtStart() < $ci->getDtStart()) {
								$intersection = new AvailabilityIntersection();
								$intersection->setDtStart($fav->getDtStart());
								$intersection->setDtEnd($ci->getDtStart());
								$favIntersection[] = $intersection;
							}
							if ($fav->getDtEnd() > $ci->getDtEnd()) {
								$intersection = new AvailabilityIntersection();
								$intersection->setDtStart($ci->getDtEnd());
								$intersection->setDtEnd($fav->getDtEnd());
								$favIntersection[] = $intersection;
							}
						}
						$i++;
					}
				}

				foreach ($favIntersection as $fav) {
					$avIntersectionsBegin[] = $fav;
				}
			}
		}

		/*
		 * $avIntersections = array();
		 * $i = 0;
		 * foreach ($avIntersectionsBegin as $av) {
		 * $intersection = null;
		 * $intFound = false;
		 * foreach ($avIntersections as $avi) {
		 * if ($av->getDtStart() >= $avi->getDtStart() && $av->getDtStart() <= $avi->getDtEnd()) {
		 * $intFound = true;
		 * $intersection = $avi;
		 * if ($intersection->getDtEnd() < $av->getDtEnd()) {
		 * $intersection->setDtEnd($av->getDtEnd());
		 * $avIntersections[] = $intersection;
		 * }
		 * }
		 * $i++;
		 * }
		 * if ($intFound == false) {
		 * $intersection = new AvailabilityIntersection();
		 * $intersection->setId($i);
		 * $intersection->setDtStart($av->getDtStart());
		 * $intersection->setDtEnd($av->getDtEnd());
		 * $avIntersections[] = $intersection;
		 * }
		 * }
		 * //
		 */

		$myCourses = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Cours')->getAllByYearWeekTrainee($currentYear, $currentWeek, $currentUser, false);

		// $locale = null;
		// if (null != $currentUser->getPreferedLocale()) {
		// $locale = $currentUser->getPreferedLocale()->getPrefix();
		// }
		// $dateFormatter = $this->get('ilc_france_worldspeak_shared_res.date_formatter');
		// $txtFirstDay = $dateFormatter->format($weekDays[0], 'long', 'none', $locale);
		// $txtLastDay = $dateFormatter->format($weekDays[6], 'long', 'none', $locale);

		$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitle_planning_txt', array(
			'%year%' => $currentYear,
			'%week%' => $currentWeek
		)));

		$this->addTwigVar('pagetitle', $this->translate('_pagetitle_planning', array(
			'%year%' => $currentYear,
			'%week%' => $currentWeek
		)));

		$countNextYearWeeks = $this->getIsoWeeksInYear($nextYear);
		$countCurrentYearWeeks = $this->getIsoWeeksInYear($currentYear);
		$countPrevYearWeeks = $this->getIsoWeeksInYear($prevYear);

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

		$this->addTwigVar('weekdays', $weekDays);
		$this->addTwigVar('closedDays', $closedDays);
		$this->addTwigVar('teacherAvailabilities', $avIntersectionsBegin);
		$this->addTwigVar('courses', $myCourses);

		$this->addTwigVar('menu_active', 'calendar');

		return $this->render('IlcfranceWorldspeakTraineeFrontBundle:Default:planning.html.twig', $this->getTwigVars());
	}

	public function notifsAction($page = 1, Request $request)
	{
		$trainee = $this->getSecurityTokenStorage()->getToken()->getUser();

		$em = $this->getEntityManager();
		$query = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TraineeNotif')->getAllOldPendingTxtByTraineeQuery($trainee);

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator->paginate($query, $page, 10);
		$pagination->setPageRange(10);
		$this->addTwigVar('notifs', $pagination);

		$this->addTwigVar('menu_active', 'notif');

		$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitle_notifs_list'));
		$this->addTwigVar('pagetitle', $this->translate('_pagetitle_notifs_list'));

		return $this->render('IlcfranceWorldspeakTraineeFrontBundle:Default:notifs.html.twig', $this->getTwigVars());
	}

	/**
	 * Guide Action
	 *
	 * @return Response
	 */
	public function guideAction(Request $request)
	{
		$this->addTwigVar('menu_active', 'guide');
		$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitle_guide'));
		$this->addTwigVar('pagetitle', $this->translate('_pagetitle_guide'));

		return $this->render('IlcfranceWorldspeakTraineeFrontBundle:Default:guide.html.twig', $this->getTwigVars());
	}

	/**
	 * Guide Action
	 *
	 * @return Response
	 */
	public function guideCEFAction(Request $request)
	{
		$this->addTwigVar('menu_active', 'guideCEF');
		$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitle_guideCEF'));
		$this->addTwigVar('pagetitle', $this->translate('_pagetitle_guideCEF'));

		return $this->render('IlcfranceWorldspeakTraineeFrontBundle:Default:guideCEF.html.twig', $this->getTwigVars());
	}
}
