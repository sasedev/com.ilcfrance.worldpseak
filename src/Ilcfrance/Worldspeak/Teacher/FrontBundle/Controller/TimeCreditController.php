<?php
namespace Ilcfrance\Worldspeak\Teacher\FrontBundle\Controller;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TimeCredit;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TimeCreditDocument;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Trainee;
use Ilcfrance\Worldspeak\Teacher\FrontBundle\Form\TimeCreditCefEndTForm;
use Ilcfrance\Worldspeak\Teacher\FrontBundle\Form\TimeCreditDocumentAddTForm;
use Ilcfrance\Worldspeak\Teacher\FrontBundle\Form\TimeCreditEndReportTForm;
use Ilcfrance\Worldspeak\Shared\ResBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * TimeCredit Controller
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TimeCreditController extends BaseController
{

	/**
	 * Edit TimeCredit (method GET)
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
			$timeCredit = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCredit')->findOneBy(array(
				'id' => $id
			));

			if (null == $timeCredit) {
				$this->addFlash('warning', 'TimeCredit.editNotfound');

				return $this->redirect($urlFrom);
			}

			$timeCreditCefEndForm = $this->createForm(TimeCreditCefEndTForm::class, $timeCredit);
			$timeCreditEndReportForm = $this->createForm(TimeCreditEndReportTForm::class, $timeCredit);

			$this->addTwigVar('timeCredit', $timeCredit);

			$this->addTwigVar('timeCreditCefEndForm', $timeCreditCefEndForm->createView());
			$this->addTwigVar('timeCreditEndReportForm', $timeCreditEndReportForm->createView());

			$timeCreditDocument = new TimeCreditDocument();
			$timeCreditDocument->setNotifyByMail(TimeCreditDocument::NOTIFYBYMAIL_SENT);
			$timeCreditDocument->setTimeCredit($timeCredit);
			$timeCreditDocumentAddForm = $this->createForm(TimeCreditDocumentAddTForm::class, $timeCreditDocument, array(
				'level' => $timeCredit->getLevel()
			));

			$this->addTwigVar('timeCreditDocumentAddForm', $timeCreditDocumentAddForm->createView());

			$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleTeacher__timeCredit_edit_txt', array(
				'%totalHours%' => $timeCredit->getTotalHours(),
				'%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
				'%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
				'%trainee%' => $timeCredit->getTrainee()->getFullname()
			)));

			$this->addTwigVar('pagetitle', $this->translate('_pagetitleTeacher__timeCredit_edit', array(
				'%totalHours%' => $timeCredit->getTotalHours(),
				'%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
				'%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
				'%trainee%' => $timeCredit->getTrainee()->getFullname()
			)));

			return $this->render('IlcfranceWorldspeakTeacherFrontBundle:TimeCredit:edit.html.twig', $this->getTwigVars());
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage());
		}

		return $this->redirect($urlFrom);
	}

	/**
	 * Edit TimeCredit (method Post)
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
			$timeCredit = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCredit')->findOneBy(array(
				'id' => $id
			));

			if (null == $timeCredit) {
				$this->addFlash('warning', 'TimeCredit.editNotfound');

				return $this->redirect($urlFrom);
			}

			$timeCreditCefEndForm = $this->createForm(TimeCreditCefEndTForm::class, $timeCredit);
			$timeCreditEndReportForm = $this->createForm(TimeCreditEndReportTForm::class, $timeCredit);

			;
			$data = $request->request->all();
			if (isset($data['TimeCreditCefEndForm'])) {
				$timeCreditCefEndForm->handleRequest($request);
				if ($timeCreditCefEndForm->isValid()) {
					$em->persist($timeCredit);
					$em->flush();

					$this->addFlash('success', $this->translate('TimeCredit.editSuccessCefEnd', array(
						'%totalHours%' => $timeCredit->getTotalHours(),
						'%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
						'%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
						'%trainee%' => $timeCredit->getTrainee()->getFullname()
					)));

					return $this->redirect($urlFrom);
				} else {
					$em->refresh($timeCredit);

					$this->addFlash('error', $this->translate('TimeCredit.editErrorCefEnd', array(
						'%totalHours%' => $timeCredit->getTotalHours(),
						'%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
						'%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
						'%trainee%' => $timeCredit->getTrainee()->getFullname()
					)));
				}
			} elseif (isset($data['TimeCreditEndReportForm'])) {
				$timeCreditEndReportForm->handleRequest($request);
				if ($timeCreditEndReportForm->isValid()) {
					$em->persist($timeCredit);
					$em->flush();

					$this->addFlash('success', $this->translate('TimeCredit.editSuccessEndReport', array(
						'%totalHours%' => $timeCredit->getTotalHours(),
						'%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
						'%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
						'%trainee%' => $timeCredit->getTrainee()->getFullname()
					)));

					return $this->redirect($urlFrom);
				} else {
					$em->refresh($timeCredit);

					$this->addFlash('error', $this->translate('TimeCredit.editErrorEndReport', array(
						'%totalHours%' => $timeCredit->getTotalHours(),
						'%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
						'%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
						'%trainee%' => $timeCredit->getTrainee()->getFullname()
					)));
				}
			}

			$this->addTwigVar('timeCredit', $timeCredit);

			$this->addTwigVar('timeCreditCefEndForm', $timeCreditCefEndForm->createView());
			// $this->addTwigVar('timeCreditDetailsForm', $timeCreditDetailsForm->createView());
			$this->addTwigVar('timeCreditEndReportForm', $timeCreditEndReportForm->createView());

			$timeCreditDocument = new TimeCreditDocument();
			$timeCreditDocument->setNotifyByMail(TimeCreditDocument::NOTIFYBYMAIL_SENT);
			$timeCreditDocument->setTimeCredit($timeCredit);
			$timeCreditDocumentAddForm = $this->createForm(TimeCreditDocumentAddTForm::class, $timeCreditDocument, array(
				'level' => $timeCredit->getLevel()
			));

			$this->addTwigVar('timeCreditDocumentAddForm', $timeCreditDocumentAddForm->createView());

			$this->addTwigVar('tabActive', 2);
			$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleTeacher__timeCredit_edit_txt', array(
				'%totalHours%' => $timeCredit->getTotalHours(),
				'%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
				'%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
				'%trainee%' => $timeCredit->getTrainee()->getFullname()
			)));

			$this->addTwigVar('pagetitle', $this->translate('_pagetitleTeacher__timeCredit_edit', array(
				'%totalHours%' => $timeCredit->getTotalHours(),
				'%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
				'%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel()),
				'%trainee%' => $timeCredit->getTrainee()->getFullname()
			)));

			return $this->render('IlcfranceWorldspeakTeacherFrontBundle:TimeCredit:edit.html.twig', $this->getTwigVars());
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage());
		}

		return $this->redirect($urlFrom);
	}
}
