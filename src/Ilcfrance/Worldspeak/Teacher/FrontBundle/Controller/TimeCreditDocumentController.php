<?php
namespace Ilcfrance\Worldspeak\Teacher\FrontBundle\Controller;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TimeCredit;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TimeCreditDocument;
use Ilcfrance\Worldspeak\Teacher\FrontBundle\Form\TimeCreditCefEndTForm;
use Ilcfrance\Worldspeak\Teacher\FrontBundle\Form\TimeCreditDocumentAddTForm;
use Ilcfrance\Worldspeak\Teacher\FrontBundle\Form\TimeCreditDocumentEditTForm;
use Ilcfrance\Worldspeak\Teacher\FrontBundle\Form\TimeCreditEndReportTForm;
use Ilcfrance\Worldspeak\Shared\ResBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * TimeCreditDocument Controller
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TimeCreditDocumentController extends BaseController
{

	/**
	 * Add new TimeCreditDocument (method POST)
	 */
	public function addPostAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Teacher__default_homepage');
		}

		if ($this->endswith($urlFrom, $this->generateUrl('Teacher__timeCreditDocument_add_post', array(
			'id' => $id
		)))) {
			$urlFrom = $this->generateUrl('Teacher__timeCredit_edit_get', array(
				'id' => $id
			));
		}
		$em = $this->getEntityManager();
		try {
			$timeCredit = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCredit')->findOneBy(array(
				'id' => $id
			));

			if (null == $timeCredit) {
				$this->addFlash('warning', 'TimeCredit.editNotfound');

				return $this->redirect($urlFrom);
			}

			$timeCreditCefEndForm = $this->createForm(TimeCreditCefEndTForm::class, $timeCredit);
			$timeCreditEndReportForm = $this->createForm(TimeCreditEndReportTForm::class, $timeCredit);

			$timeCreditDocument = new TimeCreditDocument();
			$timeCreditDocument->setNotifyByMail(TimeCreditDocument::NOTIFYBYMAIL_SENT);
			$timeCreditDocument->setTimeCredit($timeCredit);
			$timeCreditDocumentAddForm = $this->createForm(TimeCreditDocumentAddTForm::class, $timeCreditDocument, array(
				'level' => $timeCredit->getLevel()
			));

			;
			$data = $request->request->all();
			if (isset($data['TimeCreditDocumentAddForm'])) {
				$trainee = $timeCredit->getTrainee();

				$timeCreditDocumentAddForm->handleRequest($request);
				if ($timeCreditDocumentAddForm->isValid()) {
					$em->persist($timeCreditDocument);
					$em->flush();

					if ($timeCreditDocument->getNotifyByMail() == TimeCreditDocument::NOTIFYBYMAIL_SENT && null != $trainee->getEmail() && trim($trainee->getEmail()) != '') {

						$locale = null;
						if (null != $trainee->getPreferedLocale()) {
							$locale = $trainee->getPreferedLocale()->getPrefix();
						}
						$mvars = array();
						$mvars['timeCreditDocument'] = $timeCreditDocument;
						$mvars['userPreferedLocale'] = $locale;
						$from = $this->getParameter('mail_from');
						$fromName = $this->getParameter('mail_from_name');
						$subject = $this->translate('_mail.new_timeCreditDocument_subject', array(), null, $locale);

						$message = \Swift_Message::newInstance()->setFrom($from, $fromName)->setTo($trainee->getEmail(), $trainee->getFullname())->setSubject($subject)->setBody($this->renderView('IlcfranceWorldspeakSharedResBundle:Mail:' . 'trainee.new_timeCreditDocument.html.twig', $mvars), 'text/html');

						$this->sendmail($message);

						$this->addFlash('success', $this->translate('TimeCreditDocument.addSuccessWithMail', array(
							'%totalHours%' => $timeCredit->getTotalHours(),
							'%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
							'%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
							'%trainee%' => $trainee->getFullname()
						)));
					} else {
						$this->addFlash('success', $this->translate('TimeCreditDocument.addSuccess', array(
							'%totalHours%' => $timeCredit->getTotalHours(),
							'%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
							'%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
							'%trainee%' => $trainee->getFullname()
						)));
					}

					return $this->redirect($urlFrom);
				} else {
					$this->addFlash('error', $this->translate('TimeCreditDocument.addErrorInvalid'));
				}
			} else {
				$this->addFlash('error', $this->translate('TimeCreditDocument.addErrorUnknown'));
			}

			$this->addTwigVar('timeCredit', $timeCredit);

			$this->addTwigVar('timeCreditCefEndForm', $timeCreditCefEndForm->createView());
			$this->addTwigVar('timeCreditEndReportForm', $timeCreditEndReportForm->createView());
			$this->addTwigVar('timeCreditDocumentAddForm', $timeCreditDocumentAddForm->createView());

			$this->addTwigVar('tabActive', 4);
			$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleTeacher__timeCredit_edit_txt', array(
				'%totalHours%' => $timeCredit->getTotalHours(),
				'%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
				'%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
				'%trainee%' => $trainee->getFullname()
			)));

			$this->addTwigVar('pagetitle', $this->translate('_pagetitleTeacher__timeCredit_edit', array(
				'%totalHours%' => $timeCredit->getTotalHours(),
				'%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
				'%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
				'%trainee%' => $trainee->getFullname()
			)));

			return $this->render('IlcfranceWorldspeakTeacherFrontBundle:TimeCredit:edit.html.twig', $this->getTwigVars());
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage());
		}

		return $this->redirect($urlFrom);
	}

	/**
	 * notifyByMail TimeCredit (method GET)
	 *
	 * @param guid $id
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
	 */
	public function notifyByMailAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Teacher__default_homepage');
		}
		$em = $this->getEntityManager();
		try {
			$timeCreditDocument = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCreditDocument')->findOneBy(array(
				'id' => $id
			));

			if (null == $timeCreditDocument) {
				$this->addFlash('warning', 'TimeCreditDocument.mailNotfound');

				return $this->redirect($urlFrom);
			}
			$timeCredit = $timeCreditDocument->getTimeCredit();
			$trainee = $timeCredit->getTrainee();

			if (null != $trainee->getEmail() && trim($trainee->getEmail()) != '') {

				$locale = null;
				if (null != $trainee->getPreferedLocale()) {
					$locale = $trainee->getPreferedLocale()->getPrefix();
				}
				$mvars = array();
				$mvars['timeCreditDocument'] = $timeCreditDocument;
				$mvars['userPreferedLocale'] = $locale;
				$from = $this->getParameter('mail_from');
				$fromName = $this->getParameter('mail_from_name');
				$subject = $this->translate('_mail.new_timeCreditDocument_subject', array(), null, $locale);

				$message = \Swift_Message::newInstance()->setFrom($from, $fromName)->setTo($trainee->getEmail(), $trainee->getFullname())->setSubject($subject)->setBody($this->renderView('IlcfranceWorldspeakSharedResBundle:Mail:trainee.new_timeCreditDocument.html.twig', $mvars), 'text/html');

				$this->sendmail($message);

				$timeCreditDocument->setNotifyByMail(TimeCreditDocument::NOTIFYBYMAIL_SENT);
				$em->persist($timeCreditDocument);
				$em->flush();

				$this->addFlash('success', $this->translate('TimeCreditDocument.mailSuccess', array(
					'%totalHours%' => $timeCredit->getTotalHours(),
					'%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
					'%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
					'%trainee%' => $trainee->getFullname()
				)));
			} else {

				$this->addFlash('error', $this->translate('TimeCreditDocument.mailErrorNotValid', array(
					'%totalHours%' => $timeCredit->getTotalHours(),
					'%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
					'%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
					'%trainee%' => $trainee->getFullname()
				)));
			}
		} catch (\Exception $e) {
			$this->addFlash('error', $this->translate('TimeCreditDocument.mailErrorUnknown'));
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage());
		}

		return $this->redirect($urlFrom);
	}

	/**
	 * Download TeachingResource From TimeCreditDocument by id
	 *
	 * @param guid $id
	 *
	 * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function downloadAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Teacher__default_homepage');
		}
		$em = $this->getEntityManager();

		try {
			$timeCreditDocument = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCreditDocument')->findOneBy(array(
				'id' => $id
			));

			if (null != $timeCreditDocument) {
				if (null != $timeCreditDocument->getTeachingResource()) {
					$response = new Response();
					$response->headers->set('Cache-Control', 'private');
					$response->headers->set('Content-type', $timeCreditDocument->getTeachingResource()->getMimeType());
					$response->headers->set('Content-Disposition', 'attachment; filename="' . $timeCreditDocument->getTeachingResource()->getFilename() . '"');

					$response->headers->set('Content-length', $timeCreditDocument->getTeachingResource()->getLength());
					// Send headers before outputting anything
					$response->sendHeaders();

					$response->setContent($timeCreditDocument->getTeachingResource()->getFile()->getBytes());

					return $response;
				} else {
					$this->addFlash('warning', 'TimeCreditDocument.downloadNotFile');
				}
			} else {
				$this->addFlash('warning', 'TimeCreditDocument.downloadNotfound');
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage());
			$this->addFlash('error', 'TimeCreditDocument.downloadError');
		}

		return $this->redirect($urlFrom);
	}

	/**
	 * Edit TimeCreditDocument (Method GET)
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
			$timeCreditDocument = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCreditDocument')->findOneBy(array(
				'id' => $id
			));

			if (null == $timeCreditDocument) {
				$this->addFlash('warning', 'TimeCreditDocument.editNotFound');

				return $this->redirect($urlFrom);
			}
			$timeCredit = $timeCreditDocument->getTimeCredit();
			$timeCreditDocumentEditForm = $this->createForm(TimeCreditDocumentEditTForm::class, $timeCreditDocument, array(
				'level' => $timeCredit->getLevel()
			));

			$this->addTwigVar('timeCreditDocument', $timeCreditDocument);
			$this->addTwigVar('timeCreditDocumentEditForm', $timeCreditDocumentEditForm->createView());

			$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleTeacher__timeCreditDocument_edit_txt', array(
				'%totalHours%' => $timeCredit->getTotalHours(),
				'%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
				'%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
				'%trainee%' => $timeCredit->getTrainee()->getFullname()
			)));

			$this->addTwigVar('pagetitle', $this->translate('_pagetitleTeacher__timeCreditDocument_edit', array(
				'%totalHours%' => $timeCredit->getTotalHours(),
				'%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
				'%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
				'%trainee%' => $timeCredit->getTrainee()->getFullname()
			)));

			return $this->render('IlcfranceWorldspeakTeacherFrontBundle:TimeCreditDocument:edit.html.twig', $this->getTwigVars());
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage());
			$this->addFlash('error', 'TimeCreditDocument.editError');
		}

		return $this->redirect($urlFrom);
	}

	/**
	 * Edit TimeCreditDocument (Method POST)
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
			$timeCreditDocument = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCreditDocument')->findOneBy(array(
				'id' => $id
			));

			if (null == $timeCreditDocument) {
				$this->addFlash('warning', 'TimeCreditDocument.editNotFound');

				return $this->redirect($urlFrom);
			}
			$timeCredit = $timeCreditDocument->getTimeCredit();
			$trainee = $timeCredit->getTrainee();
			$timeCreditDocumentEditForm = $this->createForm(TimeCreditDocumentEditTForm::class, $timeCreditDocument, array(
				'level' => $timeCredit->getLevel()
			));

			;
			$data = $request->request->all();
			if (isset($data['TimeCreditDocumentEditForm'])) {
				$timeCreditDocumentEditForm->handleRequest($request);
				if ($timeCreditDocumentEditForm->isValid()) {
					$em->persist($timeCreditDocument);
					$em->flush();
					$this->addFlash('success', $this->translate('TimeCreditDocument.editSuccess', array(
						'%totalHours%' => $timeCredit->getTotalHours(),
						'%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
						'%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
						'%trainee%' => $trainee->getFullname()
					)));

					return $this->redirect($urlFrom);
				} else {
					$this->addFlash('error', $this->translate('TimeCreditDocument.editError'));
				}
			} else {
				$this->addFlash('error', $this->translate('TimeCreditDocument.editError'));
			}

			$this->addTwigVar('tabActive', 2);
			$this->addTwigVar('timeCreditDocument', $timeCreditDocument);
			$this->addTwigVar('timeCreditDocumentEditForm', $timeCreditDocumentEditForm->createView());

			$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleTeacher__timeCreditDocument_edit_txt', array(
				'%totalHours%' => $timeCredit->getTotalHours(),
				'%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
				'%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
				'%trainee%' => $timeCredit->getTrainee()->getFullname()
			)));

			$this->addTwigVar('pagetitle', $this->translate('_pagetitleTeacher__timeCreditDocument_edit', array(
				'%totalHours%' => $timeCredit->getTotalHours(),
				'%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
				'%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
				'%trainee%' => $timeCredit->getTrainee()->getFullname()
			)));

			return $this->render('IlcfranceWorldspeakTeacherFrontBundle:TimeCreditDocument:edit.html.twig', $this->getTwigVars());
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage());
			$this->addFlash('error', 'TimeCreditDocument.editError');
		}

		return $this->redirect($urlFrom);
	}

	/**
	 * Delete TimeCreditDocument
	 *
	 * @param guid $id
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('Teacher__default_homepage'));
		}
		$em = $this->getEntityManager();

		try {
			$timeCreditDocument = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCreditDocument')->findOneBy(array(
				'id' => $id
			));

			if (null != $timeCreditDocument) {
				$timeCredit = $timeCreditDocument->getTimeCredit();

				$em->remove($timeCreditDocument);
				$em->flush();

				$this->addFlash('success', $this->translate('TimeCreditDocument.deleteSuccess', array(
					'%totalHours%' => $timeCredit->getTotalHours(),
					'%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
					'%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
					'%trainee%' => $timeCredit->getTrainee()->getFullname()
				)));
			} else {
				$this->addFlash('warning', 'TimeCreditDocument.deleteNotfound');
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage());
			$this->addFlash('error', 'TimeCreditDocument.deleteError');
		}

		return $this->redirect($urlFrom);
	}
}
