<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Controller;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TimeCredit;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Trainee;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Locale;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TimeCreditAddTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TraineeAddTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TraineeCompanyTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TraineeProjectTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TraineeEmailTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TraineeCefTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TraineeLockoutTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TraineePreferedLocaleTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TraineeProfileTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TraineeProfileAdvancedTForm;
use Ilcfrance\Worldspeak\Shared\ResBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Trainee Controller
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TraineeController extends BaseController
{

	/**
	 * Class Constructor
	 */
	public function __construct()
	{
		$this->addTwigVar('menu_active', 'trainee');
	}

	/**
	 * Get Trainee with pagination 10/page
	 *
	 * @param integer $page
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function listAction($page = 1, Request $request)
	{
		$em = $this->getEntityManager();
		$query = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Trainee')->getAllQuery();

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator->paginate($query, $page, 10);
		$pagination->setPageRange(10);

		$trainee = new Trainee();
		$traineeAddForm = $this->createForm(TraineeAddTForm::class, $trainee);
		$this->addTwigVar('traineeAddForm', $traineeAddForm->createView());

		$this->addTwigVar('trainees', $pagination);
		$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__trainee_list'));
		$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__trainee_list'));
		$this->addTwigVar('smenu_active', 'trainee.list');

		return $this->render('IlcfranceWorldspeakAdminFrontBundle:Trainee:list.html.twig', $this->getTwigVars());
	}

	/**
	 * Get Trainee Buggy with pagination 10/page
	 *
	 * @param integer $page
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function listBuggyAction($page = 1, Request $request)
	{
		$em = $this->getEntityManager();
		$query = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Trainee')->getAllBuggyQuery();

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator->paginate($query, $page, 10);
		$pagination->setPageRange(10);

		$trainee = new Trainee();
		$traineeAddForm = $this->createForm(TraineeAddTForm::class, $trainee);
		$this->addTwigVar('traineeAddForm', $traineeAddForm->createView());

		$this->addTwigVar('trainees', $pagination);
		$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__trainee_list'));
		$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__trainee_listBuggy'));
		$this->addTwigVar('smenu_active', 'trainee.listBuggy');

		return $this->render('IlcfranceWorldspeakAdminFrontBundle:Trainee:listBuggy.html.twig', $this->getTwigVars());
	}

	/**
	 * Get Trainee By Company with pagination 10/page
	 *
	 * @param guid $id
	 * @param integer $page
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function listByCompanyAction($id, $page = 1, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Admin__trainee_list');
		}
		$em = $this->getEntityManager();
		try {
			$company = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Company')->findOneBy(array(
				'id' => $id
			));

			if (null == $company) {
				$this->addFlash('warning', 'Company.editNotfound');

				return $this->redirect($urlFrom);
			}

			$this->addTwigVar('company', $company);

			$query = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Trainee')->getAllByCompanyQuery($company);

			$paginator = $this->get('knp_paginator');
			$pagination = $paginator->paginate($query, $page, 10);
			$pagination->setPageRange(10);

			$trainee = new Trainee();
			$traineeAddForm = $this->createForm(TraineeAddTForm::class, $trainee);
			$this->addTwigVar('traineeAddForm', $traineeAddForm->createView());

			$this->addTwigVar('trainees', $pagination);

			$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__trainee_listByCompany_txt', array(
				'%company%' => $company->getName()
			)));

			$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__trainee_listByCompany', array(
				'%company%' => $company->getName()
			)));

			$this->addTwigVar('smenu_active', 'trainee.list');

			return $this->render('IlcfranceWorldspeakAdminFrontBundle:Trainee:listByCompany.html.twig', $this->getTwigVars());
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage());
		}

		return $this->redirect($urlFrom);
	}

	/**
	 * Search Trainee with pagination 10/page
	 *
	 * @param integer $page
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function searchAction($page = 1, Request $request)
	{
		;
		$q = $request->get('q');
		if (null == $q || trim($q) == "") {
			return $this->redirect($this->generateUrl("Admin__trainee_list"));
		}
		$q = trim($q);
		$em = $this->getEntityManager();
		$count = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Trainee')->countSearch($q);
		$query = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Trainee')->searchQuery($q);

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator->paginate($query, $page, 10);
		$pagination->setPageRange(10);

		$trainee = new Trainee();
		$traineeAddForm = $this->createForm(TraineeAddTForm::class, $trainee);
		$this->addTwigVar('traineeAddForm', $traineeAddForm->createView());

		$this->addTwigVar('trainees', $pagination);
		$this->addTwigVar('countQ', $count);
		$this->addTwigVar('q', $q);
		$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__trainee_search_txt', array(
			'%q%' => $q
		)));
		$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__trainee_search', array(
			'%q%' => $q
		)));
		$this->addTwigVar('smenu_active', 'trainee.list');

		return $this->render('IlcfranceWorldspeakAdminFrontBundle:Trainee:search.html.twig', $this->getTwigVars());
	}

	/**
	 * Add new Trainee (method GET)
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function addAction(Request $request)
	{
		$trainee = new Trainee();
		$traineeAddForm = $this->createForm(TraineeAddTForm::class, $trainee);

		$this->addTwigVar('traineeAddForm', $traineeAddForm->createView());
		$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__trainee_add'));
		$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__trainee_add'));
		$this->addTwigVar('smenu_active', 'trainee.add');

		return $this->render('IlcfranceWorldspeakAdminFrontBundle:Trainee:add.html.twig', $this->getTwigVars());
	}

	/**
	 * Add new Trainee (method POST)
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function addPostAction(Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('Admin__trainee_add_get'));
		}

		$trainee = new Trainee();
		$traineeAddForm = $this->createForm(TraineeAddTForm::class, $trainee);

		;
		$data = $request->request->all();
		if (isset($data['TraineeAddForm'])) {
			$traineeAddForm->handleRequest($request);

			if ($traineeAddForm->isValid()) {
				$em = $this->getEntityManager();

				$company = $trainee->getCompany();
				$t_id = $company->getAutoinc();
				$username = $company->getPrefix() . "_" . $t_id;
				$company->setAutoinc($t_id + 1);

				$trainee->setUsername($username);

				$trainee->setClearPassword(Trainee::generateRandomChar(8, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'));

				$role = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Role')->findOneBy(array(
					'name' => 'ROLE_TRAINEE'
				));

				$trainee->addTraineeRole($role);

				$em->persist($trainee);
				$em->flush();

				if ($trainee->getRegisterMail() == Trainee::REGISTERMAIL_SENT) {
					$locale = null;
					if (null != $trainee->getPreferedLocale()) {
						$locale = $trainee->getPreferedLocale()->getPrefix();
					}
					$mvars = array();
					$mvars['user'] = $trainee;
					$mvars['userPreferedLocale'] = $locale;
					$from = $this->getParameter('mail_from');
					$fromName = $this->getParameter('mail_from_name');
					$subject = $this->translate('_mail.registerAdmin__trainee_subject', array(), null, $locale);

					$message = \Swift_Message::newInstance()->setFrom($from, $fromName)->setTo($trainee->getEmail(), $trainee->getFullname())->setSubject($subject)->setBody($this->renderView('IlcfranceWorldspeakSharedResBundle:Mail:trainee.registration.html.twig', $mvars), 'text/html');

					$this->sendmail($message);

					$this->addFlash('success', $this->translate('Trainee.addSuccessWithMail', array(
						'%trainee%' => $trainee->getFullname()
					)));
				} else {
					$this->addFlash('success', $this->translate('Trainee.addSuccess', array(
						'%trainee%' => $trainee->getFullname()
					)));
				}

				return $this->redirect($this->generateUrl('Admin__trainee_edit_get', array(
					'id' => $trainee->getId()
				)));
			} else {
				$this->addTwigVar('traineeAddForm', $traineeAddForm->createView());
				$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__trainee_add'));
				$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__trainee_add'));
				$this->addTwigVar('smenu_active', 'trainee.add');

				return $this->render('IlcfranceWorldspeakAdminFrontBundle:Trainee:add.html.twig', $this->getTwigVars());
			}
		} else {
			return $this->redirect($urlFrom);
		}
	}

	/**
	 * Get Trainee Avatar
	 *
	 * @param guid $id
	 *
	 * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function avatarAction($id, Request $request)
	{
		$em = $this->getEntityManager();
		try {
			$trainee = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Trainee')->findOneBy(array(
				'id' => $id
			));

			if (null == $trainee) {
				throw new NotFoundHttpException();
			}
			$avatar = $trainee->getAvatar();
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
	 * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function registerMailAction($id, Request $request)
	{
		$em = $this->getEntityManager();
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('Admin__trainee_list'));
		}
		try {
			$trainee = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Trainee')->findOneBy(array(
				'id' => $id
			));

			if (null == $trainee) {
				$this->addFlash('warning', 'Trainee.editNotfound');
			} else {
				if (null == $trainee->getClearPassword() || trim($trainee->getClearPassword()) == '') {
					$trainee->setSalt(md5(uniqid(null, true)));
					$trainee->setClearPassword(Trainee::generateRandomChar(8, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'));
				}
				if (null != $trainee->getEmail() && trim($trainee->getEmail()) != '') {
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
					$subject = $this->translate('_mail.registerAdmin__trainee_subject', array(), null, $locale);

					$message = \Swift_Message::newInstance()->setFrom($from, $fromName)->setTo($trainee->getEmail(), $trainee->getFullname())->setSubject($subject)->setBody($this->renderView('IlcfranceWorldspeakSharedResBundle:Mail:trainee.registration.html.twig', $mvars), 'text/html');

					$this->sendmail($message);

					$this->addFlash('success', $this->translate('Trainee.mailSuccessRegister', array(
						'%trainee%' => $trainee->getFullname()
					)));
					$em->flush();
				} else {
					$this->addFlash('danger', $this->translate('Trainee.mailErrorRegister', array(
						'%trainee%' => $trainee->getFullname()
					)));
				}
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage());
			$this->addFlash('error', $this->translate('Trainee.mailFailure'));
		}

		return $this->redirect($urlFrom);
	}

	/**
	 * Generate new Login and Password By Mail
	 *
	 * @param guid $id
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function newPassMailAction($id, Request $request)
	{
		$em = $this->getEntityManager();
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('Admin__trainee_list'));
		}
		try {
			$trainee = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Trainee')->findOneBy(array(
				'id' => $id
			));

			if (null == $trainee) {
				$this->addFlash('warning', 'Trainee.editNotfound');
			} else {
				if (null == $trainee->getClearPassword() || trim($trainee->getClearPassword()) == '') {
					$trainee->setSalt(md5(uniqid(null, true)));
					$trainee->setClearPassword(Trainee::generateRandomChar(8, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'));
				}
				if (null != $trainee->getEmail() && trim($trainee->getEmail()) != '') {
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
					$subject = $this->translate('_mail.newpassAdmin__trainee_subject', array(), null, $locale);

					$message = \Swift_Message::newInstance()->setFrom($from, $fromName)->setTo($trainee->getEmail(), $trainee->getFullname())->setSubject($subject)->setBody($this->renderView('IlcfranceWorldspeakSharedResBundle:Mail:trainee.newpass.html.twig', $mvars), 'text/html');

					$this->sendmail($message);

					$this->addFlash('success', $this->translate('Trainee.mailSuccessNewPass', array(
						'%trainee%' => $trainee->getFullname()
					)));
					$em->flush();
				} else {
					$this->addFlash('danger', $this->translate('Trainee.mailErrorNewPass', array(
						'%trainee%' => $trainee->getFullname()
					)));
				}
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage());
			$this->addFlash('error', $this->translate('Trainee.mailFailure'));
		}

		return $this->redirect($urlFrom);
	}

	/**
	 * Edit Trainee (method GET)
	 *
	 * @param guid $id
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function editAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Admin__trainee_list');
		}
		$em = $this->getEntityManager();
		try {
			$trainee = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Trainee')->findOneBy(array(
				'id' => $id
			));

			if (null == $trainee) {
				$this->addFlash('warning', 'Trainee.editNotfound');

				return $this->redirect($urlFrom);
			}

			$traineeCompanyForm = $this->createForm(TraineeCompanyTForm::class, $trainee);
			$this->addTwigVar('traineeCompanyForm', $traineeCompanyForm->createView());

			$traineeProjectForm = $this->createForm(TraineeProjectTForm::class, $trainee);
			$this->addTwigVar('traineeProjectForm', $traineeProjectForm->createView());

			$traineeEmailForm = $this->createForm(TraineeEmailTForm::class, $trainee);
			$this->addTwigVar('traineeEmailForm', $traineeEmailForm->createView());

			$traineeCefForm = $this->createForm(TraineeCefTForm::class, $trainee);
			$this->addTwigVar('traineeCefForm', $traineeCefForm->createView());

			$traineeLockoutForm = $this->createForm(TraineeLockoutTForm::class, $trainee);
			$this->addTwigVar('traineeLockoutForm', $traineeLockoutForm->createView());

			$traineePreferedLocaleForm = $this->createForm(TraineePreferedLocaleTForm::class, $trainee);
			$this->addTwigVar('traineePreferedLocaleForm', $traineePreferedLocaleForm->createView());

			$traineeProfileForm = $this->createForm(TraineeProfileTForm::class, $trainee);
			$this->addTwigVar('traineeProfileForm', $traineeProfileForm->createView());

			$traineeProfileAdvancedForm = $this->createForm(TraineeProfileAdvancedTForm::class, $trainee);
			$this->addTwigVar('traineeProfileAdvancedForm', $traineeProfileAdvancedForm->createView());

			$timeCredit = new TimeCredit();
			$timeCredit->setTrainee($trainee);
			if (null != $trainee->getCef()) {
				$timeCredit->setCefBegin($trainee->getCef());
			}
			$timeCreditAddForm = $this->createForm(TimeCreditAddTForm::class, $timeCredit);
			$this->addTwigVar('timeCreditAddForm', $timeCreditAddForm->createView());

			$timeCredits = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCredit')->getAllByTrainee($trainee);

			$this->addTwigVar('timeCredits', $timeCredits);

			if ($this->isGranted('ROLE_SUPER_SUPER_ADMIN')) {
				$notifs = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TraineeNotif')->getAllByTrainee($trainee);
			} else {
				$notifs = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TraineeNotif')->getAllOldPendingTxtByTrainee($trainee);
			}

			$this->addTwigVar('notifs', $notifs);

			$this->addTwigVar('trainee', $trainee);
			$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__trainee_edit_txt', array(
				'%trainee%' => $trainee->getFullname()
			)));

			$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__trainee_edit', array(
				'%trainee%' => $trainee->getFullname()
			)));

			return $this->render('IlcfranceWorldspeakAdminFrontBundle:Trainee:edit.html.twig', $this->getTwigVars());
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage());
		}

		return $this->redirect($urlFrom);
	}

	/**
	 * Edit Trainee (method POST)
	 *
	 * @param guid $id
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function editPostAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Admin__trainee_list');
		}
		$em = $this->getEntityManager();
		try {
			$trainee = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Trainee')->findOneBy(array(
				'id' => $id
			));

			if (null == $trainee) {
				$this->addFlash('warning', 'Trainee.editNotfound');

				return $this->redirect($urlFrom);
			}

			$traineeCompanyForm = $this->createForm(TraineeCompanyTForm::class, $trainee);

			$traineeProjectForm = $this->createForm(TraineeProjectTForm::class, $trainee);

			$traineeEmailForm = $this->createForm(TraineeEmailTForm::class, $trainee);

			$traineeCefForm = $this->createForm(TraineeCefTForm::class, $trainee);

			$traineeLockoutForm = $this->createForm(TraineeLockoutTForm::class, $trainee);

			$traineePreferedLocaleForm = $this->createForm(TraineePreferedLocaleTForm::class, $trainee);

			$traineeProfileForm = $this->createForm(TraineeProfileTForm::class, $trainee);

			$traineeProfileAdvancedForm = $this->createForm(TraineeProfileAdvancedTForm::class, $trainee);

			;
			$data = $request->request->all();
			if (isset($data['TraineeProfileAdvancedForm'])) {
				$traineeProfileAdvancedForm->handleRequest($request);
				if ($traineeProfileAdvancedForm->isValid()) {
					$em->persist($trainee);
					$em->flush();

					$this->addFlash('success', $this->translate('Trainee.editSuccessProfileAdvanced', array(
						'%trainee%' => $trainee->getFullname()
					)));

					return $this->redirect($urlFrom);
				} else {
					$em->refresh($trainee);

					$this->addFlash('error', $this->translate('Trainee.editErrorProfileAdvanced', array(
						'%trainee%' => $trainee->getFullname()
					)));
				}
			} elseif (isset($data['TraineeEmailForm'])) {
				$traineeEmailForm->handleRequest($request);
				if ($traineeEmailForm->isValid()) {
					$em->persist($trainee);
					$em->flush();

					$this->addFlash('success', $this->translate('Trainee.editSuccessEmail', array(
						'%trainee%' => $trainee->getFullname()
					)));

					return $this->redirect($urlFrom);
				} else {
					$em->refresh($trainee);

					$this->addFlash('error', $this->translate('Trainee.editErrorEmail', array(
						'%trainee%' => $trainee->getFullname()
					)));
				}
			} elseif (isset($data['TraineeCefForm'])) {
				$traineeCefForm->handleRequest($request);
				if ($traineeCefForm->isValid()) {
					$em->persist($trainee);
					$em->flush();

					$this->addFlash('success', $this->translate('Trainee.editSuccessCef', array(
						'%trainee%' => $trainee->getFullname()
					)));

					return $this->redirect($urlFrom);
				} else {
					$em->refresh($trainee);

					$this->addFlash('error', $this->translate('Trainee.editErrorCef', array(
						'%trainee%' => $trainee->getFullname()
					)));
				}
			} elseif (isset($data['TraineeCompanyForm'])) {
				$traineeCompanyForm->handleRequest($request);
				if ($traineeCompanyForm->isValid()) {
					$em->persist($trainee);
					$em->flush();

					$this->addFlash('success', $this->translate('Trainee.editSuccessCompany', array(
						'%trainee%' => $trainee->getFullname()
					)));

					return $this->redirect($urlFrom);
				} else {
					$em->refresh($trainee);

					$this->addFlash('error', $this->translate('Trainee.editErrorCompany', array(
						'%trainee%' => $trainee->getFullname()
					)));
				}
			} elseif (isset($data['TraineeProjectForm'])) {
				$traineeProjectForm->handleRequest($request);
				if ($traineeProjectForm->isValid()) {
					$em->persist($trainee);
					$em->flush();

					$this->addFlash('success', $this->translate('Trainee.editSuccessProject', array(
						'%trainee%' => $trainee->getFullname()
					)));

					return $this->redirect($urlFrom);
				} else {
					$em->refresh($trainee);

					$this->addFlash('error', $this->translate('Trainee.editErrorProject', array(
						'%trainee%' => $trainee->getFullname()
					)));
				}
			} elseif (isset($data['TraineeLockoutForm'])) {
				$traineeLockoutForm->handleRequest($request);
				if ($traineeLockoutForm->isValid()) {
					$em->persist($trainee);
					$em->flush();

					$this->addFlash('success', $this->translate('Trainee.editSuccessLockout', array(
						'%trainee%' => $trainee->getFullname()
					)));

					return $this->redirect($urlFrom);
				} else {
					$em->refresh($trainee);

					$this->addFlash('error', $this->translate('Trainee.editErrorLockout', array(
						'%trainee%' => $trainee->getFullname()
					)));
				}
			} elseif (isset($data['TraineeProfileForm'])) {
				$traineeProfileForm->handleRequest($request);
				if ($traineeProfileForm->isValid()) {
					$em->persist($trainee);
					$em->flush();

					$this->addFlash('success', $this->translate('Trainee.editSuccessProfile', array(
						'%trainee%' => $trainee->getFullname()
					)));

					return $this->redirect($urlFrom);
				} else {
					$em->refresh($trainee);

					$this->addFlash('error', $this->translate('Trainee.editErrorProfile', array(
						'%trainee%' => $trainee->getFullname()
					)));
				}
			} elseif (isset($data['TraineePreferedLocaleForm'])) {
				$traineePreferedLocaleForm->handleRequest($request);
				if ($traineePreferedLocaleForm->isValid()) {
					$em->persist($trainee);
					$em->flush();

					$this->addFlash('success', $this->translate('Trainee.editSuccessPreferedLocale', array(
						'%trainee%' => $trainee->getFullname()
					)));

					return $this->redirect($urlFrom);
				} else {
					$em->refresh($trainee);

					$this->addFlash('error', $this->translate('Trainee.editErrorPreferedLocale', array(
						'%trainee%' => $trainee->getFullname()
					)));
				}
			}

			$timeCredit = new TimeCredit();
			$timeCredit->setTrainee($trainee);
			if (null != $trainee->getCef()) {
				$timeCredit->setCefBegin($trainee->getCef());
			}
			$timeCreditAddForm = $this->createForm(TimeCreditAddTForm::class, $timeCredit);
			$this->addTwigVar('timeCreditAddForm', $timeCreditAddForm->createView());

			$timeCredits = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCredit')->getAllByTrainee($trainee);

			$this->addTwigVar('timeCredits', $timeCredits);

			if ($this->isGranted('ROLE_SUPER_SUPER_ADMIN')) {
				$notifs = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TraineeNotif')->getAllByTrainee($trainee);
			} else {
				$notifs = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TraineeNotif')->getAllOldPendingTxtByTrainee($trainee);
			}

			$this->addTwigVar('notifs', $notifs);

			$this->addTwigVar('traineeCompanyForm', $traineeCompanyForm->createView());
			$this->addTwigVar('traineeEmailForm', $traineeEmailForm->createView());
			$this->addTwigVar('traineeCefForm', $traineeCefForm->createView());
			$this->addTwigVar('traineeLockoutForm', $traineeLockoutForm->createView());
			$this->addTwigVar('traineePreferedLocaleForm', $traineePreferedLocaleForm->createView());
			$this->addTwigVar('traineeProfileForm', $traineeProfileForm->createView());
			$this->addTwigVar('traineeProfileAdvancedForm', $traineeProfileAdvancedForm->createView());

			$this->addTwigVar('tabActive', 2);
			$this->addTwigVar('trainee', $trainee);
			$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__trainee_edit_txt', array(
				'%trainee%' => $trainee->getFullname()
			)));

			$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__trainee_edit', array(
				'%trainee%' => $trainee->getFullname()
			)));

			return $this->render('IlcfranceWorldspeakAdminFrontBundle:Trainee:edit.html.twig', $this->getTwigVars());
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage());
		}

		return $this->redirect($urlFrom);
	}

	/**
	 * Delete Trainee
	 *
	 * @param guid $id
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('Admin__trainee_list'));
		}
		$em = $this->getEntityManager();

		try {
			$trainee = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Trainee')->findOneBy(array(
				'id' => $id
			));

			if (null != $trainee) {
				$locale = $trainee->getPreferedLocale();
				if (null != $locale) {
					$locale->removeTrainee($trainee);
					$em->persist($locale);
				}
				$avatar = $trainee->getAvatar();
				if (null != $avatar) {
					$dm = $this->getMongoManager();
					$dm->remove($avatar);
					$dm->flush();
				}

				$em->remove($trainee);
				$em->flush();

				$this->addFlash('success', $this->translate('Trainee.deleteSuccess', array(
					'%trainee%' => $trainee->getFullname()
				)));
			} else {
				$this->addFlash('warning', 'Trainee.deleteNotfound');
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage());
			$this->addFlash('error', 'Trainee.deleteError');
		}

		return $this->redirect($urlFrom);
	}

	/**
	 * Logs Trainee (method GET)
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
			$urlFrom = $this->generateUrl('Admin__trainee_list');
		}
		$em = $this->getEntityManager();
		try {
			$trainee = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Trainee')->findOneBy(array(
				'id' => $id
			));

			if (null == $trainee) {
				$this->addFlash('warning', 'Admin.logNotfound');

				return $this->redirect($urlFrom);
			}

			$query = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TraineeLog')->getAllByTraineeQuery($trainee);

			$paginator = $this->get('knp_paginator');
			$pagination = $paginator->paginate($query, $page, 50);
			$pagination->setPageRange(10);

			$this->addTwigVar('trainee', $trainee);
			$this->addTwigVar('logs', $pagination);

			$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__trainee_log_txt', array(
				'%admin%' => $trainee->getFullname()
			)));

			$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__trainee_log', array(
				'%admin%' => $trainee->getFullname()
			)));

			return $this->render('IlcfranceWorldspeakAdminFrontBundle:Trainee:logs.html.twig', $this->getTwigVars());
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTrace());
		}

		return $this->redirect($urlFrom);
	}

	/**
	 * Delete TraineeLog
	 *
	 * @param guid $id
	 *
	 * @return RedirectResponse
	 */
	public function logDeleteAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Admin__trainee_list');
		}
		$em = $this->getEntityManager();

		try {
			$traineeLog = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TraineeLog')->findOneBy(array(
				'id' => $id
			));

			if (null != $traineeLog) {
				$em->remove($traineeLog);
				$em->flush();

				$this->addFlash('success', 'TraineeLog.deleteSuccess');
			} else {
				$this->addFlash('warning', 'TraineeLog.deleteNotfound');
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTrace());

			$this->addFlash('error', 'TraineeLog.deleteError');
		}

		return $this->redirect($urlFrom);
	}
}
