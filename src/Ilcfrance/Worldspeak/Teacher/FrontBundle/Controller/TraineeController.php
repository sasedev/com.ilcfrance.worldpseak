<?php
namespace Ilcfrance\Worldspeak\Teacher\FrontBundle\Controller;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Trainee;
use Ilcfrance\Worldspeak\Teacher\FrontBundle\Form\TraineeEmailTForm;
use Ilcfrance\Worldspeak\Teacher\FrontBundle\Form\TraineeProfileTForm;
use Ilcfrance\Worldspeak\Teacher\FrontBundle\Form\TraineeProfileAdvancedTForm;
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
			$urlFrom = $this->generateUrl('Teacher__default_homepage');
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

			$traineeEmailForm = $this->createForm(TraineeEmailTForm::class, $trainee);
			$this->addTwigVar('traineeEmailForm', $traineeEmailForm->createView());

			$traineeProfileForm = $this->createForm(TraineeProfileTForm::class, $trainee);
			$this->addTwigVar('traineeProfileForm', $traineeProfileForm->createView());

			$traineeProfileAdvancedForm = $this->createForm(TraineeProfileAdvancedTForm::class, $trainee);
			$this->addTwigVar('traineeProfileAdvancedForm', $traineeProfileAdvancedForm->createView());

			$timeCredits = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCredit')->getAllByTrainee($trainee);

			$this->addTwigVar('timeCredits', $timeCredits);

			$this->addTwigVar('trainee', $trainee);
			$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleTeacher__trainee_edit_txt', array(
				'%trainee%' => $trainee->getFullname()
			)));

			$this->addTwigVar('pagetitle', $this->translate('_pagetitleTeacher__trainee_edit', array(
				'%trainee%' => $trainee->getFullname()
			)));

			return $this->render('IlcfranceWorldspeakTeacherFrontBundle:Trainee:edit.html.twig', $this->getTwigVars());
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
			$urlFrom = $this->generateUrl('Teacher__default_homepage');
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

			$traineeEmailForm = $this->createForm(TraineeEmailTForm::class, $trainee);

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
			}

			$timeCredits = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCredit')->getAllByTrainee($trainee);

			$this->addTwigVar('timeCredits', $timeCredits);

			$this->addTwigVar('traineeEmailForm', $traineeEmailForm->createView());
			$this->addTwigVar('traineeProfileForm', $traineeProfileForm->createView());
			$this->addTwigVar('traineeProfileAdvancedForm', $traineeProfileAdvancedForm->createView());

			$this->addTwigVar('tabActive', 2);
			$this->addTwigVar('trainee', $trainee);
			$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleTeacher__trainee_edit_txt', array(
				'%trainee%' => $trainee->getFullname()
			)));

			$this->addTwigVar('pagetitle', $this->translate('_pagetitleTeacher__trainee_edit', array(
				'%trainee%' => $trainee->getFullname()
			)));

			return $this->render('IlcfranceWorldspeakTeacherFrontBundle:Trainee:edit.html.twig', $this->getTwigVars());
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage());
		}

		return $this->redirect($urlFrom);
	}
}
