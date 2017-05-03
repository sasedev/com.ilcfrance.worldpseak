<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Controller;

use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\RoleAddTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\RoleEditTForm;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Role;
use Ilcfrance\Worldspeak\Shared\ResBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Role Controller
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class RoleController extends BaseController
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->addTwigVar('menu_active', 'role');
	}

	/**
	 * Get Role with pagination 25/page
	 *
	 * @param integer $page
	 *
	 * @return Response
	 */
	public function listAction($page = 1, Request $request)
	{
		$sc = $this->getSecurityAuthorizationChecker();
		$em = $this->getEntityManager();
		$query = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Role')->getAllquery();

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator->paginate($query, $page, 25);
		$pagination->setPageRange(10);
		$this->addTwigVar('roles', $pagination);

		if ($sc->isGranted('ROLE_SUPER_SUPER_ADMIN')) {
			$role = new Role();
			$roleAddForm = $this->createForm(RoleAddTForm::class, $role);
			$this->addTwigVar('roleAddForm', $roleAddForm->createView());
		}

		$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__role_list'));
		$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__role_list'));
		$this->addTwigVar('smenu_active', 'role.list');

		return $this->render('IlcfranceWorldspeakAdminFrontBundle:Role:list.html.twig', $this->getTwigVars());
	}

	/**
	 * Add new Role (method GET)
	 *
	 * @return Response @Security("has_role('ROLE_SUPER_SUPER_ADMIN')")
	 */
	public function addAction(Request $request)
	{
		$role = new Role();
		$roleAddForm = $this->createForm(RoleAddTForm::class, $role);

		$this->addTwigVar('roleAddForm', $roleAddForm->createView());
		$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__role_add'));
		$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__role_add'));
		$this->addTwigVar('smenu_active', 'role.add');

		return $this->render('IlcfranceWorldspeakAdminFrontBundle:Role:add.html.twig', $this->getTwigVars());
	}

	/**
	 * Add new Role (method POST)
	 *
	 * @return RedirectResponse Response
	 */
	public function addPostAction(Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('Admin__role_add_get'));
		}
		$role = new Role();
		$roleAddForm = $this->createForm(RoleAddTForm::class, $role);
		$data = $request->request->all();
		if (isset($data['RoleAddForm'])) {
			$roleAddForm->handleRequest($request);

			if ($roleAddForm->isValid()) {
				$em = $this->getEntityManager();
				$em->persist($role);
				$em->flush();
				$this->addFlash('success', $this->translate('Role.addSuccess', array(
					'%role%' => $role->getName()
				)));

				return $this->redirect($this->generateUrl('Admin__role_edit_get', array(
					'id' => $role->getId()
				)));
			} else {
				$this->addTwigVar('roleAddForm', $roleAddForm->createView());
				$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__role_add'));
				$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__role_add'));
				$this->addTwigVar('smenu_active', 'role.add');

				return $this->render('IlcfranceWorldspeakAdminFrontBundle:Role:add.html.twig', $this->getTwigVars());
			}
		} else {
			return $this->redirect($urlFrom);
		}
	}

	/**
	 * Edit Role (method GET)
	 *
	 * @param guid $id
	 *
	 * @return RedirectResponse Response
	 */
	public function editAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Admin__role_list');
		}
		$sc = $this->getSecurityAuthorizationChecker();
		$em = $this->getEntityManager();
		try {
			$role = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Role')->findOneBy(array(
				'id' => $id
			));

			if (null == $role) {
				$this->addFlash('warning', 'Role.editNotfound');

				return $this->redirect($urlFrom);
			}

			if ($sc->isGranted('ROLE_SUPER_SUPER_ADMIN')) {
				$roleEditForm = $this->createForm(RoleEditTForm::class, $role);
				$this->addTwigVar('roleEditForm', $roleEditForm->createView());
			}

			$this->addTwigVar('role', $role);
			$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__role_edit_txt', array(
				'%role%' => $role->getName()
			)));

			$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__role_edit', array(
				'%role%' => $role->getName()
			)));

			return $this->render('IlcfranceWorldspeakAdminFrontBundle:Role:edit.html.twig', $this->getTwigVars());
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTrace());
		}

		return $this->redirect($urlFrom);
	}

	/**
	 * Edit Role (method POST)
	 *
	 * @param guid $id
	 *
	 * @return RedirectResponse Response
	 */
	public function editPostAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('Admin__role_edit_get', array(
				'id' => $id
			)));
		}
		$em = $this->getEntityManager();
		try {
			$role = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Role')->findOneBy(array(
				'id' => $id
			));

			if (null == $role) {
				$this->addFlash('warning', 'Role.editNotfound');

				return $this->redirect($urlFrom);
			}
			$roleEditForm = $this->createForm(RoleEditTForm::class, $role);
			;
			$data = $request->request->all();
			if (isset($data['RoleEditForm'])) {
				$roleEditForm->handleRequest($request);

				if ($roleEditForm->isValid()) {
					$em->persist($role);
					$em->flush();
					$this->addFlash('success', $this->translate('Role.editSuccess', array(
						'%role%' => $role->getName()
					)));

					return $this->redirect($this->generateUrl('Admin__role_edit_get', array(
						'id' => $role->getId()
					)));
				} else {
					$this->addTwigVar('tabActive', 2);
					$em->refresh($role);

					$this->addTwigVar('role', $role);
					$this->addTwigVar('roleEditForm', $roleEditForm->createView());
					$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__role_edit_txt', array(
						'%role%' => $role->getName()
					)));

					$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__role_edit', array(
						'%role%' => $role->getName()
					)));

					return $this->render('IlcfranceWorldspeakAdminFrontBundle:Role:edit.html.twig', $this->getTwigVars());
				}
			} else {
				return $this->redirect($urlFrom);
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTrace());

			$this->addFlash('error', 'Role.editError');
		}

		return $this->redirect($urlFrom);
	}

	/**
	 * Delete Role
	 *
	 * @param guid $id
	 *
	 * @return RedirectResponse @Security("has_role('ROLE_SUPER_SUPER_ADMIN')")
	 */
	public function deleteAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('Admin__role_list'));
		}
		$em = $this->getEntityManager();
		try {
			$role = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Role')->findOneBy(array(
				'id' => $id
			));

			if (null != $role) {
				foreach ($role->getAdmins() as $admin) {
					$admin->setPreferedRole(null);
					$em->persist($admin);
				}
				foreach ($role->getTeachers() as $teacher) {
					$teacher->setPreferedRole(null);
					$em->persist($teacher);
				}
				foreach ($role->getTrainees() as $trainee) {
					$trainee->setPreferedRole(null);
					$em->persist($trainee);
				}
				$em->remove($role);
				$em->flush();

				$this->addFlash('success', $this->translate('Role.deleteSuccess', array(
					'%role%' => $role->getName()
				)));
			} else {
				$this->addFlash('warning', 'Role.deleteNotfound');
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage() . ' ' . $e->getTrace());

			$this->addFlash('error', 'Role.deleteError');
		}

		return $this->redirect($urlFrom);
	}
}
