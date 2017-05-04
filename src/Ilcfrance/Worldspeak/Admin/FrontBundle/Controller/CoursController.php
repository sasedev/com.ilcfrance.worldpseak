<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Controller;

use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\CoursCorrectionTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\CoursKpiTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\CoursPhoneTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\CoursStatusTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\CoursTeacherTForm;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\CoursTypeTForm;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Cours;
use Ilcfrance\Worldspeak\Shared\ResBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Cours Controller
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class CoursController extends BaseController
{

	/**
	 * Class Constructor
	 */
	public function __construct()
	{
		$this->addTwigVar('menu_active', 'trainee');
	}

	/**
	 * Get Cours with pagination 10/page
	 *
	 * @param integer $page
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function listAction($page = 1, Request $request)
	{
		$em = $this->getEntityManager();
		$query = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Cours')->getAllQuery(false);

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator->paginate($query, $page, 10);
		$pagination->setPageRange(10);

		$this->addTwigVar('courses', $pagination);
		$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__cours_list'));
		$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__cours_list'));
		$this->addTwigVar('smenu_active', 'cours.list');

		return $this->render('IlcfranceWorldspeakAdminFrontBundle:Cours:list.html.twig', $this->getTwigVars());
	}

	/**
	 * Get Cours Buggy with pagination 10/page
	 *
	 * @param integer $page
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function listBuggyAction($page = 1, Request $request)
	{
		$em = $this->getEntityManager();
		$query = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Cours')->getAllBuggyQuery(false);

		$paginator = $this->get('knp_paginator');
		$pagination = $paginator->paginate($query, $page, 10);
		$pagination->setPageRange(10);

		$this->addTwigVar('courses', $pagination);
		$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__cours_listBuggy'));
		$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__cours_listBuggy'));
		$this->addTwigVar('smenu_active', 'cours.listBuggy');

		return $this->render('IlcfranceWorldspeakAdminFrontBundle:Cours:listBuggy.html.twig', $this->getTwigVars());
	}

	/**
	 * Edit Cours Action
	 *
	 * @param guid $id
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function downloadAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Admin__default_homepage');
		}

		$em = $this->getEntityManager();

		try {
			$cours = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Cours')->findOneBy(array(
				'id' => $id
			));

			if (null == $cours) {
				return $this->redirect($urlFrom);
			}

			$dateFormatter = $this->get('ilc_france_worldspeak_shared_res.date_formatter');

			$this->addTwigVar('cours', $cours);

			$title = $this->translate('Cours.pdf', array(
				'%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
				'%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
				'%duration%' => $cours->getDuration()
			));

			$this->addTwigVar('pagetitle', $title);

			$pdf = $this->get('white_october.tcpdf')->create();

			$pdf->SetCreator('Salah Abdelkader Seif Eddine');
			$pdf->SetAuthor('ILCFrance - WorldSpeak');
			$pdf->SetTitle($title . ' - ' . $cours->getTimeCredit()->getTrainee()->getFullname());
			$pdf->SetSubject($title . ' - ' . $cours->getTimeCredit()->getTrainee()->getFullname());

			$pdf->SetHeaderData(null, 0, 'ILC WorldSpeak - ' . $title, $cours->getTimeCredit()->getTrainee()->getFullname(), array(
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
			$pdf->SetFont('helvetica', '', 12, '', true);

			// Add a page
			// This method has several options, check the source code documentation for more information.
			$pdf->AddPage();

			$html = $this->renderView('IlcfranceWorldspeakSharedResBundle:Cours:correction.html.twig', $this->getTwigVars());

			$pdf->writeHTML($html, true, false, true, false, ''); // */

			$pdf->lastPage();

			$filename = $this->normalize('ILCWorldSpeak - ' . $title . ' - ' . $cours->getTimeCredit()->getTrainee()->getFullname());

			$response = new Response($pdf->Output($filename . '.pdf"', 'D'));
			// $response = new Response($pdf->Output($filename.'.pdf"'));
			$response->headers->set('Content-Type', 'application/pdf');
			$response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '.pdf"');
			$response->headers->set('Pragma', 'public');
			$response->headers->set('Cache-Control', 'maxage=1');

			return $response;
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage());

			return $this->redirect($urlFrom);
		}
	}

	/**
	 * Edit Cours (method GET)
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
			$cours = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Cours')->findOneBy(array(
				'id' => $id
			));

			if (null == $cours) {
				$this->addFlash('warning', 'Cours.editNotfound');

				return $this->redirect($urlFrom);
			}
			/*
			 * if(null != $cours->getTeacher() && (null == $cours->getPhone() || trim($cours->getPhone()) == '')) {
			 * $cours->setPhone($cours->getTeacher()->getCoursPhone());
			 * }
			 */

			$coursCorrectionForm = $this->createForm(CoursCorrectionTForm::class, $cours);
			$coursKpiForm = $this->createForm(CoursKpiTForm::class, $cours);
			$coursPhoneForm = $this->createForm(CoursPhoneTForm::class, $cours);
			$coursStatusForm = $this->createForm(CoursStatusTForm::class, $cours);
			$coursTeacherForm = $this->createForm(CoursTeacherTForm::class, $cours);
			$coursTypeForm = $this->createForm(CoursTypeTForm::class, $cours);

			$dateFormatter = $this->get('ilc_france_worldspeak_shared_res.date_formatter');

			$this->addTwigVar('cours', $cours);

			$this->addTwigVar('coursCorrectionForm', $coursCorrectionForm->createView());
			$this->addTwigVar('coursKpiForm', $coursKpiForm->createView());
			$this->addTwigVar('coursPhoneForm', $coursPhoneForm->createView());
			$this->addTwigVar('coursStatusForm', $coursStatusForm->createView());
			$this->addTwigVar('coursTeacherForm', $coursTeacherForm->createView());
			$this->addTwigVar('coursTypeForm', $coursTypeForm->createView());

			$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__cours_edit_txt', array(
				'%trainee%' => $cours->getTimeCredit()->getTrainee()->getFullname(),
				'%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
				'%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
				'%duration%' => $cours->getDuration()
			)));

			$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__cours_edit', array(
				'%trainee%' => $cours->getTimeCredit()->getTrainee()->getFullname(),
				'%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
				'%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
				'%duration%' => $cours->getDuration()
			)));

			return $this->render('IlcfranceWorldspeakAdminFrontBundle:Cours:edit.html.twig', $this->getTwigVars());
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage());
		}

		return $this->redirect($urlFrom);
	}

	/**
	 * Edit Cours (method POST)
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
			$cours = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Cours')->findOneBy(array(
				'id' => $id
			));

			if (null == $cours) {
				$this->addFlash('warning', 'Cours.editNotfound');

				return $this->redirect($urlFrom);
			}

			$oldStatus = $cours->getStatus();

			$coursCorrectionForm = $this->createForm(CoursCorrectionTForm::class, $cours);
			$coursKpiForm = $this->createForm(CoursKpiTForm::class, $cours);
			$coursPhoneForm = $this->createForm(CoursPhoneTForm::class, $cours);
			$coursStatusForm = $this->createForm(CoursStatusTForm::class, $cours);
			$coursTeacherForm = $this->createForm(CoursTeacherTForm::class, $cours);
			$coursTypeForm = $this->createForm(CoursTypeTForm::class, $cours);

			$dateFormatter = $this->get('ilc_france_worldspeak_shared_res.date_formatter');

			$data = $request->request->all();
			if (isset($data['CoursCorrectionForm'])) {
				$coursCorrectionForm->handleRequest($request);
				if ($coursCorrectionForm->isValid()) {
					$em->persist($cours);
					$em->flush();

					$this->addFlash('success', $this->translate('Cours.editSuccessCorrection', array(
						'%trainee%' => $cours->getTimeCredit()->getTrainee()->getFullname(),
						'%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
						'%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
						'%duration%' => $cours->getDuration()
					)));

					return $this->redirect($urlFrom);
				} else {
					$em->refresh($cours);

					$this->addFlash('error', $this->translate('Cours.editErrorCorrection', array(
						'%trainee%' => $cours->getTimeCredit()->getTrainee()->getFullname(),
						'%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
						'%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
						'%duration%' => $cours->getDuration()
					)));
				}
			} elseif (isset($data['CoursKpiForm'])) {
				$coursKpiForm->handleRequest($request);
				if ($coursKpiForm->isValid()) {
					$em->persist($cours);
					$em->flush();

					$this->addFlash('success', $this->translate('Cours.editSuccessKpi', array(
						'%trainee%' => $cours->getTimeCredit()->getTrainee()->getFullname(),
						'%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
						'%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
						'%duration%' => $cours->getDuration()
					)));

					return $this->redirect($urlFrom);
				} else {
					$em->refresh($cours);

					$this->addFlash('error', $this->translate('Cours.editErrorKpi', array(
						'%trainee%' => $cours->getTimeCredit()->getTrainee()->getFullname(),
						'%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
						'%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
						'%duration%' => $cours->getDuration()
					)));
				}
			} elseif (isset($data['CoursPhoneForm'])) {
				$coursPhoneForm->handleRequest($request);
				if ($coursPhoneForm->isValid()) {
					$em->persist($cours);
					$em->flush();

					$this->addFlash('success', $this->translate('Cours.editSuccessPhone', array(
						'%trainee%' => $cours->getTimeCredit()->getTrainee()->getFullname(),
						'%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
						'%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
						'%duration%' => $cours->getDuration()
					)));

					return $this->redirect($urlFrom);
				} else {
					$em->refresh($cours);

					$this->addFlash('error', $this->translate('Cours.editErrorPhone', array(
						'%trainee%' => $cours->getTimeCredit()->getTrainee()->getFullname(),
						'%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
						'%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
						'%duration%' => $cours->getDuration()
					)));
				}
			} elseif (isset($data['CoursStatusForm'])) {
				$coursStatusForm->handleRequest($request);
				if ($coursStatusForm->isValid()) {
					$timeCredit = $cours->getTimeCredit();

					if ($oldStatus == $cours::STATUS_ABSENT) {
						$timeCredit->setLostHours($timeCredit->getLostHours() - (1 * $cours->getDuration() / 60));
					}
					if ($oldStatus == Cours::STATUS_DONE) {
						$timeCredit->setDoneHours($timeCredit->getDoneHours() - (1 * $cours->getDuration() / 60));
					}
					if ($oldStatus == Cours::STATUS_PLANNED || $oldStatus == Cours::STATUS_PLANNED_PENDING) {

						$timeCredit->setReservedHours($timeCredit->getReservedHours() - (1 * $cours->getDuration() / 60));
					}

					if ($cours->getStatus() == $cours::STATUS_ABSENT) {
						$timeCredit->setLostHours($timeCredit->getLostHours() + (1 * $cours->getDuration() / 60));
					}
					if ($cours->getStatus() == Cours::STATUS_DONE) {
						$timeCredit->setDoneHours($timeCredit->getDoneHours() + (1 * $cours->getDuration() / 60));
					}
					if ($cours->getStatus() == Cours::STATUS_PLANNED || $cours->getStatus() == Cours::STATUS_PLANNED_PENDING) {

						$timeCredit->setReservedHours($timeCredit->getReservedHours() + (1 * $cours->getDuration() / 60));
					}

					$em->persist($timeCredit);

					$em->persist($cours);
					$em->flush();

					$this->addFlash('success', $this->translate('Cours.editSuccessStatus', array(
						'%trainee%' => $cours->getTimeCredit()->getTrainee()->getFullname(),
						'%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
						'%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
						'%duration%' => $cours->getDuration()
					)));

					return $this->redirect($urlFrom);
				} else {
					$em->refresh($cours);

					$this->addFlash('error', $this->translate('Cours.editErrorStatus', array(
						'%trainee%' => $cours->getTimeCredit()->getTrainee()->getFullname(),
						'%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
						'%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
						'%duration%' => $cours->getDuration()
					)));
				}
			} elseif (isset($data['CoursTeacherForm'])) {
				$coursTeacherForm->handleRequest($request);
				if ($coursTeacherForm->isValid()) {
					$teacher = $cours->getTeacher();
					$cours->setPhone($teacher->getCoursPhone());
					$em->persist($cours);
					$em->flush();

					$this->addFlash('success', $this->translate('Cours.editSuccessTeacher', array(
						'%trainee%' => $cours->getTimeCredit()->getTrainee()->getFullname(),
						'%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
						'%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
						'%duration%' => $cours->getDuration()
					)));

					return $this->redirect($urlFrom);
				} else {
					$em->refresh($cours);

					$this->addFlash('error', $this->translate('Cours.editErrorTeacher', array(
						'%trainee%' => $cours->getTimeCredit()->getTrainee()->getFullname(),
						'%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
						'%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
						'%duration%' => $cours->getDuration()
					)));
				}
			} elseif (isset($data['CoursTypeForm'])) {
				$coursTypeForm->handleRequest($request);
				if ($coursTypeForm->isValid()) {
					$em->persist($cours);
					$em->flush();

					$this->addFlash('success', $this->translate('Cours.editSuccessType', array(
						'%trainee%' => $cours->getTimeCredit()->getTrainee()->getFullname(),
						'%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
						'%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
						'%duration%' => $cours->getDuration()
					)));

					return $this->redirect($urlFrom);
				} else {
					$em->refresh($cours);

					$this->addFlash('error', $this->translate('Cours.editErrorType', array(
						'%trainee%' => $cours->getTimeCredit()->getTrainee()->getFullname(),
						'%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
						'%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
						'%duration%' => $cours->getDuration()
					)));
				}
			} else {
				$this->addFlash('error', $this->translate('Cours.editErrorUnknown', array(
					'%trainee%' => $cours->getTimeCredit()->getTrainee()->getFullname(),
					'%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
					'%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
					'%duration%' => $cours->getDuration()
				)));
			}

			/*
			 * if(null != $cours->getTeacher() && (null == $cours->getPhone() || trim($cours->getPhone()) == '')) {
			 * $cours->setPhone($cours->getTeacher()->getCoursPhone());
			 * }
			 */

			$this->addTwigVar('tabActive', 2);
			$this->addTwigVar('cours', $cours);

			$this->addTwigVar('coursCorrectionForm', $coursCorrectionForm->createView());
			$this->addTwigVar('coursKpiForm', $coursKpiForm->createView());
			$this->addTwigVar('coursPhoneForm', $coursPhoneForm->createView());
			$this->addTwigVar('coursStatusForm', $coursStatusForm->createView());
			$this->addTwigVar('coursTeacherForm', $coursTeacherForm->createView());
			$this->addTwigVar('coursTypeForm', $coursTypeForm->createView());

			$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleAdmin__cours_edit_txt', array(
				'%trainee%' => $cours->getTimeCredit()->getTrainee()->getFullname(),
				'%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
				'%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
				'%duration%' => $cours->getDuration()
			)));

			$this->addTwigVar('pagetitle', $this->translate('_pagetitleAdmin__cours_edit', array(
				'%trainee%' => $cours->getTimeCredit()->getTrainee()->getFullname(),
				'%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
				'%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
				'%duration%' => $cours->getDuration()
			)));

			return $this->render('IlcfranceWorldspeakAdminFrontBundle:Cours:edit.html.twig', $this->getTwigVars());
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage());
		}

		return $this->redirect($urlFrom);
	}

	/**
	 * Delete Cours
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
			$cours = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Cours')->findOneBy(array(
				'id' => $id
			));

			if (null != $cours) {

				$teacher = $cours->getTeacher();
				if (null != $teacher) {
					$teacher->removeCours($cours);
					$em->persist($teacher);
				}
				$timeCredit = $cours->getTimeCredit();

				if ($cours->getStatus() == $cours::STATUS_ABSENT) {
					$timeCredit->setLostHours($timeCredit->getLostHours() - (1 * $cours->getDuration() / 60));
				}
				if ($cours->getStatus() == Cours::STATUS_DONE) {
					$timeCredit->setDoneHours($timeCredit->getDoneHours() - (1 * $cours->getDuration() / 60));
				}
				if ($cours->getStatus() == Cours::STATUS_PLANNED || $cours->getStatus() == Cours::STATUS_PLANNED_PENDING) {

					$timeCredit->setReservedHours($timeCredit->getReservedHours() - (1 * $cours->getDuration() / 60));
				}

				$timeCredit->removeCours($cours);
				$em->remove($cours);
				$em->persist($timeCredit);

				$em->flush();

				$dateFormatter = $this->get('ilc_france_worldspeak_shared_res.date_formatter');

				$this->addFlash('success', $this->translate('Cours.deleteSuccess', array(
					'%trainee%' => $cours->getTimeCredit()->getTrainee()->getFullname(),
					'%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
					'%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
					'%duration%' => $cours->getDuration()
				)));
			} else {
				$this->addFlash('warning', 'Cours.deleteNotfound');
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage());
			$this->addFlash('error', 'Cours.deleteError');
		}

		return $this->redirect($urlFrom);
	}
}
