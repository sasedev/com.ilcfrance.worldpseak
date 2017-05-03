<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Controller;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Teacher;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TeacherAvailability;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Locale;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TeacherAddTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TeacherAvailabilityAddTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TeacherCoursPhoneTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TeacherEmailTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TeacherFtypeTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TeacherLockoutTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TeacherPreferedLocaleTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TeacherProfileTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TeacherTypeTForm;
use Ilcfrance\Worldspeak\Shared\ResBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Teacher Controller
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TeacherController extends BaseController
{

	/**
	 * Class Constructor
	 */
	public function __construct()
	{
		$this->addTwigVar('menu_active', 'teacher');
	}

	/**
	 * Get Teacher with pagination 10/page
	 *
	 * @param integer $page
	 *
	 * @return Response
	 */
	public function listAction($page = 1, Request $request)
	{
		$em = $this->getEntityManager();
		$query = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Teacher')->getAllQuery();

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator->paginate($query, $page, 10);
		$pagination->setPageRange(10);

		$teacher = new Teacher();
		$teacherAddForm = $this->createForm(TeacherAddTForm::class, $teacher);
		$this->addTwigVar('teacherAddForm', $teacherAddForm->createView());

		$this->addTwigVar('teachers', $pagination);
		$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__teacher_list'));
		$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__teacher_list'));
		$this->addTwigVar('smenu_active', 'teacher.list');

		return $this->render('IlcfranceWorldspeakAdminFrontBundle:Teacher:list.html.twig', $this->getTwigVars());
	}

	/**
	 * Get Teacher with pagination 10/page
	 *
	 * @param integer $page
	 *
	 * @return Response
	 */
	public function listBuggyAction($page = 1, Request $request)
	{
		$em = $this->getEntityManager();
		$query = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Teacher')->getAllBuggyQuery();

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator->paginate($query, $page, 10);
		$pagination->setPageRange(10);

		$teacher = new Teacher();
		$teacherAddForm = $this->createForm(TeacherAddTForm::class, $teacher);
		$this->addTwigVar('teacherAddForm', $teacherAddForm->createView());

		$this->addTwigVar('teachers', $pagination);
		$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__teacher_list'));
		$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__teacher_listBuggy'));
		$this->addTwigVar('smenu_active', 'teacher.listBuggy');

		return $this->render('IlcfranceWorldspeakAdminFrontBundle:Teacher:listBuggy.html.twig', $this->getTwigVars());
	}

	/**
	 * Search Teacher with pagination 10/page
	 *
	 * @param integer $page
	 *
	 * @return Response
	 */
	public function searchAction($page = 1, Request $request)
	{
		;
		$q = $request->get('q');
		if (null == $q || trim($q) == "") {
			return $this->redirect($this->generateUrl("Admin__teacher_list"));
		}
		$q = trim($q);
		$em = $this->getEntityManager();
		$count = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Teacher')->countSearch($q);
		$query = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Teacher')->searchQuery($q);

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator->paginate($query, $page, 10);
		$pagination->setPageRange(10);

		$teacher = new Teacher();
		$teacherAddForm = $this->createForm(TeacherAddTForm::class, $teacher);
		$this->addTwigVar('teacherAddForm', $teacherAddForm->createView());

		$this->addTwigVar('teachers', $pagination);
		$this->addTwigVar('countQ', $count);
		$this->addTwigVar('q', $q);
		$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__teacher_search_txt', array(
			'%q%' => $q
		)));
		$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__teacher_search', array(
			'%q%' => $q
		)));

		$this->addTwigVar('smenu_active', 'teacher.list');

		return $this->render('IlcfranceWorldspeakAdminFrontBundle:Teacher:search.html.twig', $this->getTwigVars());
	}

	public function availabilitiesAction($year = null, $week = null, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Admin__teacher_list');
		}
		if (null == $year || $year < 1) {
			$currentYear = intval(date("Y"));
			$currentWeek = intval(date("W"));
			$currentMonth = intval(date("n"));

			if ($currentMonth == 12 && $currentWeek == 1) {
				$currentYear++;
			}

			return $this->redirect($this->generateUrl('Admin__teacher_availabilities', array(
				'year' => $currentYear,
				'week' => $currentWeek
			)));
		} elseif (null == $week) {
			$currentYear = $year;
			$currentWeek = date("W");

			return $this->redirect($this->generateUrl('Admin__teacher_availabilities', array(
				'year' => $currentYear,
				'week' => $currentWeek
			)));
		} elseif ($week < 1) {
			$currentYear = $year;
			$currentWeek = 1;

			return $this->redirect($this->generateUrl('Admin__teacher_availabilities', array(
				'year' => $currentYear,
				'week' => $currentWeek
			)));
		} elseif ($week > $this->getIsoWeeksInYear($year)) {
			$currentYear = $year;
			$currentWeek = $this->getIsoWeeksInYear($year);

			return $this->redirect($this->generateUrl('Admin__teacher_availabilities', array(
				'year' => $currentYear,
				'week' => $currentWeek
			)));
		} else {
			$currentYear = $year;
			$currentWeek = $week;
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
		$closedDays = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:ClosedDay')->getAllBetween($weekDays[0], $weekDays[6]);

		$teacherAvailabilities = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TeacherAvailability')->getAllByYearWeek($currentYear, $currentWeek);

		$teacherAvailability = new TeacherAvailability();
		$teacherAvailabilityAddForm = $this->createForm(TeacherAvailabilityAddTForm::class, $teacherAvailability);

		$this->addTwigVar('teacherAvailabilityAddForm', $teacherAvailabilityAddForm->createView());

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

		$this->addTwigVar('smenu_active', 'teacher.availabilities');

		$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__teacher_availabilities_txt', array(
			'%year%' => $currentYear,
			'%week%' => $currentWeek
		)));

		$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__teacher_availabilities', array(
			'%year%' => $currentYear,
			'%week%' => $currentWeek
		)));

		return $this->render('IlcfranceWorldspeakAdminFrontBundle:Teacher:availabilities.html.twig', $this->getTwigVars());
	}

	/**
	 * Add new Teacher (method GET)
	 *
	 * @return Response
	 */
	public function addAction(Request $request)
	{
		$teacher = new Teacher();
		$teacherAddForm = $this->createForm(TeacherAddTForm::class, $teacher);

		$this->addTwigVar('teacherAddForm', $teacherAddForm->createView());
		$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__teacher_add'));
		$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__teacher_add'));
		$this->addTwigVar('smenu_active', 'teacher.add');

		return $this->render('IlcfranceWorldspeakAdminFrontBundle:Teacher:add.html.twig', $this->getTwigVars());
	}

	/**
	 * Add new Teacher (method POST)
	 *
	 * @return RedirectResponse Response
	 */
	public function addPostAction(Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('Admin__teacher_add_get'));
		}

		$teacher = new Teacher();
		$teacherAddForm = $this->createForm(TeacherAddTForm::class, $teacher);

		;
		$data = $request->request->all();
		if (isset($data['TeacherAddForm'])) {
			$teacherAddForm->handleRequest($request);

			if ($teacherAddForm->isValid()) {
				$em = $this->getEntityManager();

				$teacher->setClearPassword(Teacher::generateRandomChar(8, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'));
				if ($teacher->getType() == Teacher::TYPE_INTERNAL) {
					$role = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Role')->findOneBy(array(
						'name' => 'ROLE_INTERNAL_TEACHER'
					));

					$teacher->addTeacherRole($role);
				}
				if ($teacher->getType() == Teacher::TYPE_EXTERNAL) {
					$role = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Role')->findOneBy(array(
						'name' => 'ROLE_EXTERNAL_TEACHER'
					));

					$teacher->addTeacherRole($role);
				}

				$em->persist($teacher);
				$em->flush();

				if ($teacher->getRegisterMail() == Teacher::REGISTERMAIL_SENT) {
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

					$this->addFlash('success', $this->translate('Teacher.addSuccessWithMail', array(
						'%teacher%' => $teacher->getFullname()
					)));
				} else {
					$this->addFlash('success', $this->translate('Teacher.addSuccess', array(
						'%teacher%' => $teacher->getFullname()
					)));
				}

				return $this->redirect($this->generateUrl('Admin__teacher_edit_get', array(
					'id' => $teacher->getId()
				)));
			} else {
				$this->addTwigVar('teacherAddForm', $teacherAddForm->createView());
				$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__teacher_add'));
				$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__teacher_add'));
				$this->addTwigVar('smenu_active', 'teacher.add');

				return $this->render('IlcfranceWorldspeakAdminFrontBundle:Teacher:add.html.twig', $this->getTwigVars());
			}
		} else {
			return $this->redirect($urlFrom);
		}
	}

	/**
	 * Get Teacher Avatar
	 *
	 * @param guid $id
	 *
	 * @return Response
	 * @throws NotFoundHttpException
	 */
	public function avatarAction($id, Request $request)
	{
		$em = $this->getEntityManager();
		try {
			$teacher = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Teacher')->findOneBy(array(
				'id' => $id
			));

			if (null == $teacher) {
				throw new NotFoundHttpException();
			}
			$avatar = $teacher->getAvatar();
			if (null == $avatar) {
				throw new NotFoundHttpException();
			}
			$response = new Response();
			$response->headers->set('Content-Type', $avatar->getMimeType());
			$response->setContent($avatar->getFile()->getBytes());

			return $response;
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage());

			throw new NotFoundHttpException();
		}
	}

	/**
	 * Set Login and password by Mail
	 *
	 * @param guid $id
	 *
	 * @throws NotFoundHttpException
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function registerMailAction($id, Request $request)
	{
		$em = $this->getEntityManager();
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('Admin__teacher_list'));
		}
		try {
			$teacher = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Teacher')->findOneBy(array(
				'id' => $id
			));

			if (null == $teacher) {
				$this->addFlash('warning', 'Teacher.editNotfound');
			} else {
				if (null == $teacher->getClearPassword() || trim($teacher->getClearPassword()) == '') {
					$teacher->setSalt(md5(uniqid(null, true)));
					$teacher->setClearPassword(Teacher::generateRandomChar(8, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'));
				}
				if (null != $teacher->getEmail() && trim($teacher->getEmail()) != '') {
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

					$this->addFlash('success', $this->translate('Teacher.mailSuccessRegister', array(
						'%teacher%' => $teacher->getFullname()
					)));
					$em->flush();
				} else {
					$this->addFlash('danger', $this->translate('Teacher.mailErrorRegister', array(
						'%teacher%' => $teacher->getFullname()
					)));
				}
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage());
			$this->addFlash('error', $this->translate('Teacher.mailFailure'));
		}

		return $this->redirect($urlFrom);
	}

	/**
	 * Generate new Login and Password By Mail
	 *
	 * @param guid $id
	 *
	 * @return Response
	 * @throws NotFoundHttpException
	 */
	public function newPassMailAction($id, Request $request)
	{
		$em = $this->getEntityManager();
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('Admin__teacher_list'));
		}
		try {
			$teacher = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Teacher')->findOneBy(array(
				'id' => $id
			));

			if (null == $teacher) {
				$this->addFlash('warning', 'Teacher.editNotfound');
			} else {
				if (null == $teacher->getClearPassword() || trim($teacher->getClearPassword()) == '') {
					$teacher->setSalt(md5(uniqid(null, true)));
					$teacher->setClearPassword(Teacher::generateRandomChar(8, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'));
				}
				if (null != $teacher->getEmail() && trim($teacher->getEmail()) != '') {
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
					$subject = $this->translate('_mail.newpass_teacher_subject', array(), null, $locale);

					$message = \Swift_Message::newInstance()->setFrom($from, $fromName)->setTo($teacher->getEmail(), $teacher->getFullname())->setSubject($subject)->setBody($this->renderView('IlcfranceWorldspeakSharedResBundle:Mail:teacher.newpass.html.twig', $mvars), 'text/html');

					$this->sendmail($message);

					$this->addFlash('success', $this->translate('Teacher.mailSuccessNewPass', array(
						'%teacher%' => $teacher->getFullname()
					)));
					$em->flush();
				} else {
					$this->addFlash('danger', $this->translate('Teacher.mailErrorNewPass', array(
						'%teacher%' => $teacher->getFullname()
					)));
				}
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage());
			$this->addFlash('error', $this->translate('Teacher.mailFailure'));
		}

		return $this->redirect($urlFrom);
	}

	public function planningAction($id, $year = null, $week = null, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Admin__teacher_edit_get', array(
				'id' => $id
			));
		}
		if (null == $year || $year < 1) {
			$currentYear = intval(date("Y"));
			$currentWeek = intval(date("W"));
			$currentMonth = intval(date("n"));

			if ($currentMonth == 12 && $currentWeek == 1) {
				$currentYear++;
			}

			return $this->redirect($this->generateUrl('Admin__teacher_planning', array(
				'id' => $id,
				'year' => $currentYear,
				'week' => $currentWeek
			)));
		} elseif (null == $week) {
			$currentYear = $year;
			$currentWeek = date("W");

			return $this->redirect($this->generateUrl('Admin__teacher_planning', array(
				'id' => $id,
				'year' => $currentYear,
				'week' => $currentWeek
			)));
		} elseif ($week < 1) {
			$currentYear = $year;
			$currentWeek = 1;

			return $this->redirect($this->generateUrl('Admin__teacher_planning', array(
				'id' => $id,
				'year' => $currentYear,
				'week' => $currentWeek
			)));
		} elseif ($week > $this->getIsoWeeksInYear($year)) {
			$currentYear = $year;
			$currentWeek = $this->getIsoWeeksInYear($year);

			return $this->redirect($this->generateUrl('Admin__teacher_planning', array(
				'id' => $id,
				'year' => $currentYear,
				'week' => $currentWeek
			)));
		} else {
			$currentYear = $year;
			$currentWeek = $week;
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
		try {
			$teacher = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Teacher')->findOneBy(array(
				'id' => $id
			));

			if (null == $teacher) {
				$this->addFlash('warning', 'Teacher.editNotfound');
			} else {
				$closedDays = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:ClosedDay')->getAllBetween($weekDays[0], $weekDays[6]);

				$teacherAvailabilities = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TeacherAvailability')->getAllByYearWeekTeacher($currentYear, $currentWeek, $teacher);

				$courses = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Cours')->getAllByYearWeekTeacher($currentYear, $currentWeek, $teacher);

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

				$this->addTwigVar('teacher', $teacher);
				$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__teacher_planning_txt', array(
					'%teacher%' => $teacher->getFullname(),
					'%year%' => $currentYear,
					'%week%' => $currentWeek
				)));

				$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__teacher_planning', array(
					'%teacher%' => $teacher->getFullname(),
					'%year%' => $currentYear,
					'%week%' => $currentWeek
				)));

				return $this->render('IlcfranceWorldspeakAdminFrontBundle:Teacher:planning.html.twig', $this->getTwigVars());
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage());
			$this->addFlash('error', $this->translate('Teacher.planningFailure'));
		}

		return $this->redirect($urlFrom);
	}

	/**
	 * Edit Teacher (method GET)
	 *
	 * @param guid $id
	 *
	 * @return RedirectResponse Response
	 */
	public function editAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Admin__teacher_list');
		}
		$em = $this->getEntityManager();
		try {
			$teacher = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Teacher')->findOneBy(array(
				'id' => $id
			));

			if (null == $teacher) {
				$this->addFlash('warning', 'Teacher.editNotfound');

				return $this->redirect($urlFrom);
			}

			$teacherCoursPhoneForm = $this->createForm(TeacherCoursPhoneTForm::class, $teacher);
			$this->addTwigVar('teacherCoursPhoneForm', $teacherCoursPhoneForm->createView());

			$teacherEmailForm = $this->createForm(TeacherEmailTForm::class, $teacher);
			$this->addTwigVar('teacherEmailForm', $teacherEmailForm->createView());

			$teacherFtypeForm = $this->createForm(TeacherFtypeTForm::class, $teacher);
			$this->addTwigVar('teacherFtypeForm', $teacherFtypeForm->createView());

			$teacherLockoutForm = $this->createForm(TeacherLockoutTForm::class, $teacher);
			$this->addTwigVar('teacherLockoutForm', $teacherLockoutForm->createView());

			$teacherPreferedLocaleForm = $this->createForm(TeacherPreferedLocaleTForm::class, $teacher);
			$this->addTwigVar('teacherPreferedLocaleForm', $teacherPreferedLocaleForm->createView());

			$teacherProfileForm = $this->createForm(TeacherProfileTForm::class, $teacher);
			$this->addTwigVar('teacherProfileForm', $teacherProfileForm->createView());

			$teacherTypeForm = $this->createForm(TeacherTypeTForm::class, $teacher);
			$this->addTwigVar('teacherTypeForm', $teacherTypeForm->createView());

			if ($this->isGranted('ROLE_SUPER_SUPER_ADMIN')) {
				$notifs = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TeacherNotif')->getAllByTeacher($teacher);
			} else {
				$notifs = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TeacherNotif')->getAllOldPendingTxtByTeacher($teacher);
			}

			$this->addTwigVar('notifs', $notifs);

			$this->addTwigVar('teacher', $teacher);
			$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__teacher_edit_txt', array(
				'%teacher%' => $teacher->getFullname()
			)));

			$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__teacher_edit', array(
				'%teacher%' => $teacher->getFullname()
			)));

			return $this->render('IlcfranceWorldspeakAdminFrontBundle:Teacher:edit.html.twig', $this->getTwigVars());
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage());
		}

		return $this->redirect($urlFrom);
	}

	/**
	 * Edit Teacher (method POST)
	 *
	 * @param guid $id
	 *
	 * @return RedirectResponse Response
	 */
	public function editPostAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Admin__teacher_list');
		}
		$em = $this->getEntityManager();
		try {
			$teacher = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Teacher')->findOneBy(array(
				'id' => $id
			));

			if (null == $teacher) {
				$this->addFlash('warning', 'Teacher.editNotfound');

				return $this->redirect($urlFrom);
			}

			$teacherCoursPhoneForm = $this->createForm(TeacherCoursPhoneTForm::class, $teacher);

			$teacherEmailForm = $this->createForm(TeacherEmailTForm::class, $teacher);

			$teacherFtypeForm = $this->createForm(TeacherFtypeTForm::class, $teacher);

			$teacherLockoutForm = $this->createForm(TeacherLockoutTForm::class, $teacher);

			$teacherPreferedLocaleForm = $this->createForm(TeacherPreferedLocaleTForm::class, $teacher);

			$teacherProfileForm = $this->createForm(TeacherProfileTForm::class, $teacher);

			$teacherTypeForm = $this->createForm(TeacherTypeTForm::class, $teacher);

			;
			$data = $request->request->all();
			if (isset($data['TeacherTypeForm'])) {
				$teacherTypeForm->handleRequest($request);
				if ($teacherTypeForm->isValid()) {
					$teacher->emptyRoles();
					if ($teacher->getType() == Teacher::TYPE_INTERNAL) {
						$role = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Role')->findOneBy(array(
							'name' => 'ROLE_INTERNAL_TEACHER'
						));

						$teacher->addTeacherRole($role);
					}
					if ($teacher->getType() == Teacher::TYPE_EXTERNAL) {
						$role = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Role')->findOneBy(array(
							'name' => 'ROLE_EXTERNAL_TEACHER'
						));

						$teacher->addTeacherRole($role);
					}
					$em->persist($teacher);
					$em->flush();

					$this->addFlash('success', $this->translate('Teacher.editSuccessType', array(
						'%teacher%' => $teacher->getFullname()
					)));

					return $this->redirect($urlFrom);
				} else {
					$em->refresh($teacher);

					$this->addFlash('error', $this->translate('Teacher.editErrorType', array(
						'%teacher%' => $teacher->getFullname()
					)));
				}
			} elseif (isset($data['TeacherEmailForm'])) {
				$teacherEmailForm->handleRequest($request);
				if ($teacherEmailForm->isValid()) {
					$em->persist($teacher);
					$em->flush();

					$this->addFlash('success', $this->translate('Teacher.editSuccessEmail', array(
						'%teacher%' => $teacher->getFullname()
					)));

					return $this->redirect($urlFrom);
				} else {
					$em->refresh($teacher);

					$this->addFlash('error', $this->translate('Teacher.editErrorEmail', array(
						'%teacher%' => $teacher->getFullname()
					)));
				}
			} elseif (isset($data['TeacherFtypeForm'])) {
				$teacherFtypeForm->handleRequest($request);
				if ($teacherFtypeForm->isValid()) {
					$em->persist($teacher);
					$em->flush();

					$this->addFlash('success', $this->translate('Teacher.editSuccessFtype', array(
						'%teacher%' => $teacher->getFullname()
					)));

					return $this->redirect($urlFrom);
				} else {
					$em->refresh($teacher);

					$this->addFlash('error', $this->translate('Teacher.editErrorFtype', array(
						'%teacher%' => $teacher->getFullname()
					)));
				}
			} elseif (isset($data['TeacherCoursPhoneForm'])) {
				$teacherCoursPhoneForm->handleRequest($request);
				if ($teacherCoursPhoneForm->isValid()) {
					$em->persist($teacher);
					$em->flush();

					$this->addFlash('success', $this->translate('Teacher.editSuccessCoursPhone', array(
						'%teacher%' => $teacher->getFullname()
					)));

					return $this->redirect($urlFrom);
				} else {
					$em->refresh($teacher);

					$this->addFlash('error', $this->translate('Teacher.editErrorCoursPhone', array(
						'%teacher%' => $teacher->getFullname()
					)));
				}
			} elseif (isset($data['TeacherLockoutForm'])) {
				$teacherLockoutForm->handleRequest($request);
				if ($teacherLockoutForm->isValid()) {
					$em->persist($teacher);
					$em->flush();

					$this->addFlash('success', $this->translate('Teacher.editSuccessLockout', array(
						'%teacher%' => $teacher->getFullname()
					)));

					return $this->redirect($urlFrom);
				} else {
					$em->refresh($teacher);

					$this->addFlash('error', $this->translate('Teacher.editErrorLockout', array(
						'%teacher%' => $teacher->getFullname()
					)));
				}
			} elseif (isset($data['TeacherProfileForm'])) {
				$teacherProfileForm->handleRequest($request);
				if ($teacherProfileForm->isValid()) {
					$em->persist($teacher);
					$em->flush();

					$this->addFlash('success', $this->translate('Teacher.editSuccessProfile', array(
						'%teacher%' => $teacher->getFullname()
					)));

					return $this->redirect($urlFrom);
				} else {
					$em->refresh($teacher);

					$this->addFlash('error', $this->translate('Teacher.editErrorProfile', array(
						'%teacher%' => $teacher->getFullname()
					)));
				}
			} elseif (isset($data['TeacherPreferedLocaleForm'])) {
				$teacherPreferedLocaleForm->handleRequest($request);
				if ($teacherPreferedLocaleForm->isValid()) {
					$em->persist($teacher);
					$em->flush();

					$this->addFlash('success', $this->translate('Teacher.editSuccessPreferedLocale', array(
						'%teacher%' => $teacher->getFullname()
					)));

					return $this->redirect($urlFrom);
				} else {
					$em->refresh($teacher);

					$this->addFlash('error', $this->translate('Teacher.editErrorPreferedLocale', array(
						'%teacher%' => $teacher->getFullname()
					)));
				}
			}

			$this->addTwigVar('teacherCoursPhoneForm', $teacherCoursPhoneForm->createView());
			$this->addTwigVar('teacherEmailForm', $teacherEmailForm->createView());
			$this->addTwigVar('teacherFtypeForm', $teacherFtypeForm->createView());
			$this->addTwigVar('teacherLockoutForm', $teacherLockoutForm->createView());
			$this->addTwigVar('teacherPreferedLocaleForm', $teacherPreferedLocaleForm->createView());
			$this->addTwigVar('teacherProfileForm', $teacherProfileForm->createView());
			$this->addTwigVar('teacherTypeForm', $teacherTypeForm->createView());

			if ($this->isGranted('ROLE_SUPER_SUPER_ADMIN')) {
				$notifs = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TeacherNotif')->getAllByTeacher($teacher);
			} else {
				$notifs = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TeacherNotif')->getAllOldPendingTxtByTeacher($teacher);
			}

			$this->addTwigVar('notifs', $notifs);

			$this->addTwigVar('tabActive', 2);
			$this->addTwigVar('teacher', $teacher);
			$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__teacher_edit_txt', array(
				'%teacher%' => $teacher->getFullname()
			)));

			$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__teacher_edit', array(
				'%teacher%' => $teacher->getFullname()
			)));

			return $this->render('IlcfranceWorldspeakAdminFrontBundle:Teacher:edit.html.twig', $this->getTwigVars());
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage());
		}

		return $this->redirect($urlFrom);
	}

	/**
	 * Delete Teacher
	 *
	 * @param guid $id
	 *
	 * @return RedirectResponse
	 */
	public function deleteAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('Admin__teacher_list'));
		}
		$em = $this->getEntityManager();

		try {
			$teacher = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Teacher')->findOneBy(array(
				'id' => $id
			));

			if (null != $teacher) {
				$locale = $teacher->getPreferedLocale();
				if (null != $locale) {
					$locale->removeTeacher($teacher);
					$em->persist($locale);
				}
				$courses = $teacher->getCourses();
				foreach ($courses as $cours) {
					$cours->setTeacher(null);
					$em->persist($cours);
				}
				$avatar = $teacher->getAvatar();
				if (null != $avatar) {
					$dm = $this->getMongoManager();
					$dm->remove($avatar);
					$dm->flush();
				}

				$em->remove($teacher);
				$em->flush();

				$this->addFlash('success', $this->translate('Teacher.deleteSuccess', array(
					'%teacher%' => $teacher->getFullname()
				)));
			} else {
				$this->addFlash('warning', 'Teacher.deleteNotfound');
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage());
			$this->addFlash('error', 'Teacher.deleteError');
		}

		return $this->redirect($urlFrom);
	}

	/**
	 * Logs Teacher (method GET)
	 *
	 * @param guid $id
	 * @param integer $page
	 *
	 * @return RedirectResponse|Response
	 */
	public function logsAction($id, $page, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Admin__teacher_list');
		}
		$em = $this->getEntityManager();
		try {
			$teacher = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Teacher')->findOneBy(array(
				'id' => $id
			));

			if (null == $teacher) {
				$this->addFlash('warning', 'Admin.logNotfound');

				return $this->redirect($urlFrom);
			}

			$query = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TeacherLog')->getAllByTeacherQuery($teacher);

			$paginator = $this->get('knp_paginator');
			$pagination = $paginator->paginate($query, $page, 50);
			$pagination->setPageRange(10);

			$this->addTwigVar('teacher', $teacher);
			$this->addTwigVar('logs', $pagination);

			$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__teacher_log_txt', array(
				'%admin%' => $teacher->getFullname()
			)));

			$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__teacher_log', array(
				'%admin%' => $teacher->getFullname()
			)));

			return $this->render('IlcfranceWorldspeakAdminFrontBundle:Teacher:logs.html.twig', $this->getTwigVars());
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTrace());
		}

		return $this->redirect($urlFrom);
	}

	/**
	 * Delete TeacherLog
	 *
	 * @param guid $id
	 *
	 * @return RedirectResponse
	 */
	public function logDeleteAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Admin__teacher_list');
		}
		$em = $this->getEntityManager();

		try {
			$teacherLog = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TeacherLog')->findOneBy(array(
				'id' => $id
			));

			if (null != $teacherLog) {
				$em->remove($teacherLog);
				$em->flush();

				$this->addFlash('success', 'TeacherLog.deleteSuccess');
			} else {
				$this->addFlash('warning', 'TeacherLog.deleteNotfound');
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTrace());

			$this->addFlash('error', 'TeacherLog.deleteError');
		}

		return $this->redirect($urlFrom);
	}
}
