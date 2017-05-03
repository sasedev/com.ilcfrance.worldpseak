<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Controller;

use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\AdminAddTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\AdminEmailTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\AdminLockoutTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\AdminPreferedLocaleTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\AdminProfileTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\AdminRoleTForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Admin;
use Ilcfrance\Worldspeak\Shared\ResBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Admin Controller
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class AdminController extends BaseController
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->addTwigVar('menu_active', 'admin');
	}

	/**
	 * Get Admin with pagination 10/page
	 *
	 * @param integer $page
	 *
	 * @return Response
	 */
	public function listAction($page = 1, Request $request)
	{
		$sc = $this->getSecurityAuthorizationChecker();
		$em = $this->getEntityManager();
		$query = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Admin')->getAllQuery();

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator->paginate($query, $page, 10);
		$pagination->setPageRange(10);

		$admin = new Admin();
		if ($sc->isGranted('ROLE_SUPER_ADMIN')) {
			if ($sc->isGranted('ROLE_SUPER_SUPER_ADMIN')) {
				$adminAddForm = $this->createForm(AdminAddTForm::class, $admin, array(
					'selrole' => true
				));
			} else {
				$adminAddForm = $this->createForm(AdminAddTForm::class, $admin);
			}
			$this->addTwigVar('adminAddForm', $adminAddForm->createView());
		}

		$currentUser = $this->getSecurityTokenStorage()->getToken()->getUser();
		$this->addTwigVar('currentUser', $currentUser);

		$this->addTwigVar('admins', $pagination);
		$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__admin_list'));
		$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__admin_list'));
		$this->addTwigVar('smenu_active', 'admin.list');

		return $this->render('IlcfranceWorldspeakAdminFrontBundle:Admin:list.html.twig', $this->getTwigVars());
	}

	/**
	 * Get Buggy Admin with pagination 10/page
	 *
	 * @param integer $page
	 *
	 * @return Response
	 */
	public function listBuggyAction($page = 1, Request $request)
	{
		$sc = $this->getSecurityAuthorizationChecker();
		$em = $this->getEntityManager();
		$query = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Admin')->getAllBuggyQuery();

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator->paginate($query, $page, 10);
		$pagination->setPageRange(10);

		$admin = new Admin();
		if ($sc->isGranted('ROLE_SUPER_ADMIN')) {
			if ($sc->isGranted('ROLE_SUPER_SUPER_ADMIN')) {
				$adminAddForm = $this->createForm(AdminAddTForm::class, $admin, array(
					'selrole' => true
				));
			} else {
				$adminAddForm = $this->createForm(AdminAddTForm::class, $admin);
			}
			$this->addTwigVar('adminAddForm', $adminAddForm->createView());
		}

		$currentUser = $this->getSecurityTokenStorage()->getToken()->getUser();
		$this->addTwigVar('currentUser', $currentUser);

		$this->addTwigVar('admins', $pagination);
		$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__admin_list'));
		$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__admin_listBuggy'));
		$this->addTwigVar('smenu_active', 'admin.list');

		return $this->render('IlcfranceWorldspeakAdminFrontBundle:Admin:listBuggy.html.twig', $this->getTwigVars());
	}

	/**
	 * Search Admin with pagination 10/page
	 *
	 * @param integer $page
	 *
	 * @return Response
	 */
	public function searchAction($page = 1, Request $request)
	{
		$q = $request->get('q');
		if (null == $q || trim($q) == "") {
			return $this->redirect($this->generateUrl("Admin__admin_list"));
		}
		$q = trim($q);

		$sc = $this->getSecurityAuthorizationChecker();
		$em = $this->getEntityManager();
		$count = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Admin')->countSearch($q);
		$query = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Admin')->searchQuery($q);

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator->paginate($query, $page, 10);
		$pagination->setPageRange(10);

		$admin = new Admin();
		if ($sc->isGranted('ROLE_SUPER_ADMIN')) {
			if ($sc->isGranted('ROLE_SUPER_SUPER_ADMIN')) {
				$adminAddForm = $this->createForm(AdminAddTForm::class, $admin, array(
					'selrole' => true
				));
			} else {
				$adminAddForm = $this->createForm(AdminAddTForm::class, $admin);
			}
			$this->addTwigVar('adminAddForm', $adminAddForm->createView());
		}

		$currentUser = $this->getSecurityTokenStorage()->getToken()->getUser();
		$this->addTwigVar('currentUser', $currentUser);

		$this->addTwigVar('admins', $pagination);
		$this->addTwigVar('countQ', $count);
		$this->addTwigVar('q', $q);
		$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__admin_search_txt', array(
			'%q%' => $q
		)));
		$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__admin_search', array(
			'%q%' => $q
		)));

		$this->addTwigVar('smenu_active', 'admin.list');

		return $this->render('IlcfranceWorldspeakAdminFrontBundle:Admin:search.html.twig', $this->getTwigVars());
	}

	/**
	 * Add new Admin (method GET)
	 *
	 * @return Response @Security("has_role('ROLE_SUPER_ADMIN')")
	 */
	public function addAction(Request $request)
	{
		$sc = $this->getSecurityAuthorizationChecker();

		$admin = new Admin();
		if ($sc->isGranted('ROLE_SUPER_SUPER_ADMIN')) {
			$adminAddForm = $this->createForm(AdminAddTForm::class, $admin, array(
				'selrole' => true
			));
		} else {
			$adminAddForm = $this->createForm(AdminAddTForm::class, $admin);
		}

		$this->addTwigVar('adminAddForm', $adminAddForm->createView());
		$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__admin_add'));
		$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__admin_add'));
		$this->addTwigVar('smenu_active', 'admin.add');

		return $this->render('IlcfranceWorldspeakAdminFrontBundle:Admin:add.html.twig', $this->getTwigVars());
	}

	/**
	 * Add new Admin (method POST)
	 *
	 * @return RedirectResponse Response
	 */
	public function addPostAction(Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('Admin__admin_add_get'));
		}
		$sc = $this->getSecurityAuthorizationChecker();

		$admin = new Admin();
		if ($sc->isGranted('ROLE_SUPER_SUPER_ADMIN')) {
			$adminAddForm = $this->createForm(AdminAddTForm::class, $admin, array(
				'selrole' => true
			));
		} else {
			$adminAddForm = $this->createForm(AdminAddTForm::class, $admin);
		}
		$data = $request->request->all();
		if (isset($data['AdminAddForm'])) {
			$adminAddForm->handleRequest($request);

			if ($adminAddForm->isValid()) {
				$em = $this->getEntityManager();

				$admin->setClearPassword(Admin::generateRandomChar(8, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'));
				if (!$sc->isGranted('ROLE_SUPER_SUPER_ADMIN')) {
					$role = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Role')->findOneBy(array(
						'name' => 'ROLE_ADMIN'
					));

					$admin->addAdminRole($role);
				} else {
					$role = $adminAddForm['adminRoles']->getData();
					$admin->addAdminRole($role);
				}

				$em->persist($admin);
				$em->flush();

				$locale = null;
				if (null != $admin->getPreferedLocale()) {
					$locale = $admin->getPreferedLocale()->getPrefix();
				}
				$mvars = array();
				$mvars['user'] = $admin;
				$mvars['userPreferedLocale'] = $locale;
				$from = $this->getParameter('mail_from');
				$fromName = $this->getParameter('mail_from_name');
				$subject = $this->translate('_mail.registerAdmin_admin_subject', array(), null, $locale);

				$message = \Swift_Message::newInstance()->setFrom($from, $fromName)->setTo($admin->getEmail(), $admin->getFullname())->setSubject($subject)->setBody($this->renderView('IlcfranceWorldspeakSharedResBundle:Mail:admin.registration.html.twig', $mvars), 'text/html');

				$this->sendmail($message);

				$this->addFlash('success', $this->translate('Admin.addSuccess', array(
					'%admin%' => $admin->getFullname()
				)));

				return $this->redirect($this->generateUrl('Admin__admin_edit_get', array(
					'id' => $admin->getId()
				)));
			} else {
				if ($sc->isGranted('ROLE_SUPER_SUPER_ADMIN')) {
					$adminAddForm = $this->createForm(AdminAddTForm::class, $admin, array(
						'selrole' => true
					));
				} else {
					$adminAddForm = $this->createForm(AdminAddTForm::class, $admin);
				}
				$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__admin_add'));
				$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__admin_add'));
				$this->addTwigVar('smenu_active', 'admin.add');

				return $this->render('IlcfranceWorldspeakAdminFrontBundle:Admin:add.html.twig', $this->getTwigVars());
			}
		} else {
			return $this->redirect($urlFrom);
		}
	}

	/**
	 * Get Admin Avatar
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
			$admin = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Admin')->findOneBy(array(
				'id' => $id
			));

			if (null == $admin) {
				throw new NotFoundHttpException();
			}
			$avatar = $admin->getAvatar();
			if (null == $avatar) {
				throw new NotFoundHttpException();
			}
			$response = new Response();
			$response->headers->set('Content-Type', $avatar->getMimeType());
			$response->setContent($avatar->getFile()->getBytes());

			return $response;
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTrace());

			throw new NotFoundHttpException();
		}
	}

	/**
	 * Edit Admin (method GET)
	 *
	 * @param guid $id
	 *
	 * @return RedirectResponse Response
	 */
	public function editAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Admin__admin_list');
		}
		$sc = $this->getSecurityAuthorizationChecker();
		$em = $this->getEntityManager();
		try {
			$admin = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Admin')->findOneBy(array(
				'id' => $id
			));

			if (null == $admin) {
				$this->addFlash('warning', 'Admin.editNotfound');

				return $this->redirect($urlFrom);
			}

			$superiorRole = false;
			if (!$sc->isGranted('ROLE_SUPER_SUPER_ADMIN')) {

				$roles = $admin->getAdminRoles();

				foreach ($roles as $r) {
					if ($r->getName() == "ROLE_SUPER_SUPER_ADMIN" || $r->getName() == "ROLE_SUPER_ADMIN") {
						$superiorRole = true;
					}
				}
			}
			$this->addTwigVar('superiorRole', $superiorRole);

			if ($sc->isGranted('ROLE_SUPER_ADMIN') && $superiorRole == false) {
				if ($sc->isGranted('ROLE_SUPER_SUPER_ADMIN')) {
					$adminRoleForm = $this->createForm(AdminRoleTForm::class, $admin);
					$this->addTwigVar('adminRoleForm', $adminRoleForm->createView());
				}

				$adminEmailForm = $this->createForm(AdminEmailTForm::class, $admin);
				$this->addTwigVar('adminEmailForm', $adminEmailForm->createView());

				$adminLockoutForm = $this->createForm(AdminLockoutTForm::class, $admin);
				$this->addTwigVar('adminLockoutForm', $adminLockoutForm->createView());

				$adminProfileForm = $this->createForm(AdminProfileTForm::class, $admin);
				$this->addTwigVar('adminProfileForm', $adminProfileForm->createView());

				$adminPreferedLocaleForm = $this->createForm(AdminPreferedLocaleTForm::class, $admin);
				$this->addTwigVar('adminPreferedLocaleForm', $adminPreferedLocaleForm->createView());
			}

			$this->addTwigVar('admin', $admin);
			$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__admin_edit_txt', array(
				'%admin%' => $admin->getFullname()
			)));

			$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__admin_edit', array(
				'%admin%' => $admin->getFullname()
			)));

			return $this->render('IlcfranceWorldspeakAdminFrontBundle:Admin:edit.html.twig', $this->getTwigVars());
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTrace());
		}

		return $this->redirect($urlFrom);
	}

	/**
	 * Edit Admin (method POST)
	 *
	 * @param guid $id
	 *
	 * @return RedirectResponse Response
	 */
	public function editPostAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Admin__admin_list');
		}
		$sc = $this->getSecurityAuthorizationChecker();
		$em = $this->getEntityManager();
		try {
			$admin = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Admin')->findOneBy(array(
				'id' => $id
			));

			if (null == $admin) {
				$this->addFlash('warning', 'Admin.editNotfound');

				return $this->redirect($urlFrom);
			}

			$superiorRole = false;
			if (!$sc->isGranted('ROLE_SUPER_SUPER_ADMIN')) {

				$roles = $admin->getAdminRoles();

				foreach ($roles as $r) {
					if ($r->getName() == "ROLE_SUPER_SUPER_ADMIN" || $r->getName() == "ROLE_SUPER_ADMIN") {
						$superiorRole = true;
					}
				}
			}
			$this->addTwigVar('superiorRole', $superiorRole);

			if ($sc->isGranted('ROLE_SUPER_ADMIN') && $superiorRole == false) {
				if ($sc->isGranted('ROLE_SUPER_SUPER_ADMIN')) {
					$adminRoleForm = $this->createForm(AdminRoleTForm::class, $admin);
				}
				$adminEmailForm = $this->createForm(AdminEmailTForm::class, $admin);
				$adminLockoutForm = $this->createForm(AdminLockoutTForm::class, $admin);
				$adminProfileForm = $this->createForm(AdminProfileTForm::class, $admin);
				$adminPreferedLocaleForm = $this->createForm(AdminPreferedLocaleTForm::class, $admin);

				$data = $request->request->all();
				if (isset($data['AdminRoleForm'])) {
					if ($sc->isGranted('ROLE_SUPER_SUPER_ADMIN')) {
						$adminRoleForm->handleRequest($request);
						if ($adminRoleForm->isValid()) {
							$role = $adminRoleForm['adminRoles']->getData();
							$admin->emptyRoles();
							$admin->addAdminRole($role);
							$em->persist($admin);
							$em->flush();

							$this->addFlash('success', $this->translate('Admin.editSuccessRole', array(
								'%admin%' => $admin->getFullname()
							)));

							return $this->redirect($urlFrom);
						} else {
							$em->refresh($admin);

							$this->addFlash('error', $this->translate('Admin.editErrorRole', array(
								'%admin%' => $admin->getFullname()
							)));
						}
					} else {
						$this->addFlash('error', $this->translate('Admin.editFailureHigherThanYou', array(
							'%admin%' => $admin->getFullname()
						)));
					}
				} elseif (isset($data['AdminEmailForm'])) {
					$adminEmailForm->handleRequest($request);
					if ($adminEmailForm->isValid()) {
						$em->persist($admin);
						$em->flush();

						$this->addFlash('success', $this->translate('Admin.editSuccessEmail', array(
							'%admin%' => $admin->getFullname()
						)));

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($admin);

						$this->addFlash('error', $this->translate('Admin.editErrorEmail', array(
							'%admin%' => $admin->getFullname()
						)));
					}
				} elseif (isset($data['AdminLockoutForm'])) {
					$adminLockoutForm->handleRequest($request);
					if ($adminLockoutForm->isValid()) {
						$em->persist($admin);
						$em->flush();

						$this->addFlash('success', $this->translate('Admin.editSuccessLockout', array(
							'%admin%' => $admin->getFullname()
						)));

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($admin);

						$this->addFlash('error', $this->translate('Admin.editErrorLockout', array(
							'%admin%' => $admin->getFullname()
						)));
					}
				} elseif (isset($data['AdminProfileForm'])) {
					$adminProfileForm->handleRequest($request);
					if ($adminProfileForm->isValid()) {
						$em->persist($admin);
						$em->flush();

						$this->addFlash('success', $this->translate('Admin.editSuccessProfile', array(
							'%admin%' => $admin->getFullname()
						)));

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($admin);

						$this->addFlash('error', $this->translate('Admin.editErrorProfile', array(
							'%admin%' => $admin->getFullname()
						)));
					}
				} elseif (isset($data['AdminPreferedLocaleForm'])) {
					$adminPreferedLocaleForm->handleRequest($request);
					if ($adminPreferedLocaleForm->isValid()) {
						$em->persist($admin);
						$em->flush();

						$this->addFlash('success', $this->translate('Admin.editSuccessPreferedLocale', array(
							'%admin%' => $admin->getFullname()
						)));

						return $this->redirect($urlFrom);
					} else {
						$em->refresh($admin);

						$this->addFlash('error', $this->translate('Admin.editErrorPreferedLocale', array(
							'%admin%' => $admin->getFullname()
						)));
					}
				}

				if ($sc->isGranted('ROLE_SUPER_SUPER_ADMIN')) {
					$this->addTwigVar('adminRoleForm', $adminRoleForm->createView());
				}
				$this->addTwigVar('adminEmailForm', $adminEmailForm->createView());
				$this->addTwigVar('adminLockoutForm', $adminLockoutForm->createView());
				$this->addTwigVar('adminProfileForm', $adminProfileForm->createView());
				$this->addTwigVar('adminPreferedLocaleForm', $adminPreferedLocaleForm->createView());
			} else {
				$this->addFlash('error', $this->translate('Admin.editFailureHigherThanYou', array(
					'%admin%' => $admin->getFullname()
				)));
			}

			$this->addTwigVar('tabActive', 2);
			$this->addTwigVar('admin', $admin);
			$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__admin_edit_txt', array(
				'%admin%' => $admin->getFullname()
			)));

			$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__admin_edit', array(
				'%admin%' => $admin->getFullname()
			)));

			return $this->render('IlcfranceWorldspeakAdminFrontBundle:Admin:edit.html.twig', $this->getTwigVars());
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTrace());
		}

		return $this->redirect($urlFrom);
	}

	/**
	 * Delete Admin
	 *
	 * @param guid $id
	 *
	 * @return RedirectResponse @Security("has_role('ROLE_SUPER_ADMIN')")
	 */
	public function deleteAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('Admin__admin_list'));
		}
		$sc = $this->getSecurityAuthorizationChecker();
		$em = $this->getEntityManager();
		$currentUser = $this->getSecurityTokenStorage()->getToken()->getUser();

		try {
			$admin = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Admin')->findOneBy(array(
				'id' => $id
			));

			if (null != $admin) {
				if ($currentUser->getId() == $admin->getId()) {
					$this->addFlash('error', 'Admin.deleteFailureforYourSelf');

					return $this->redirect($this->generateUrl($urlFrom));
				}
				if (!$sc->isGranted('ROLE_SUPER_SUPER_ADMIN')) {
					$superiorRole = false;
					$roles = $admin->getAdminRoles();

					foreach ($roles as $r) {
						if ($r->getName() == "ROLE_SUPER_SUPER_ADMIN" || $r->getName() == "ROLE_SUPER_ADMIN") {
							$superiorRole = true;
						}
					}

					if ($superiorRole == true) {
						$this->addFlash('error', $this->translate('Admin.deleteFailureHigherThanYou', array(
							'%admin%' => $admin->getFullname()
						)));

						return $this->redirect($this->generateUrl($urlFrom));
					}
				}

				$locale = $admin->getPreferedLocale();
				if (null != $locale) {
					$locale->removeAdmin($admin);
					$em->persist($locale);
				}
				$avatar = $admin->getAvatar();
				if (null != $avatar) {
					$dm = $this->getMongoManager();
					$dm->remove($avatar);
					$dm->flush();
				}

				$em->remove($admin);
				$em->flush();

				$this->addFlash('success', $this->translate('Admin.deleteSuccess', array(
					'%admin%' => $admin->getFullname()
				)));
			} else {
				$this->addFlash('warning', 'Admin.deleteNotfound');
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTrace());

			$this->addFlash('error', 'Admin.deleteError');
		}

		return $this->redirect($urlFrom);
	}

	/**
	 * Logs Admin (method GET)
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
			$urlFrom = $this->generateUrl('Admin__admin_list');
		}
		$em = $this->getEntityManager();
		try {
			$admin = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Admin')->findOneBy(array(
				'id' => $id
			));

			if (null == $admin) {
				$this->addFlash('warning', 'Admin.logNotfound');

				return $this->redirect($urlFrom);
			}

			$query = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:AdminLog')->getAllByAdminQuery($admin);

			$paginator = $this->get('knp_paginator');
			$pagination = $paginator->paginate($query, $page, 50);
			$pagination->setPageRange(10);

			$this->addTwigVar('admin', $admin);
			$this->addTwigVar('logs', $pagination);

			$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__admin_log_txt', array(
				'%admin%' => $admin->getFullname()
			)));

			$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__admin_log', array(
				'%admin%' => $admin->getFullname()
			)));

			return $this->render('IlcfranceWorldspeakAdminFrontBundle:Admin:logs.html.twig', $this->getTwigVars());
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTrace());
		}

		return $this->redirect($urlFrom);
	}

	/**
	 * Delete AdminLog
	 *
	 * @param guid $id
	 *
	 * @return RedirectResponse
	 */
	public function logDeleteAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Admin__admin_list');
		}
		$em = $this->getEntityManager();

		try {
			$adminLog = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:AdminLog')->findOneBy(array(
				'id' => $id
			));

			if (null != $adminLog) {
				$em->remove($adminLog);
				$em->flush();

				$this->addFlash('success', 'AdminLog.deleteSuccess');
			} else {
				$this->addFlash('warning', 'AdminLog.deleteNotfound');
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTrace());

			$this->addFlash('error', 'AdminLog.deleteError');
		}

		return $this->redirect($urlFrom);
	}
}
