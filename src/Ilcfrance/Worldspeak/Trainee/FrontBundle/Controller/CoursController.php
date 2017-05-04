<?php
namespace Ilcfrance\Worldspeak\Trainee\FrontBundle\Controller;

use Ilcfrance\Worldspeak\Shared\DataBundle\Document\TraineeFile;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Cours;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TraineeLog;
use Ilcfrance\Worldspeak\Shared\ResBundle\Controller\BaseController;
use Ilcfrance\Worldspeak\Trainee\FrontBundle\Form\CoursDocumentAddTForm;
use Symfony\Component\HttpFoundation\JsonResponse;
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
	 * Constructor
	 */
	public function __construct()
	{
		$this->addTwigVar('menu_active', '');
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
			$urlFrom = $this->generateUrl('Trainee__default_homepage');
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
	 * Edit Cours Action
	 *
	 * @param guid $id
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function editAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Trainee__default_homepage');
		}

		$em = $this->getEntityManager();

		try {
			$cours = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Cours')->findOneBy(array(
				'id' => $id
			));

			if (null == $cours) {
				return $this->redirect($urlFrom);
			}

			$traineeFile = new TraineeFile();
			$coursDocumentAddForm = $this->createForm(CoursDocumentAddTForm::class, $traineeFile);

			$this->addTwigVar('coursDocumentAddForm', $coursDocumentAddForm->createView());

			$dateFormatter = $this->get('ilc_france_worldspeak_shared_res.date_formatter');

			$this->addTwigVar('cours', $cours);

			$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitleTrainee__cours_edit_txt', array(
				'%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
				'%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
				'%duration%' => $cours->getDuration()
			)));

			$this->addTwigVar('pagetitle', $this->translate('_pagetitleTrainee__cours_edit', array(
				'%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
				'%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
				'%duration%' => $cours->getDuration()
			)));

			return $this->render('IlcfranceWorldspeakTraineeFrontBundle:Cours:edit.html.twig', $this->getTwigVars());
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getLine() . ' ' . $e->getMessage());

			return $this->redirect($urlFrom);
		}
	}

	/**
	 * Ajax Add Cours
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse \Symfony\Component\HttpFoundation\Response
	 *         \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function ajaxAddAction(Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('Trainee__default_homepage'));
		}

		;

		if ($request->isXmlHttpRequest()) {
			$currentUser = $this->getSecurityTokenStorage()->getToken()->getUser();

			$dtStart = new \DateTime();
			$dtStart->setTimestamp($request->request->get('start') / 1000);

			$dateFormatter = $this->get('ilc_france_worldspeak_shared_res.date_formatter');

			$em = $this->getEntityManager();
			$response = new Response();
			try {
				// verifier si la date de début est valide
				$nowP1d = new \DateTime('now');
				$nowP1d->modify('+1 day');

				if ($dtStart < $nowP1d) {
					$traineeLog = new TraineeLog();
					$traineeLog->setTrainee($currentUser);
					$traineeLog->setMsg("Echec d'ajout de Cours du " . $dateFormatter->format($dtStart, 'long', 'medium') . " : " . $this->translate('Cours.addErrorDtStartInvalid'));
					$em->persist($traineeLog);
					$em->flush();

					$response->setStatusCode(419);
					$response->setContent($this->translate('Cours.addErrorDtStartInvalid'));

					return $response;
				}

				$timeCredit = null;
				$timeCredits = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCredit')->getAllByTrainee($currentUser);

				// verifier s'il a des Credits
				if (count($timeCredits) == 0) {
					$traineeLog = new TraineeLog();
					$traineeLog->setTrainee($currentUser);
					$traineeLog->setMsg("Echec d'ajout de Cours du " . $dateFormatter->format($dtStart, 'long', 'medium') . " : " . $this->translate('Cours.addErrorTimeCreditNotfound'));
					$em->persist($traineeLog);
					$em->flush();

					$response->setStatusCode(404);
					$response->setContent($this->translate('Cours.addErrorTimeCreditNotfound'));

					return $response;
				}

				// verifier si le Credit a des heures libres

				$hasFreeTimeCredits = false;

				foreach ($timeCredits as $timeCreditTest) {
					if ($timeCreditTest->getNotPlanifiedHours() != 0) {
						$hasFreeTimeCredits = true;
						$timeCredit = $timeCreditTest;
					}
				}

				if ($hasFreeTimeCredits == false) {
					$traineeLog = new TraineeLog();
					$traineeLog->setTrainee($currentUser);
					$traineeLog->setMsg("Echec d'ajout de Cours du " . $dateFormatter->format($dtStart, 'long', 'medium') . " : " . $this->translate('Cours.addErrorTimeCreditNotfree'));
					$em->persist($traineeLog);
					$em->flush();

					$response->setStatusCode(419);
					$response->setContent($this->translate('Cours.addErrorTimeCreditNotfree'));

					return $response;
				}

				// Verifier si la deadLine n'est pas dépassée
				$now = new \DateTime('now');

				if ((null != $timeCredit->getDeadLine()) && ($now > $timeCredit->getDeadLine() || $dtStart > $timeCredit->getDeadLine())) {

					$traineeLog = new TraineeLog();
					$traineeLog->setTrainee($currentUser);
					$traineeLog->setMsg("Echec d'ajout de Cours du " . $dateFormatter->format($dtStart, 'long', 'medium') . " : " . $this->translate('Cours.addErrorTimeCreditDeadLine'));
					$em->persist($traineeLog);
					$em->flush();

					$response->setStatusCode(419);
					$response->setContent($this->translate('Cours.addErrorTimeCreditDeadLine'));

					return $response;
				}

				// verifier s'il n'a pas de Cours en meme temps
				$coursInTheSameTime = false;
				$myCourses = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Cours')->getAllByTrainee($currentUser, false);

				foreach ($myCourses as $cCours) {
					$dtStart1 = new \DateTime('now');
					$dtStart1->setTimestamp($cCours->getDtStart()->getTimestamp());

					$dtStart2 = new \DateTime('now');
					$dtStart2->setTimestamp($cCours->getDtStart()->getTimestamp());
					$dtStart2 = $dtStart2->modify('+30 minutes');

					$dtStart3 = new \DateTime('now');
					$dtStart3->setTimestamp($cCours->getDtStart()->getTimestamp());
					$dtStart3 = $dtStart3->modify('-30 minutes');

					if ($dtStart == $dtStart1 || $dtStart == $dtStart2 || $dtStart == $dtStart3) {
						$coursInTheSameTime = true;
					}
				}

				if ($coursInTheSameTime == true) {
					$traineeLog = new TraineeLog();
					$traineeLog->setTrainee($currentUser);
					$traineeLog->setMsg("Echec d'ajout de Cours du " . $dateFormatter->format($dtStart, 'long', 'medium') . " : " . $this->translate('Cours.addErrorSameTime'));
					$em->persist($traineeLog);
					$em->flush();

					$response->setStatusCode(419);
					$response->setContent($this->translate('Cours.addErrorSameTime'));

					return $response;
				}

				// detecter les présences de formateurs au moment supposé du cours
				$teacherAvailabilities = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TeacherAvailability')->getAllByDate($dtStart, false);

				if (count($teacherAvailabilities) == 0) {
					$traineeLog = new TraineeLog();
					$traineeLog->setTrainee($currentUser);
					$traineeLog->setMsg("Echec d'ajout de Cours du " . $dateFormatter->format($dtStart, 'long', 'medium') . " : " . $this->translate('Cours.addErrorNoTeacherAvailabilies'));
					$em->persist($traineeLog);
					$em->flush();

					$response->setStatusCode(419);
					$response->setContent($this->translate('Cours.addErrorNoTeacherAvailabilies'));

					return $response;
				}

				// verifier si les formateurs présents n'ont pas de cours au meme moment
				$freeTeachers = array();

				foreach ($teacherAvailabilities as $teacherAvailability) {
					$teacher = $teacherAvailability->getTeacher();
					$courses = $teacher->getCourses();
					$teacherFound = false;

					if (null == $courses || count($courses) == 0) {
						$freeTeachers[] = $teacher;
					} else {
						$teacherFound = true;

						foreach ($courses as $cCours) {
							$dtStart1 = new \DateTime('now');
							$dtStart1->setTimestamp($cCours->getDtStart()->getTimestamp());

							$dtStart2 = new \DateTime('now');
							$dtStart2->setTimestamp($cCours->getDtStart()->getTimestamp());
							$dtStart2 = $dtStart2->modify('+30 minutes');

							$dtStart3 = new \DateTime('now');
							$dtStart3->setTimestamp($cCours->getDtStart()->getTimestamp());
							$dtStart3 = $dtStart3->modify('-30 minutes');

							if ($dtStart == $dtStart1 || $dtStart == $dtStart2 || $dtStart == $dtStart3) {
								$teacherFound = false;
							}
						}

						if ($teacherFound == true) {
							$freeTeachers[] = $teacher;
						}
					}
				}

				if (count($freeTeachers) == 0) {
					$traineeLog = new TraineeLog();
					$traineeLog->setTrainee($currentUser);
					$traineeLog->setMsg("Echec d'ajout de Cours du " . $dateFormatter->format($dtStart, 'long', 'medium') . " : " . $this->translate('Cours.addErrorNoTeacherFound'));
					$em->persist($traineeLog);
					$em->flush();

					$response->setStatusCode(419);
					$response->setContent($this->translate('Cours.addErrorNoTeacherFound'));

					return $response;
				}

				$currentTeacher = $freeTeachers[0];
				$cours = new Cours();
				$cours->setTimeCredit($timeCredit);
				$cours->setTeacher($currentTeacher);
				$cours->setPhone($currentTeacher->getCoursPhone());
				$cours->setDtStart($dtStart);
				$cours->setStatus(Cours::STATUS_PLANNED);
				$cours->setType(Cours::TYPE_UNDEFINED);

				$timeCredit->setReservedHours($timeCredit->getReservedHours() + 1);
				$em->persist($cours);
				$em->persist($timeCredit);
				$em->flush();

				$traineeLog = new TraineeLog();
				$traineeLog->setTrainee($currentUser);
				$traineeLog->setMsg("Succes d'ajout de Cours du " . $dateFormatter->format($cours->getDtStart(), 'long', 'medium'));
				$em->persist($traineeLog);
				$em->flush();

				$jsonResponse = new JsonResponse();
				$jsonResponse->setData(array(
					'id' => $cours->getId(),
					'title' => $this->translate('Cours'),
					'backgroundColor' => $cours->getBackgroundColor(),
					'borderColor' => $cours->getBorderColor(),
					'textColor' => $cours->getTextColor(),
					'allDay' => $cours->getAllDay(),
					'dtStart' => $cours->getEvStart(),
					'dtEnd' => $cours->getEvEnd(),
					'msg' => $this->translate('Cours.addSuccess_txt', array(
						'%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
						'%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
						'%duration%' => $cours->getDuration()
					))
				));

				return $jsonResponse;
			} catch (\Exception $e) {
				$logger = $this->getLogger();
				$logger->addError($e->getLine() . ' ' . $e->getFile() . ' ' . $e->getMessage());

				$traineeLog = new TraineeLog();
				$traineeLog->setTrainee($currentUser);
				$traineeLog->setMsg("Echec d'ajout de Cours du " . $dateFormatter->format($dtStart, 'long', 'medium') . " : " . $e->getMessage());
				$em->persist($traineeLog);
				$em->flush();

				$response->setStatusCode(415);
				$response->setContent($this->translate('Cours.addFailureInvalid'));

				return $response;
			}
		}

		return $this->redirect($urlFrom);
	}

	/**
	 * Ajax Edit Cours
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse \Symfony\Component\HttpFoundation\Response
	 */
	public function ajaxEditAction(Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('Trainee__default_homepage'));
		}

		;

		if ($request->isXmlHttpRequest()) {
			$currentUser = $this->getSecurityTokenStorage()->getToken()->getUser();
			$id = $request->request->get('cId');
			$dtStart = new \DateTime();
			$dtStart->setTimestamp($request->request->get('start') / 1000);
			$dateFormatter = $this->get('ilc_france_worldspeak_shared_res.date_formatter');

			$em = $this->getEntityManager();
			$response = new Response();
			try {
				$cours = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Cours')->findOneBy(array(
					'id' => $id
				));

				// verifier si le cours existe
				if (null == $cours) {
					$traineeLog = new TraineeLog();
					$traineeLog->setTrainee($currentUser);
					$traineeLog->setMsg("Echec modif de Cours " . $this->translate('Cours.editErrorNotFound'));
					$em->persist($traineeLog);
					$em->flush();

					$response->setStatusCode(404);
					$response->setContent($this->translate('Cours.editErrorNotFound'));

					return $response;
				}

				// verifier si l'utilisateur est bien le propriétaire du cours
				if ($currentUser->getId() != $cours->getTimeCredit()->getTrainee()->getId()) {
					$traineeLog = new TraineeLog();
					$traineeLog->setTrainee($currentUser);
					$traineeLog->setMsg("Echec modif de Cours du " . $dateFormatter->format($cours->getDtStart(), 'long', 'medium') . " : " . $this->translate('Cours.editErrorNotOwner'));
					$em->persist($traineeLog);
					$em->flush();

					$response->setStatusCode(403);
					$response->setContent($this->translate('Cours.editErrorNotOwner'));

					return $response;
				}

				// verifier si le cours est modifiable
				if ($cours->getEditable() == false) {
					$traineeLog = new TraineeLog();
					$traineeLog->setTrainee($currentUser);
					$traineeLog->setMsg("Echec modif de Cours du " . $dateFormatter->format($cours->getDtStart(), 'long', 'medium') . " : " . $this->translate('Cours.editErrorNotEditable'));
					$em->persist($traineeLog);
					$em->flush();

					$response->setStatusCode(419);
					$response->setContent($this->translate('Cours.editErrorNotEditable'));

					return $response;
				}

				// verifier si la date de début est valide
				$nowP1d = new \DateTime('now');
				$nowP1d->modify('+1 day');

				if ($dtStart < $nowP1d) {
					$traineeLog = new TraineeLog();
					$traineeLog->setTrainee($currentUser);
					$traineeLog->setMsg("Echec modif de Cours du " . $dateFormatter->format($cours->getDtStart(), 'long', 'medium') . " : " . $this->translate('Cours.editErrorDtStartInvalid'));
					$em->persist($traineeLog);
					$em->flush();

					$response->setStatusCode(419);
					$response->setContent($this->translate('Cours.editErrorDtStartInvalid'));

					return $response;
				}

				// Verifier si la deadLine n'est pas dépassée
				$timeCredit = $cours->getTimeCredit();
				$now = new \DateTime('now');

				if (null != $timeCredit->getDeadLine() && ($now > $timeCredit->getDeadLine() || $dtStart > $timeCredit->getDeadLine())) {
					$traineeLog = new TraineeLog();
					$traineeLog->setTrainee($currentUser);
					$traineeLog->setMsg("Echec modif de Cours du " . $dateFormatter->format($cours->getDtStart(), 'long', 'medium') . " : " . $this->translate('Cours.editErrorTimeCreditDeadLine'));
					$em->persist($traineeLog);
					$em->flush();

					$response->setStatusCode(419);
					$response->setContent($this->translate('Cours.editErrorTimeCreditDeadLine'));

					return $response;
				}

				// verifier s'il n'a pas de Cours en meme temps
				$coursInTheSameTime = false;
				$myCourses = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Cours')->getAllByTrainee($currentUser, false);

				foreach ($myCourses as $cCours) {
					$dtStart1 = new \DateTime('now');
					$dtStart1->setTimestamp($cCours->getDtStart()->getTimestamp());

					$dtStart2 = new \DateTime('now');
					$dtStart2->setTimestamp($cCours->getDtStart()->getTimestamp());
					$dtStart2 = $dtStart2->modify('+30 minutes');

					$dtStart3 = new \DateTime('now');
					$dtStart3->setTimestamp($cCours->getDtStart()->getTimestamp());
					$dtStart3 = $dtStart3->modify('-30 minutes');

					if (($dtStart == $dtStart1 || $dtStart == $dtStart2 || $dtStart == $dtStart3) && ($cCours->getId() != $id)) {

						$coursInTheSameTime = true;
					}
				}

				if ($coursInTheSameTime == true) {
					$traineeLog = new TraineeLog();
					$traineeLog->setTrainee($currentUser);
					$traineeLog->setMsg("Echec modif de Cours du " . $dateFormatter->format($cours->getDtStart(), 'long', 'medium') . " : " . $this->translate('Cours.editErrorSameTime'));
					$em->persist($traineeLog);
					$em->flush();

					$response->setStatusCode(419);
					$response->setContent($this->translate('Cours.editErrorSameTime'));

					return $response;
				}

				// detecter les présences de formateurs au moment supposé du cours
				$teacherAvailabilities = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TeacherAvailability')->getAllByDate($dtStart, false);

				if (count($teacherAvailabilities) == 0) {
					$traineeLog = new TraineeLog();
					$traineeLog->setTrainee($currentUser);
					$traineeLog->setMsg("Echec modif de Cours du " . $dateFormatter->format($cours->getDtStart(), 'long', 'medium') . " : " . $this->translate('Cours.editErrorNoTeacherAvailabilies'));
					$em->persist($traineeLog);
					$em->flush();

					$response->setStatusCode(419);
					$response->setContent($this->translate('Cours.editErrorNoTeacherAvailabilies'));

					return $response;
				}

				// verifier si les formateurs présents n'ont pas de cours au meme moment
				$freeTeachers = array();
				foreach ($teacherAvailabilities as $teacherAvailability) {
					$teacher = $teacherAvailability->getTeacher();
					$courses = $teacher->getCourses();
					if (null == $courses || count($courses) == 0) {
						$freeTeachers[] = $teacher;
					} else {
						$teacherFound = true;
						foreach ($courses as $cCours) {
							$dtStart1 = new \DateTime('now');
							$dtStart1->setTimestamp($cCours->getDtStart()->getTimestamp());

							$dtStart2 = new \DateTime('now');
							$dtStart2->setTimestamp($cCours->getDtStart()->getTimestamp());
							$dtStart2 = $dtStart2->modify('+30 minutes');

							$dtStart3 = new \DateTime('now');
							$dtStart3->setTimestamp($cours->getDtStart()->getTimestamp());
							$dtStart3 = $dtStart3->modify('-30 minutes');
							if (($dtStart == $dtStart1 || $dtStart == $dtStart2 || $dtStart == $dtStart3) && ($cCours->getId() != $id)) {

								$teacherFound = false;
							}
						}
						if ($teacherFound == true) {
							$freeTeachers[] = $teacher;
						}
					}
				}

				if (count($freeTeachers) == 0) {
					$traineeLog = new TraineeLog();
					$traineeLog->setTrainee($currentUser);
					$traineeLog->setMsg("Echec modif de Cours du " . $dateFormatter->format($cours->getDtStart(), 'long', 'medium') . " : " . $this->translate('Cours.editErrorNoTeacherFound'));
					$em->persist($traineeLog);
					$em->flush();

					$response->setStatusCode(419);
					$response->setContent($this->translate('Cours.editErrorNoTeacherFound'));

					return $response;
				}

				$currentTeacher = $freeTeachers[0];
				$cours->setTeacher($currentTeacher);
				$cours->setPhone($currentTeacher->getCoursPhone());
				$cours->setDtStart($dtStart);

				$em->persist($cours);
				$em->flush();

				$traineeLog = new TraineeLog();
				$traineeLog->setTrainee($currentUser);
				$traineeLog->setMsg("Success modif de Cours du " . $dateFormatter->format($cours->getDtStart(), 'long', 'medium') . " : ");
				$em->persist($traineeLog);
				$em->flush();

				$jsonResponse = new JsonResponse();
				$jsonResponse->setData(array(
					'id' => $cours->getId(),
					'title' => $this->translate('Cours'),
					'backgroundColor' => $cours->getBackgroundColor(),
					'borderColor' => $cours->getBorderColor(),
					'textColor' => $cours->getTextColor(),
					'allDay' => $cours->getAllDay(),
					'dtStart' => $cours->getEvStart(),
					'dtEnd' => $cours->getEvEnd(),
					'msg' => $this->translate('Cours.editSuccess_txt', array(
						'%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
						'%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
						'%duration%' => $cours->getDuration()
					))
				));

				return $jsonResponse;
			} catch (\Exception $e) {
				$logger = $this->getLogger();
				$logger->addError($e->getLine() . ' ' . $e->getFile() . ' ' . $e->getMessage());

				$traineeLog = new TraineeLog();
				$traineeLog->setTrainee($currentUser);
				$traineeLog->setMsg("Echec modif de Cours " . $e->getMessage());
				$em->persist($traineeLog);
				$em->flush();

				$response->setStatusCode(415);
				$response->setContent($this->translate('Cours.editFailureInvalid'));
				return $response;
			}
		}

		return $this->redirect($urlFrom);
	}

	/**
	 * Ajax Delete Cours
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse \Symfony\Component\HttpFoundation\Response
	 *         \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function ajaxDeleteAction(Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('Trainee__default_homepage'));
		}

		;

		if ($request->isXmlHttpRequest()) {
			$currentUser = $this->getSecurityTokenStorage()->getToken()->getUser();

			$id = $request->request->get('cId');
			$dtStart = new \DateTime();
			$dtStart->setTimestamp($request->request->get('start') / 1000);

			$dateFormatter = $this->get('ilc_france_worldspeak_shared_res.date_formatter');

			$em = $this->getEntityManager();
			$response = new Response();

			try {
				$cours = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Cours')->findOneBy(array(
					'id' => $id
				));

				// verifier si le cours existe
				if (null == $cours) {
					$logger = $this->getLogger();
					$logger->addError("Erreur Ajax Suppression de Cours " . $this->translate('Cours.deleteErrorNotFound'));

					$traineeLog = new TraineeLog();
					$traineeLog->setTrainee($currentUser);
					$traineeLog->setMsg("Erreur Ajax Suppression de Cours " . $this->translate('Cours.deleteErrorNotFound'));
					$em->persist($traineeLog);
					$em->flush();

					$response->setStatusCode(404);
					$response->setContent($this->translate('Cours.deleteErrorNotFound'));

					return $response;
				}

				// verifier si l'utilisateur est bien le propriétaire du cours
				if ($currentUser->getId() != $cours->getTimeCredit()->getTrainee()->getId()) {
					$logger = $this->getLogger();
					$logger->addError("Erreur Ajax Suppression de Cours " . $this->translate('Cours.deleteErrorNotOwner'));

					$traineeLog = new TraineeLog();
					$traineeLog->setTrainee($currentUser);
					$traineeLog->setMsg("Erreur Ajax Suppression de Cours " . $this->translate('Cours.deleteErrorNotOwner'));
					$em->persist($traineeLog);
					$em->flush();

					$response->setStatusCode(403);
					$response->setContent($this->translate('Cours.deleteErrorNotOwner'));

					return $response;
				}

				// verifier si le cours est supprimable
				if ($cours->getDeletable() == false) {
					$logger = $this->getLogger();
					$logger->addError("Erreur Ajax Suppression de Cours " . $this->translate('Cours.deleteErrorNotDeletable'));

					$traineeLog = new TraineeLog();
					$traineeLog->setTrainee($currentUser);
					$traineeLog->setMsg("Erreur Ajax Suppression de Cours " . $this->translate('Cours.deleteErrorNotDeletable'));
					$em->persist($traineeLog);
					$em->flush();

					$response->setStatusCode(419);
					$response->setContent($this->translate('Cours.deleteErrorNotDeletable'));

					return $response;
				}

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
				$em->persist($timeCredit);
				$em->remove($cours);

				$em->flush();

				$traineeLog = new TraineeLog();
				$traineeLog->setTrainee($currentUser);
				$traineeLog->setMsg("Success Ajax Suppression de Cours du " . $dateFormatter->format($cours->getDtStart(), 'long', 'medium'));
				$em->persist($traineeLog);
				$em->flush();

				$jsonResponse = new JsonResponse();
				$jsonResponse->setData(array(
					'id' => $cours->getId(),
					'title' => $this->translate('Cours'),
					'backgroundColor' => $cours->getBackgroundColor(),
					'borderColor' => $cours->getBorderColor(),
					'textColor' => $cours->getTextColor(),
					'allDay' => $cours->getAllDay(),
					'dtStart' => $cours->getEvStart(),
					'dtEnd' => $cours->getEvEnd(),
					'msg' => $this->translate('Cours.deleteSuccess_txt', array(
						'%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
						'%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
						'%duration%' => $cours->getDuration()
					))
				));

				return $jsonResponse;
			} catch (\Exception $e) {
				$logger = $this->getLogger();
				$logger->addError($e->getLine() . ' ' . $e->getFile() . ' ' . $e->getMessage());

				$traineeLog = new TraineeLog();
				$traineeLog->setTrainee($currentUser);
				$traineeLog->setMsg("Erreur Ajax Suppression de Cours " . $e->getMessage());
				$em->persist($traineeLog);
				$em->flush();

				$response->setStatusCode(415);
				$response->setContent($this->translate('Cours.deleteFailure'));

				return $response;
			}
		}

		return $this->redirect($urlFrom);
	}

	/**
	 * Delete Cours Action
	 *
	 * @param guid $id
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Trainee__default_homepage');
		}

		$currentUser = $this->getSecurityTokenStorage()->getToken()->getUser();

		$em = $this->getEntityManager();
		try {
			$cours = $em->find('IlcfranceWorldspeakSharedDataBundle:Cours', $id);

			$dateFormatter = $this->get('ilc_france_worldspeak_shared_res.date_formatter');

			if (null == $cours) {
				$this->addFlash('warning', 'Cours.deleteErrorNotFound');

				$traineeLog = new TraineeLog();
				$traineeLog->setTrainee($currentUser);
				$traineeLog->setMsg("Erreur Suppression de Cours " . $this->translate('Cours.deleteErrorNotFound'));
				$em->persist($traineeLog);
				$em->flush();
			} elseif ($currentUser->getId() != $cours->getTimeCredit()->getTrainee()->getId()) {
				$this->addFlash('error', 'Cours.deleteErrorNotOwner');

				$traineeLog = new TraineeLog();
				$traineeLog->setTrainee($currentUser);
				$traineeLog->setMsg("Erreur Suppression de Cours " . $this->translate('Cours.deleteErrorNotOwner'));
				$em->persist($traineeLog);
				$em->flush();
			} elseif ($cours->getDeletable() == false) {
				$this->addFlash('error', 'Cours.deleteErrorNotDeletable');

				$traineeLog = new TraineeLog();
				$traineeLog->setTrainee($currentUser);
				$traineeLog->setMsg("Erreur Suppression de Cours " . $this->translate('Cours.deleteErrorNotDeletable'));
				$em->persist($traineeLog);
				$em->flush();
			} else {
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
				$em->persist($timeCredit);

				$em->remove($cours);

				$em->flush();

				$traineeLog = new TraineeLog();
				$traineeLog->setTrainee($currentUser);
				$traineeLog->setMsg("Success Suppression de Cours du " . $dateFormatter->format($cours->getDtStart(), 'long', 'medium'));
				$em->persist($traineeLog);
				$em->flush();

				$this->addFlash('success', $this->translate('Cours.deleteSuccess', array(
					'%dStart%' => $dateFormatter->format($cours->getDtStart(), 'full'),
					'%tStart%' => $dateFormatter->format($cours->getDtStart(), 'none', 'long'),
					'%duration%' => $cours->getDuration()
				)));
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getMessage());

			$traineeLog = new TraineeLog();
			$traineeLog->setTrainee($currentUser);
			$traineeLog->setMsg("Erreur Suppression de Cours " . $e->getMessage());
			$em->persist($traineeLog);
			$em->flush();

			$this->addFlash('error', 'Cours.deleteFailure');
		}

		return $this->redirect($urlFrom);
	}
}
