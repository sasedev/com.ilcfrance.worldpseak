<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Controller;

use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\ExcelImportTForm;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\AdminNotif;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Company;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Cours;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Teacher;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TimeCredit;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Trainee;
use Ilcfrance\Worldspeak\Shared\ResBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Default Controller
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class DefaultController extends BaseController
{

	protected $extotligne = 0;

	protected $infos = "";

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
		$currentYear = intval(date("Y"));
		$currentWeek = intval(date("W"));
		$currentMonth = intval(date("n"));

		if ($currentMonth == 12 && $currentWeek == 1) {
			$currentYear++;
		}

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

		$em = $this->getEntityManager();
		$closedDays = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:ClosedDay')->getAllBetween($weekDays[0], $weekDays[6], false);

		$teacherAvailabilities = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TeacherAvailability')->getAllByYearWeek($currentYear, $currentWeek, false);

		$courses = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Cours')->getAllByYearWeek($currentYear, $currentWeek, false);

		$excelImportForm = $this->createForm(ExcelImportTForm::class);

		$this->addTwigVar('excelImportForm', $excelImportForm->createView());

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

		$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitle_homepage'));
		$this->addTwigVar('pagetitle', $this->translate('_pagetitle_homepage'));

		return $this->render('IlcfranceWorldspeakAdminFrontBundle:Default:index.html.twig', $this->getTwigVars());
	}

	public function excelAction(Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Admin__default_homepage');
		}

		if ($this->endswith($urlFrom, $this->generateUrl('Admin__default_excel'))) {
			$urlFrom = $this->generateUrl('Admin__default_homepage');
		}
		$logger = $this->getLogger();
		$em = $this->getEntityManager();
		try {
			$excelImportForm = $this->createForm(ExcelImportTForm::class);

			$data = $request->request->all();
			if (isset($data['ExcelImportForm'])) {
				$excelImportForm->handleRequest($request);
				if ($excelImportForm->isValid()) {
					ini_set('memory_limit', '4096M');
					ini_set('max_execution_time', '0');
					$extension = $excelImportForm['file']->getData()->guessExtension();
					if ($extension == 'zip') {
						$extension = 'xlsx';
					}
					$filename = uniqid() . '.' . $extension;
					$excelImportForm['file']->getData()->move($this->getParameter('adapter_files'), $filename);
					$fullfilename = $this->getParameter('adapter_files');
					$fullfilename .= '/' . $filename;

					$excelObj = $this->get('phpexcel')->createPHPExcelObject($fullfilename);

					// $this->importCompanies($excelObj);
					// $this->importTeacher($excelObj);
					$this->importTrainees($excelObj);
					$this->importCourses($excelObj);

					$em->flush();

					/*
					 * $deletedTrainees = 0;
					 * $deletedTimeCredits = 0;
					 * $timeCredits = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCredit')->getAll();
					 * foreach ($timeCredits as $timeCredit) {
					 * if($timeCredit->getStatus() == TimeCredit::STATUS_FULL_FINISHED) {
					 * $trainee = $timeCredit->getTrainee();
					 * $trainee->removeCredit($timeCredit);
					 * $em->remove($timeCredit);
					 * $deletedTimeCredits++;
					 * if(count($trainee->getCredits()) == 0) {
					 * $em->remove($trainee);
					 * $deletedTrainees++;
					 * }
					 * }
					 * }
					 * $em->flush(); //
					 */

					$this->infos .= $this->extotligne . ' lignes lues au total<br>';
					// $this->infos .= $deletedTimeCredits. ' Crédit horaire Terminés supprimés<br>';
					// $this->infos .= $deletedTrainees. ' Stagiaires supprimés pour Crédit terminés<br>';

					$this->addFlash('info', $this->infos);

					return $this->redirect($urlFrom);
				}
			}

			$currentYear = intval(date("Y"));
			$currentWeek = intval(date("W"));
			$currentMonth = intval(date("n"));

			if ($currentMonth == 12 && $currentWeek == 1) {
				$currentYear++;
			}

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

			$closedDays = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:ClosedDay')->getAllBetween($weekDays[0], $weekDays[6], false);

			$teacherAvailabilities = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TeacherAvailability')->getAllByYearWeek($currentYear, $currentWeek, false);

			$courses = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Cours')->getAllByYearWeek($currentYear, $currentWeek, false);

			$this->addTwigVar('excelImportForm', $excelImportForm->createView());

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

			$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitle_homepage'));
			$this->addTwigVar('pagetitle', $this->translate('_pagetitle_homepage'));

			return $this->render('IlcfranceWorldspeakAdminFrontBundle:Default:index.html.twig', $this->getTwigVars());
		} catch (\Exception $e) {

			$logger->addError($e->getLine() . ' ' . $e->getMessage());
		} // */

		return $this->redirect($urlFrom);
	}

	public function detectBuggyAction(Request $request)
	{
		ini_set('memory_limit', '4096M');
		ini_set('max_execution_time', '0');

		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Admin__default_homepage');
		}
		$em = $this->getEntityManager();

		/*
		 * $teachers = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Teacher')->findAll();
		 * foreach ($teachers as $teacher) {
		 * $em->flush($teacher);
		 * }
		 * $trainees = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Trainee')->findAll();
		 * foreach ($trainees as $trainee) {
		 * $em->refresh($trainee);
		 * } //
		 */

		$courses = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Cours')->findAll();
		foreach ($courses as $cours) {
			$em->refresh($cours);
		} // */

		$timeCredits = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCredit')->findAll();
		foreach ($timeCredits as $timeCredit) {
			$em->refresh($timeCredit);
		} // */

		$em->flush();

		return $this->redirect($urlFrom);
	}

	public function resetTeachersAction(Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Admin__default_homepage');
		}

		$em = $this->getEntityManager();

		$teachers = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Teacher')->findAll();
		foreach ($teachers as $teacher) {
			$teacher->setRegisterMail(Teacher::REGISTERMAIL_NOTSENT);
			$teacher->setLogins(0);
			$teacher->setLastLogin(null);
			$teacher->setLastActivity(null);
			$teacher->setRecoveryCode(null);
			$teacher->setRecoveryExpiration(null);
			$teacher->setSalt(md5(uniqid(null, true)));
			$teacher->setClearPassword(Teacher::generateRandomChar(8, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'));

			$em->persist($teacher);
		}

		$em->flush();
		return $this->redirect($urlFrom);
	}

	public function mailTeachersAction(Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Admin__default_homepage');
		}

		$em = $this->getEntityManager();

		$teachers = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Teacher')->findAll();

		$mailSent = 0;
		$teachersCount = count($teachers);

		foreach ($teachers as $teacher) {

			if ($teacher->getLockout() != Teacher::LOCKOUT_UNLOCKED || $teacher->getRegisterMail() != Teacher::REGISTERMAIL_NOTSENT) {
				$teachersCount--;
			} else {
				try {
					if (null != $teacher->getEmail() && trim($teacher->getEmail()) != '') {
						if (null == $teacher->getClearPassword() || trim($teacher->getClearPassword()) == '') {
							$teacher->setSalt(md5(uniqid(null, true)));
							$teacher->setClearPassword(Teacher::generateRandomChar(8, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'));
						}

						$teacher->setRegisterMail(Teacher::REGISTERMAIL_SENT);
						$em->persist($teacher);

						$locale = null;
						if (null != $teacher->getPreferedLocale()) {
							$locale = $teacher->getPreferedLocale()->getPrefix();
						}
						$mvars = array();
						$mvars['user'] = $teacher;
						$mvars['userPreferedLocale'] = $locale;
						$from = $this->getParameter('mail_from');
						$fromName = $this->getParameter('mail_from_name');
						$subject = $this->translate('_mail.registerAdmin_teacher_subject', array(), null, $locale);

						$message = \Swift_Message::newInstance()->setFrom($from, $fromName)->setTo($teacher->getEmail(), $teacher->getFullname())->setSubject($subject)->setBody($this->renderView('IlcfranceWorldspeakSharedResBundle:Mail:teacher.registration.html.twig', $mvars), 'text/html');

						$this->sendmail($message);
						$em->flush();
						$mailSent++;
					}
				} catch (\Exception $e) {
					$logger = $this->getLogger();
					$logger->addError($e->getLine() . ' ' . $e->getMessage());
				}
			}
		}

		$this->addFlash('info', $mailSent . ' / ' . $teachersCount . ' Mail envoyés aux formateurs avec leurs paramètres');

		$em->flush();
		return $this->redirect($urlFrom);
	}

	public function resetTraineesAction(Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Admin__default_homepage');
		}

		$em = $this->getEntityManager();

		$trainees = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Trainee')->findAll();
		foreach ($trainees as $trainee) {
			$trainee->setRegisterMail(Trainee::REGISTERMAIL_NOTSENT);
			$trainee->setLogins(0);
			$trainee->setLastLogin(null);
			$trainee->setLastActivity(null);
			$trainee->setRecoveryCode(null);
			$trainee->setRecoveryExpiration(null);
			$trainee->setSalt(md5(uniqid(null, true)));
			$trainee->setClearPassword(Trainee::generateRandomChar(8, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'));

			$em->persist($trainee);
		}

		$em->flush();
		return $this->redirect($urlFrom);
	}

	public function mailTraineesAction(Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Admin__default_homepage');
		}

		$em = $this->getEntityManager();

		$trainees = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Trainee')->findAll();

		$mailSent = 0;
		$traineesCount = count($trainees);

		foreach ($trainees as $trainee) {

			if ($trainee->getLockout() != Trainee::LOCKOUT_UNLOCKED || $trainee->getRegisterMail() != Trainee::REGISTERMAIL_NOTSENT) {
				$traineesCount--;
			} else {
				try {
					if (null != $trainee->getEmail() && trim($trainee->getEmail()) != '') {
						if (null == $trainee->getClearPassword() || trim($trainee->getClearPassword()) == '') {
							$trainee->setSalt(md5(uniqid(null, true)));
							$trainee->setClearPassword(Trainee::generateRandomChar(8, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'));
						}

						$trainee->setRegisterMail(Trainee::REGISTERMAIL_SENT);
						$em->persist($trainee);

						$locale = null;
						if (null != $trainee->getPreferedLocale()) {
							$locale = $trainee->getPreferedLocale()->getPrefix();
						}
						$mvars = array();
						$mvars['user'] = $trainee;
						$mvars['userPreferedLocale'] = $locale;
						$from = $this->getParameter('mail_from');
						$fromName = $this->getParameter('mail_from_name');
						$subject = $this->translate('_mail.registerAdmin_trainee_subject', array(), null, $locale);

						$message = \Swift_Message::newInstance()->setFrom($from, $fromName)->setTo($trainee->getEmail(), $trainee->getFullname())->setSubject($subject)->setBody($this->renderView('IlcfranceWorldspeakSharedResBundle:Mail:trainee.registration.html.twig', $mvars), 'text/html');

						$this->sendmail($message);
						$em->flush();
						$mailSent++;
					}
				} catch (\Exception $e) {
					$logger = $this->getLogger();
					$logger->addError($e->getLine() . ' ' . $e->getMessage());
				}
			}
		}

		$this->addFlash('info', $mailSent . ' / ' . $traineesCount . ' Mail envoyés aux stagiaires avec leurs paramètres');

		$em->flush();
		return $this->redirect($urlFrom);
	}

	public function bugsAction(Request $request)
	{
		ini_set('memory_limit', '4096M');
		ini_set('max_execution_time', '0');

		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Admin__default_homepage');
		}

		$buggyTimeCredits = array();
		$buggyTrainees = array();

		$em = $this->getEntityManager();

		$timeCredits = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCredit')->findAll();

		foreach ($timeCredits as $timeCredit) {
			$courses = $timeCredit->getCourses();
			$done = 0;
			$lost = 0;
			$planned = 0;
			foreach ($courses as $cours) {
				if ($cours->getStatus() == Cours::STATUS_PLANNED_PENDING || $cours->getStatus() == Cours::STATUS_PLANNED) {

					$planned += $cours->getDuration() / 60;
				} elseif ($cours->getStatus() == Cours::STATUS_DONE) {
					$done += $cours->getDuration() / 60;
				} elseif ($cours->getStatus() == Cours::STATUS_ABSENT) {
					$lost += $cours->getDuration() / 60;
				}
			}
			if ($timeCredit->getReservedHours() != $planned || $timeCredit->getDoneHours() != $done || $timeCredit->getLostHours() != $lost) {
				$buggyTimeCredits[] = $timeCredit;
			}
		}

		$trainees = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Trainee')->findAll();

		foreach ($trainees as $trainee) {
			if (count($trainee->getCredits()) == 0) {
				$buggyTrainees[] = $trainee;
			}
		}

		$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitle_bugs_list'));
		$this->addTwigVar('pagetitle', $this->translate('_pagetitle_bugs_list'));

		$this->addTwigVar('timeCredits', $buggyTimeCredits);
		$this->addTwigVar('trainees', $buggyTrainees);

		return $this->render('IlcfranceWorldspeakAdminFrontBundle:Default:bugs.html.twig', $this->getTwigVars());
	}

	public function notifsAction($page = 1, Request $request)
	{
		$em = $this->getEntityManager();
		// if ($this->isGranted('ROLE_SUPER_SUPER_ADMIN')) {
		// $query = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:AdminNotif')->getAllQuery();
		// } else {
		$query = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:AdminNotif')->getAllOldPendingQuery();
		// }

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator->paginate($query, $page, 10);
		$pagination->setPageRange(10);
		$this->addTwigVar('notifs', $pagination);

		$this->addTwigVar('menu_active', 'notif');

		$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitle_notifs_list'));
		$this->addTwigVar('pagetitle', $this->translate('_pagetitle_notifs_list'));

		return $this->render('IlcfranceWorldspeakAdminFrontBundle:Default:notifs.html.twig', $this->getTwigVars());
	}

	public function planningAction($year, $week, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Admin__default_homepage');
		}
		if (null == $year || $year < 1) {
			$currentYear = intval(date("Y"));
			$currentWeek = intval(date("W"));
			$currentMonth = intval(date("n"));

			if ($currentMonth == 12 && $currentWeek == 1) {
				$currentYear++;
			}

			return $this->redirect($this->generateUrl('Admin__default_planning', array(
				'year' => $currentYear,
				'week' => $currentWeek
			)));
		} elseif (null == $week) {
			$currentYear = $year;
			$currentWeek = date("W");

			return $this->redirect($this->generateUrl('Admin__default_planning', array(
				'year' => $currentYear,
				'week' => $currentWeek
			)));
		} elseif ($week < 1) {
			$currentYear = $year;
			$currentWeek = 1;

			return $this->redirect($this->generateUrl('Admin__default_planning', array(
				'year' => $currentYear,
				'week' => $currentWeek
			)));
		} elseif ($week > $this->getIsoWeeksInYear($year)) {
			$currentYear = $year;
			$currentWeek = $this->getIsoWeeksInYear($year);

			return $this->redirect($this->generateUrl('Admin__default_planning', array(
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

			$em = $this->getEntityManager();
			$closedDays = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:ClosedDay')->getAllBetween($weekDays[0], $weekDays[6], false);

			$teacherAvailabilities = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TeacherAvailability')->getAllByYearWeek($currentYear, $currentWeek, false);

			$courses = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Cours')->getAllByYearWeek($currentYear, $currentWeek, false);

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

			return $this->render('IlcfranceWorldspeakAdminFrontBundle:Default:planning.html.twig', $this->getTwigVars());
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage());
			$this->addFlash('error', $this->translate('Default.planningFailure'));
		}

		return $this->redirect($urlFrom);
	}

	private function importCompanies(\PHPExcel $excelObj)
	{
		$logger = $this->getLogger();
		$em = $this->getEntityManager();
		$excelObj->setActiveSheetIndex(3);
		$worksheet = $excelObj->getActiveSheet();
		$highestRow = $worksheet->getHighestRow();
		$cmpLignesLues = 0;
		$cmpLignesNontraitees = 0;
		$companiestrouvee = 0;
		for ($row = 2; $row <= $highestRow; $row++) {
			$this->extotligne++;
			$cmpLignesLues++;
			$codeMa = strval($worksheet->getCellByColumnAndRow(0, $row)->getValue());
			$Company = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Company')->findOneBy(array(
				'codeMA' => $codeMa
			));

			if (null == $Company) {
				$companiestrouvee++;
				$Company = new Company();
				$Company->setCodeMA($codeMa);
				$Company->setName(strval($worksheet->getCellByColumnAndRow(1, $row)->getValue()));
				$Company->setService(strval($worksheet->getCellByColumnAndRow(2, $row)->getValue()));
				$Company->setAddress(strval($worksheet->getCellByColumnAndRow(3, $row)->getValue()));
				if (strval($worksheet->getCellByColumnAndRow(4, $row)->getValue()) != "") {
					$address2 = strval($worksheet->getCellByColumnAndRow(4, $row)->getValue());
					$Company->setAddress($Company->getAddress() . " - " . $address2);
				}
				$Company->setPostalCode(strval($worksheet->getCellByColumnAndRow(5, $row)->getValue()));
				$Company->setTown(strval($worksheet->getCellByColumnAndRow(6, $row)->getValue()));
				$Company->setCountry('FR');
				$prefix = trim(strtolower(substr($Company->getName(), 0, 4)));
				do {
					$CompanyTest = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Company')->findOneBy(array(
						'prefix' => $prefix
					));
					if (null != $CompanyTest) {
						$prefix .= "1";
					}
				} while ($CompanyTest != null);

				$Company->setPrefix($prefix);

				$em->persist($Company);
				$em->flush();
			} else {
				$cmpLignesNontraitees++;
				$logger->addNotice('Company déjà importée (Ligne :' . intval($cmpLignesLues + 1) . ')');
			}
		}
		$this->infos .= $cmpLignesLues . ' lignes de la fiche des Companies lues<br>';
		$this->infos .= $companiestrouvee . ' nouvelles Companies<br>';
		$this->infos .= $cmpLignesNontraitees . ' Companies deja connues<br>'; // */
	}

	private function importTeacher(\PHPExcel $excelObj)
	{
		$logger = $this->getLogger();
		$em = $this->getEntityManager();
		$role = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Role')->findOneBy(array(
			'name' => 'ROLE_INTERNAL_TEACHER'
		));
		$teacherstrouvee = 0;
		$excelObj->setActiveSheetIndex(2);
		$worksheet = $excelObj->getActiveSheet();
		$highestRow = $worksheet->getHighestRow();
		$tchLignesLues = 0;
		$tchLignesNontraitees = 0;
		$tchLignesFac = 0;
		for ($row = 2; $row <= $highestRow; $row++) {
			$this->extotligne++;
			$tchLignesLues++;
			$tNom = trim(strval($worksheet->getCellByColumnAndRow(1, $row)->getValue()));
			if (stripos($tNom, "(FAC)") !== false) {
				$tchLignesFac++;
				$logger->addNotice('Formateur ignoré (Ligne :' . intval($tchLignesLues + 1) . ')');
			} else {
				$codeMa = strval($worksheet->getCellByColumnAndRow(0, $row)->getValue());
				$Teacher = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Teacher')->findOneBy(array(
					'codeMA' => $codeMa
				));
				$Teacher2 = null;
				$tEmail = $this->normalize(trim(strval($worksheet->getCellByColumnAndRow(12, $row)->getValue())));
				if ($tEmail != "") {
					$Teacher2 = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Teacher')->findOneBy(array(
						'email' => $tEmail
					));
				}
				if (null == $Teacher && null == $Teacher2) {
					$teacherstrouvee++;
					$Teacher = new Teacher();
					$Teacher->setCodeMA($codeMa);
					$Teacher->setLastName(trim(strval($worksheet->getCellByColumnAndRow(1, $row)->getValue())));
					$Teacher->setFirstName(trim(strval($worksheet->getCellByColumnAndRow(2, $row)->getValue())));
					$sexe = trim(strval($worksheet->getCellByColumnAndRow(3, $row)->getValue()));
					if ($sexe == "M.") {
						$Teacher->setSexe(Teacher::SEXE_MR);
					} elseif ($sexe == "Mme") {
						$Teacher->setSexe(Teacher::SEXE_MRS);
					} elseif ($sexe == "Mlle") {
						$Teacher->setSexe(Teacher::SEXE_MISS);
					}
					$Teacher->setPhone(trim(strval($worksheet->getCellByColumnAndRow(11, $row)->getValue())));
					$Teacher->setMobile(trim(strval($worksheet->getCellByColumnAndRow(12, $row)->getValue())));
					$tEmail = trim(strval($worksheet->getCellByColumnAndRow(13, $row)->getValue()));
					if ($tEmail != "") {
						$Teacher->setEmail($tEmail);
					}
					$Teacher->setRegisterMail(Teacher::REGISTERMAIL_NOTSENT);
					$Teacher->setType(Teacher::TYPE_INTERNAL);
					$Teacher->setFtype(Teacher::FTYPE_EN);
					$Teacher->setClearPassword($Teacher->generateRandomChar(12));
					$username = $Teacher->getLastName() . "." . $Teacher->getFirstName();
					$username = strtolower($this->normalize($username));
					$username = str_replace(" ", "", $username);
					do {
						$Teacher2 = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Teacher')->findOneBy(array(
							'username' => $username
						));
						if (null != $Teacher2) {
							$username .= "1";
						}
					} while ($Teacher2 != null);
					$role->addTeacher($Teacher);
					$Teacher->addTeacherRole($role);

					$Teacher->setUsername($username);
					$em->persist($role);
					$em->persist($Teacher);
					$em->flush();
				} else {
					$tchLignesNontraitees++;
					$logger->addNotice('Formateur déjà connu (Ligne :' . intval($tchLignesLues + 1) . ')');
				}
			}
		}
		$this->infos .= $tchLignesLues . ' lignes de la fiche des Formateurs lues<br>';
		$this->infos .= $teacherstrouvee . ' nouveaux Formateurs<br>';
		$this->infos .= $tchLignesNontraitees . ' Formateurs deja connus<br>';
		$this->infos .= $tchLignesFac . ' Formateurs Factices<br>';
	}

	private function importTrainees(\PHPExcel $excelObj)
	{
		$logger = $this->getLogger();
		$em = $this->getEntityManager();
		$role = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Role')->findOneBy(array(
			'name' => 'ROLE_TRAINEE'
		));
		$companies = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Company')->getAll();
		$traineestrouvee = 0;
		$excelObj->setActiveSheetIndex(0);
		$worksheet = $excelObj->getActiveSheet();
		$highestRow = $worksheet->getHighestRow();
		$trnLignesLues = 0;
		$trnLignesNontraitees = 0;
		$trnError = 0;
		for ($row = 2; $row <= $highestRow; $row++) {
			$this->extotligne++;
			$trnLignesLues++;
			$totalH = floatval($worksheet->getCellByColumnAndRow(8, $row)->getValue());
			$doneH = floatval($worksheet->getCellByColumnAndRow(11, $row)->getValue()) - floatval($worksheet->getCellByColumnAndRow(13, $row)->getValue());
			$lostH = floatval($worksheet->getCellByColumnAndRow(13, $row)->getValue());
			$reservedH = floatval($worksheet->getCellByColumnAndRow(14, $row)->getValue());
			$codeMa = strval($worksheet->getCellByColumnAndRow(0, $row)->getValue());

			// if ($codeMa == '411140084' || $codeMa == '374122262') {
			$Trainee = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Trainee')->findOneBy(array(
				'codeMA' => $codeMa
			));
			$Trainee2 = null;
			$tEmail = $this->normalize(strval($worksheet->getCellByColumnAndRow(10, $row)->getValue()));
			if ($tEmail != "") {
				$Trainee2 = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Trainee')->findOneBy(array(
					'email' => $tEmail
				));
			}
			if (null == $Trainee && null == $Trainee2) {
				$traineestrouvee++;
				$Trainee = new Trainee();
				$cmpCodeMa = trim(strval($worksheet->getCellByColumnAndRow(7, $row)->getValue()));
				$Company = null;
				foreach ($companies as $cmp) {
					if ($cmp->getCodeMA() == $cmpCodeMa) {
						$Company = $cmp;
					}
				}

				if (null != $Company) {
					$Trainee->setCompany($Company);
					$t_id = $Company->getAutoinc();
					$username = $Company->getPrefix() . "_" . $t_id;
					$Trainee->setUsername($username);
					$Trainee->setClearPassword($Trainee->generateRandomChar(12));
					$Trainee->setEmail($tEmail);
					$Trainee->addTraineeRole($role);
					$Trainee->setCodeMA($codeMa);

					$Trainee->setLastName(trim(strval($worksheet->getCellByColumnAndRow(1, $row)->getValue())));
					$Trainee->setFirstName(trim(strval($worksheet->getCellByColumnAndRow(2, $row)->getValue())));

					$sexe = trim(strval($worksheet->getCellByColumnAndRow(3, $row)->getValue()));
					if ($sexe == "M.") {
						$Trainee->setSexe(Trainee::SEXE_MR);
					} elseif ($sexe == "Mme") {
						$Trainee->setSexe(Trainee::SEXE_MRS);
					} elseif ($sexe == "Mlle") {
						$Trainee->setSexe(Trainee::SEXE_MISS);
					}
					$Trainee->setRegistermail(Trainee::REGISTERMAIL_NOTSENT);
					$Trainee->setJob(trim(strval($worksheet->getCellByColumnAndRow(5, $row)->getValue())));

					$role->addTrainee($Trainee);
					$Company->setAutoinc($t_id + 1);
					$Company->addTrainee($Trainee);
					$em->persist($Trainee);
					$em->persist($role);
					$em->persist($Company);
					$em->flush();
					// ajout du timeCredit
					$TimeCredit = new TimeCredit();
					$TimeCredit->setTrainee($Trainee);

					$TimeCredit->setTotalHours($totalH);
					$TimeCredit->setDoneHours($doneH);
					$TimeCredit->setLostHours($lostH);
					$TimeCredit->setReservedHours($reservedH);
					$TimeCredit->setNotifyByMail(TimeCredit::NOTIFYBYMAIL_NOTSENT);
					$TimeCredit->setFtype(TimeCredit::FTYPE_EN);
					$TimeCredit->setLevel(TimeCredit::LEVEL_UNDEFINED);
					$em->persist($TimeCredit);
					$em->flush();
				} else {
					$logger->addNotice("Erreur lors de l'import de Stagaire à la ligne " . intval($trnLignesLues + 1) . ' : Company inconnue');
					$trnError++;
				}
			} else {
				$trnLignesNontraitees++;
				$logger->addNotice('Stagiaire déjà connu (Ligne :' . intval($trnLignesLues + 1) . ')');
			}
			/*
			 * } else {
			 * $trnLignesNontraitees++;
			 * $logger->addNotice('Stagiaire déjà connu (Ligne :'.intval($trnLignesLues+1).')');
			 * } //
			 */
		}
		$this->infos .= $trnLignesLues . ' lignes de la fiche des Stragiaires lues<br>';
		$this->infos .= $traineestrouvee . ' nouveaux Stragiaires<br>';
		$this->infos .= $trnLignesNontraitees . ' Stragiaires deja connus<br>';
		$this->infos .= $trnError . ' Lignes de stagiaires contenant des erreurs<br>';
	}

	private function importCourses(\PHPExcel $excelObj)
	{
		$logger = $this->getLogger();
		$em = $this->getEntityManager();
		$teachers = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Teacher')->getAll();
		$trainees = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Trainee')->getAll();
		$courstrouvee = 0;
		$excelObj->setActiveSheetIndex(1);
		$worksheet = $excelObj->getActiveSheet();
		$highestRow = $worksheet->getHighestRow();
		$crLignesLues = 0;
		$crLigneNontraitees = 0;
		$crError = 0;

		for ($row = 2; $row <= $highestRow; $row++) {
			$this->extotligne++;
			$crLignesLues++;
			$codeMA = strval($worksheet->getCellByColumnAndRow(0, $row)->getValue());
			$Cours = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Cours')->findOneBy(array(
				'codeMA' => $codeMA
			));
			if (null != $Cours) {
				$curTeacher = null;
				if (null == $Cours->getTeacher()) {
					$teacherCodeMA = trim(strval($worksheet->getCellByColumnAndRow(1, $row)->getValue()));
					foreach ($teachers as $teacher) {
						if ($teacher->getCodeMA() == $teacherCodeMA) {
							$curTeacher = $teacher;
						}
					}
					if (null != $curTeacher) {
						$Cours->setTeacher($curTeacher);
					}
				}

				// *
				$isplaned = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
				$absent = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
				$present = $worksheet->getCellByColumnAndRow(13, $row)->getValue();

				if ($Cours->getStatus() == Cours::STATUS_PLANNED || $Cours->getStatus() == Cours::STATUS_PLANNED_PENDING) {
					$logger->addNotice('Ligne ' . intval($crLignesLues + 1) . " Present " . $present . " / Absent " . $absent . " / Planified " . $isplaned);
					if ($isplaned == '=FALSE()') {
						if (null != $absent && floatval($absent) != 0) {
							$Cours->setStatus(Cours::STATUS_ABSENT);
							$logger->addNotice('Cours Absent');
						} elseif (null != $present && floatval($present) != 0) {
							$Cours->setStatus(Cours::STATUS_DONE);
							$logger->addNotice('Cours Présent');
						} else {
							$Cours->setStatus(Cours::STATUS_PLANNED);
							$logger->addNotice('Cours Planifié');
						}
					} else {
						$Cours->setStatus(Cours::STATUS_PLANNED);
						$logger->addNotice('Cours Planifié');
					}
					$em->persist($Cours);
				} else {
					$crLigneNontraitees++;
				} // */

				// $crLigneNontraitees++;
			} else {

				$crLigneNontraitees++;

				// *

				$Cours = new Cours();
				$Cours->setType(Cours::TYPE_UNDEFINED);
				$traineeCodeMA = trim(strval($worksheet->getCellByColumnAndRow(9, $row)->getValue()));
				$teacherCodeMA = trim(strval($worksheet->getCellByColumnAndRow(1, $row)->getValue()));
				$curTeacher = null;
				$curTrainee = null;
				foreach ($trainees as $trainee) {
					if ($trainee->getCodeMA() == $traineeCodeMA) {
						$curTrainee = $trainee;
					}
				}

				if (null == $curTrainee) {
					$logger->addNotice("Erreur lors de l'import du cours à la ligne " . intval($crLignesLues + 1) . ' Stagiaire inconnu : ' . strval($worksheet->getCellByColumnAndRow(10, $row)->getValue()));
					$crError++;
				} else {
					$timeCredits = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCredit')->getLastByTrainee($curTrainee);

					if (null == $timeCredits || count($timeCredits) != 1) {
						$logger->addNotice("Erreur lors de l'import du cours à la ligne " . intval($crLignesLues + 1) . ' pas de credit horaire trouvé');
						$crError++;
					} else {
						$timeCredit = $timeCredits[0];
						foreach ($teachers as $teacher) {
							if ($teacher->getCodeMA() == $teacherCodeMA) {
								$curTeacher = $teacher;
							}
						}
						if (null != $curTeacher) {
							$Cours->setTeacher($curTeacher);
						}
						$Cours->setCodeMA($codeMA);
						$Cours->setTimeCredit($timeCredit);
						$dt = \PHPExcel_Shared_Date::ExcelToPHPObject($worksheet->getCellByColumnAndRow(3, $row)->getValue());
						$time = \PHPExcel_Shared_Date::ExcelToPHPObject($worksheet->getCellByColumnAndRow(4, $row)->getValue());

						$strdtcours = $dt->format('Y-m-d') . " " . $time->format('G:i');
						$dtcours = \DateTime::createFromFormat('Y-m-d G:i', $strdtcours);
						$Cours->setDtStart($dtcours);
						$duration = intval(floatval($worksheet->getCellByColumnAndRow(5, $row)->getValue()) * 60);
						$Cours->setDuration($duration);
						$isplaned = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
						$absent = $worksheet->getCellByColumnAndRow(12, $row)->getValue();
						$present = $worksheet->getCellByColumnAndRow(13, $row)->getValue();
						if ($isplaned == '=FALSE()') {
							if (null != $absent && floatval($absent) != 0) {
								$Cours->setStatus(Cours::STATUS_ABSENT);
								$logger->addNotice('Cours Absent');
							} elseif (null != $present && floatval($present) != 0) {
								$Cours->setStatus(Cours::STATUS_DONE);
								$logger->addNotice('Cours Présent');
							} else {
								$Cours->setStatus(Cours::STATUS_PLANNED);
								$logger->addNotice('Cours Planifié');
							}
						} else {
							$Cours->setStatus(Cours::STATUS_PLANNED);
							$logger->addNotice('Cours Planifié');
						}
						$em->persist($Cours);
						$em->flush();
						$courstrouvee++;
					}
				} // */
			}
		}
		$em->flush();
		$this->infos .= $crLignesLues . ' lignes de la fiche des Cours lues<br>';
		$this->infos .= $courstrouvee . ' nouveaux Cours<br>';
		$this->infos .= $crLigneNontraitees . ' Cours deja connus<br>';
		$this->infos .= $crError . ' Lignes contenant des erreurs<br>';
	}
}
