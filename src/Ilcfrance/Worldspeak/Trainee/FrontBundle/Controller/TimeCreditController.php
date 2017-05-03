<?php
namespace Ilcfrance\Worldspeak\Trainee\FrontBundle\Controller;

use Ilcfrance\Worldspeak\Trainee\FrontBundle\Form\SurveyBeginTForm;
use Ilcfrance\Worldspeak\Trainee\FrontBundle\Form\SurveyEndTForm;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TimeCredit;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TraineeLog;
use Ilcfrance\Worldspeak\Shared\ResBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TimeCreditController extends BaseController
{

	/**
	 * Class Constructor
	 */
	public function __construct()
	{
		$this->addTwigVar('menu_active', '');
	}

	public function reportAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Trainee__default_homepage');
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

			$this->addTwigVar('timeCredit', $timeCredit);

			$this->addTwigVar('pagetitle', $this->translate('TimeCredit.report.pdf'));

			$pdf = $this->get('white_october.tcpdf')->create();

			$pdf->SetCreator('Salah Abdelkader Seif Eddine');
			$pdf->SetAuthor('ILCFrance - WorldSpeak');
			$pdf->SetTitle($this->translate('TimeCredit.report') . ' ' . $timeCredit->getTrainee()->getFullname());
			$pdf->SetSubject($this->translate('TimeCredit.report') . ' ' . $timeCredit->getTrainee()->getFullname());

			$pdf->SetHeaderData(null, 0, 'ILC WorldSpeak - ' . $this->translate('TimeCredit.report'), $timeCredit->getTrainee()->getFullname(), array(
				0,
				32,
				255
			), array(
				0,
				32,
				128
			));
			$pdf->setFooterData(array(
				0,
				64,
				0
			), array(
				0,
				64,
				128
			));

			// set header and footer fonts
			$pdf->setHeaderFont(array(
				PDF_FONT_NAME_MAIN,
				'',
				PDF_FONT_SIZE_MAIN
			));
			$pdf->setFooterFont(array(
				PDF_FONT_NAME_DATA,
				'',
				PDF_FONT_SIZE_DATA
			));

			// set default monospaced font
			$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

			// set margins
			$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
			$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
			$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

			// set auto page breaks
			$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

			// set image scale factor
			$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

			$pdf->setFontSubsetting(true);

			// Set font
			// dejavusans is a UTF-8 Unicode font, if you only need to
			// print standard ASCII chars, you can use core fonts like
			// helvetica or times to reduce file size.
			$pdf->SetFont('helvetica', '', 11, '', true);

			// Add a page
			// This method has several options, check the source code documentation for more information.
			$pdf->AddPage();

			$html = $this->renderView('IlcfranceWorldspeakSharedResBundle:TimeCredit:report1.html.twig', $this->getTwigVars());

			$pdf->writeHTML($html, true, false, true, false, ''); // */

			$pdf->AddPage();

			$html = $this->renderView('IlcfranceWorldspeakSharedResBundle:TimeCredit:report2.html.twig', $this->getTwigVars());

			$pdf->writeHTML($html, true, false, true, false, '');

			$pdf->lastPage();

			$response = new Response($pdf->Output('ILCWorldSpeak_Formation_' . $timeCredit->getTrainee()->getFullname() . '.pdf"', 'D'));
			$response->headers->set('Content-Type', 'application/pdf');
			$response->headers->set('Content-Disposition', 'attachment; filename="Formation ' . $timeCredit->getTrainee()->getFullname() . '.pdf"');
			$response->headers->set('Pragma', 'public');
			$response->headers->set('Cache-Control', 'maxage=1');

			return $response;
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage());
		}

		return $this->redirect($urlFrom);
	}

	public function surveyBeginAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Trainee__default_homepage');
		}

		$em = $this->getEntityManager();

		try {
			$timeCredit = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCredit')->findOneBy(array(
				'id' => $id
			));

			if (null == $timeCredit) {
				$this->addFlash('warning', 'TimeCredit.notFound');
				return $this->redirect($urlFrom);
			}

			$trainee = $this->getSecurityTokenStorage()->getToken()->getUser();
			if ($trainee->getId() != $timeCredit->getTrainee()->getId()) {
				$this->addFlash('warning', 'TimeCredit.notOwner');
				return $this->redirect($urlFrom);
			}

			$surveyBeginForm = $this->createForm(SurveyBeginTForm::class, $timeCredit);

			$this->addTwigVar('timeCredit', $timeCredit);

			$this->addTwigVar('surveyBeginForm', $surveyBeginForm->createView());

			$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleTrainee__timeCredit_surveyBegin_txt', array(
				'%totalHours%' => $timeCredit->getTotalHours(),
				'%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
				'%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel())
			)));

			$this->addTwigVar('pagetitle', $this->translate('_pagetitleTrainee__timeCredit_surveyBegin', array(
				'%totalHours%' => $timeCredit->getTotalHours(),
				'%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
				'%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel())
			)));

			return $this->render('IlcfranceWorldspeakTraineeFrontBundle:TimeCredit:surveyBegin.html.twig', $this->getTwigVars());
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getMessage());
		}

		return $this->redirect($urlFrom);
	}

	public function surveyBeginPostAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Trainee__default_homepage');
		}

		$em = $this->getEntityManager();
		$trainee = $this->getSecurityTokenStorage()->getToken()->getUser();

		try {
			$timeCredit = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCredit')->findOneBy(array(
				'id' => $id
			));

			if (null == $timeCredit) {
				$traineeLog = new TraineeLog();
				$traineeLog->setTrainee($trainee);
				$traineeLog->setMsg("Echec SurveyBegin " . $this->translate('TimeCredit.notFound'));
				$em->persist($traineeLog);
				$em->flush();

				$this->addFlash('warning', 'TimeCredit.notFound');
				return $this->redirect($urlFrom);
			}

			if ($trainee->getId() != $timeCredit->getTrainee()->getId()) {
				$traineeLog = new TraineeLog();
				$traineeLog->setTrainee($trainee);
				$traineeLog->setMsg("Echec SurveyBegin " . $this->translate('TimeCredit.notOwner'));
				$em->persist($traineeLog);
				$em->flush();

				$this->addFlash('warning', 'TimeCredit.notOwner');
				return $this->redirect($urlFrom);
			}

			$surveyBeginForm = $this->createForm(SurveyBeginTForm::class, $timeCredit);

			;
			$data = $request->request->all();
			if (isset($data['SurveyBeginForm'])) {
				$surveyBeginForm->handleRequest($request);

				if ($surveyBeginForm->isValid()) {
					$timeCredit->setSurveyBegin(TimeCredit::SURVEY_FILLED);
					$em->persist($timeCredit);
					$em->flush();

					$traineeLog = new TraineeLog();
					$traineeLog->setTrainee($trainee);
					$traineeLog->setMsg("Success SurveyBegin");
					$em->persist($traineeLog);
					$em->flush();

					$this->addFlash('success', 'TimeCredit.successSurveyBegin');
					return $this->redirect($this->generateUrl('Trainee__default_homepage'));
				}
			}

			$traineeLog = new TraineeLog();
			$traineeLog->setTrainee($trainee);
			$traineeLog->setMsg("Echec SurveyBegin Form invalid");
			$em->persist($traineeLog);
			$em->flush();

			$this->addTwigVar('timeCredit', $timeCredit);

			$this->addTwigVar('surveyBeginForm', $surveyBeginForm->createView());

			$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleTrainee__timeCredit_surveyBegin_txt', array(
				'%totalHours%' => $timeCredit->getTotalHours(),
				'%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
				'%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel())
			)));

			$this->addTwigVar('pagetitle', $this->translate('_pagetitleTrainee__timeCredit_surveyBegin', array(
				'%totalHours%' => $timeCredit->getTotalHours(),
				'%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
				'%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel())
			)));

			return $this->render('IlcfranceWorldspeakTraineeFrontBundle:TimeCredit:surveyBegin.html.twig', $this->getTwigVars());
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getMessage());

			$traineeLog = new TraineeLog();
			$traineeLog->setTrainee($trainee);
			$traineeLog->setMsg("Echec SurveyBegin " . $e->getMessage());
			$em->persist($traineeLog);
			$em->flush();
		}

		return $this->redirect($urlFrom);
	}

	public function surveyEndAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Trainee__default_homepage');
		}

		$em = $this->getEntityManager();

		try {
			$timeCredit = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCredit')->findOneBy(array(
				'id' => $id
			));

			if (null == $timeCredit) {
				$this->addFlash('warning', 'TimeCredit.notFound');
				return $this->redirect($urlFrom);
			}

			$trainee = $this->getSecurityTokenStorage()->getToken()->getUser();
			if ($trainee->getId() != $timeCredit->getTrainee()->getId()) {
				$this->addFlash('warning', 'TimeCredit.notOwner');
				return $this->redirect($urlFrom);
			}

			$surveyEndForm = $this->createForm(SurveyEndTForm::class, $timeCredit);

			$this->addTwigVar('timeCredit', $timeCredit);

			$this->addTwigVar('surveyEndForm', $surveyEndForm->createView());

			$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleTrainee__timeCredit_surveyEnd_txt', array(
				'%totalHours%' => $timeCredit->getTotalHours(),
				'%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
				'%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel())
			)));

			$this->addTwigVar('pagetitle', $this->translate('_pagetitleTrainee__timeCredit_surveyEnd', array(
				'%totalHours%' => $timeCredit->getTotalHours(),
				'%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
				'%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel())
			)));

			return $this->render('IlcfranceWorldspeakTraineeFrontBundle:TimeCredit:surveyEnd.html.twig', $this->getTwigVars());
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getMessage());
		}

		return $this->redirect($urlFrom);
	}

	public function surveyEndPostAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Trainee__default_homepage');
		}

		$em = $this->getEntityManager();
		$trainee = $this->getSecurityTokenStorage()->getToken()->getUser();

		try {
			$timeCredit = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCredit')->findOneBy(array(
				'id' => $id
			));

			if (null == $timeCredit) {
				$traineeLog = new TraineeLog();
				$traineeLog->setTrainee($trainee);
				$traineeLog->setMsg("Echec SurveyEnd " . $this->translate('TimeCredit.notFound'));
				$em->persist($traineeLog);
				$em->flush();

				$this->addFlash('warning', 'TimeCredit.notFound');
				return $this->redirect($urlFrom);
			}

			if ($trainee->getId() != $timeCredit->getTrainee()->getId()) {
				$traineeLog = new TraineeLog();
				$traineeLog->setTrainee($trainee);
				$traineeLog->setMsg("Echec SurveyEnd " . $this->translate('TimeCredit.notOwner'));
				$em->persist($traineeLog);
				$em->flush();

				$this->addFlash('warning', 'TimeCredit.notOwner');
				return $this->redirect($urlFrom);
			}

			$surveyEndForm = $this->createForm(SurveyEndTForm::class, $timeCredit);

			;
			$data = $request->request->all();
			if (isset($data['SurveyEndForm'])) {
				$surveyEndForm->handleRequest($request);

				if ($surveyEndForm->isValid()) {
					$timeCredit->setSurveyEnd(TimeCredit::SURVEY_FILLED);
					$em->persist($timeCredit);
					$em->flush();

					$traineeLog = new TraineeLog();
					$traineeLog->setTrainee($trainee);
					$traineeLog->setMsg("Success SurveyEnd");
					$em->persist($traineeLog);
					$em->flush();

					$this->addFlash('success', 'TimeCredit.successSurveyEnd');
					return $this->redirect($this->generateUrl('Trainee__default_homepage'));
				}
			}

			$traineeLog = new TraineeLog();
			$traineeLog->setTrainee($trainee);
			$traineeLog->setMsg("Echec SurveyEnd Form invalid");
			$em->persist($traineeLog);
			$em->flush();

			$this->addTwigVar('timeCredit', $timeCredit);

			$this->addTwigVar('surveyEndForm', $surveyEndForm->createView());

			$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleTrainee__timeCredit_surveyEnd_txt', array(
				'%totalHours%' => $timeCredit->getTotalHours(),
				'%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
				'%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel())
			)));

			$this->addTwigVar('pagetitle', $this->translate('_pagetitleTrainee__timeCredit_surveyEnd', array(
				'%totalHours%' => $timeCredit->getTotalHours(),
				'%ftype%' => $this->translate('TimeCredit.ftype.' . $timeCredit->getFtype()),
				'%level%' => $this->translate('TimeCredit.level.' . $timeCredit->getLevel())
			)));

			return $this->render('IlcfranceWorldspeakTraineeFrontBundle:TimeCredit:surveyEnd.html.twig', $this->getTwigVars());
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getMessage());

			$traineeLog = new TraineeLog();
			$traineeLog->setTrainee($trainee);
			$traineeLog->setMsg("Echec SurveyEnd " . $e->getMessage());
			$em->persist($traineeLog);
			$em->flush();
		}

		return $this->redirect($urlFrom);
	}
}
