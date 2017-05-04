<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Controller;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TeacherAvailability;
use Ilcfrance\Worldspeak\Admin\FrontBundle\Form\TeacherAvailabilityAddTForm;
use Ilcfrance\Worldspeak\Shared\ResBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TeacherAvailabilityController extends BaseController
{

	/**
	 * Class Constructor
	 */
	public function __construct()
	{
		$this->addTwigVar('menu_active', 'teacher');
	}

	public function modalAddAction(Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
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
		}

		$teacherAvailability = new TeacherAvailability();
		$teacherAvailabilityAddForm = $this->createForm(TeacherAvailabilityAddTForm::class, $teacherAvailability);

		;
		$data = $request->request->all();
		if (isset($data['TeacherAvailabilityAddForm'])) {
			$teacherAvailabilityAddForm->handleRequest($request);

			$dtStart = $teacherAvailability->getDtStart();
			$dtEnd = $teacherAvailability->getDtEnd();
			$teacher = $teacherAvailability->getTeacher();

			$dateFormatter = $this->get('ilc_france_worldspeak_shared_res.date_formatter');

			$dtStartTxt = $dateFormatter->format($dtStart, 'full', 'long');
			$dtEndTxt = $dateFormatter->format($dtEnd, 'full', 'long');

			if ($teacherAvailabilityAddForm->isValid()) {

				$em = $this->getEntityManager();
				$availabilitycheck = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TeacherAvailability')->getAllArroundForTeacher($teacher, $dtStart, $dtEnd, false);

				if (count($availabilitycheck) != 0) {
					$this->addFlash('error', $this->translate('TeacherAvailability.addFailureIntersection', array(
						'%teacher%' => $teacher->getFullname(),
						'%dtStart%' => $dtStartTxt,
						'%dtEnd%' => $dtEndTxt
					)));
				} else {
					$em->persist($teacherAvailability);
					$em->flush();

					$this->addFlash('success', $this->translate('TeacherAvailability.addSuccess', array(
						'%teacher%' => $teacher->getFullname(),
						'%dtStart%' => $dtStartTxt,
						'%dtEnd%' => $dtEndTxt
					)));
				}
			} else {
				$this->addFlash('error', 'TeacherAvailability.addFailureInvalid');
			}
		}

		return $this->redirect($urlFrom);
	}

	public function ajaxAddAction(Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('Admin__teacher_list'));
		}
		;
		if ($request->isXmlHttpRequest()) {
			$tId = $request->request->get('tId');
			$dtStart = new \DateTime();
			$dtStart->setTimestamp($request->request->get('start') / 1000);
			$dtEnd = new \DateTime();
			$dtEnd->setTimestamp($request->request->get('end') / 1000);

			$dateFormatter = $this->get('ilc_france_worldspeak_shared_res.date_formatter');

			$dtStartTxt = $dateFormatter->format($dtStart, 'full', 'long');
			$dtEndTxt = $dateFormatter->format($dtEnd, 'full', 'long');

			$em = $this->getEntityManager();
			$response = new Response();
			try {
				$teacher = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Teacher')->findOneBy(array(
					'id' => $tId
				));

				if (null == $teacher) {
					$response->setStatusCode(404);
					$response->setContent($this->translate('TeacherAvailability.addTeacherNotfound'));

					return $response;
				} else {
					$availabilitycheck = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TeacherAvailability')->getAllArroundForTeacher($teacher, $dtStart, $dtEnd, false);

					if (count($availabilitycheck) != 0) {
						$response->setStatusCode(409);
						$response->setContent($this->translate('TeacherAvailability.addFailureIntersection_txt', array(
							'%teacher%' => $teacher->getFullname(),
							'%dtStart%' => $dtStartTxt,
							'%dtEnd%' => $dtEndTxt
						)));

						return $response;
					} else {
						$teacherAvailability = new TeacherAvailability();
						$teacherAvailability->setTeacher($teacher);
						$teacherAvailability->setDtStart($dtStart);
						$teacherAvailability->setDtEnd($dtEnd);
						$em->persist($teacherAvailability);
						$em->flush();
						$jsonResponse = new JsonResponse();

						$jsonResponse->setData(array(
							'id' => $teacherAvailability->getId(),
							'title' => $this->translate('TeacherAvailability'),
							'backgroundColor' => $teacherAvailability->getBackgroundColor(),
							'borderColor' => $teacherAvailability->getBorderColor(),
							'textColor' => $teacherAvailability->getTextColor(),
							'allDay' => $teacherAvailability->getAllDay(),
							'dtStart' => $teacherAvailability->getEvStart(),
							'dtEnd' => $teacherAvailability->getEvEnd(),
							'msg' => $this->translate('TeacherAvailability.addSuccess_txt', array(
								'%teacher%' => $teacher->getFullname(),
								'%dtStart%' => $dtStartTxt,
								'%dtEnd%' => $dtEndTxt
							))
						));

						return $jsonResponse;
					}
				}
			} catch (\Exception $e) {
				$response->setStatusCode(415);
				$response->setContent($this->translate('TeacherAvailability.addFailureInvalid'));

				return $response;
			}
		} else {
			return $this->redirect($urlFrom);
		}
	}

	public function ajaxEditAction(Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('Admin__teacher_list'));
		}
		if ($request->isXmlHttpRequest()) {
			$taId = $request->request->get('taId');
			$dtStart = new \DateTime();
			$dtStart->setTimestamp($request->request->get('start') / 1000);
			$dtEnd = new \DateTime();
			$dtEnd->setTimestamp($request->request->get('end') / 1000);

			$dateFormatter = $this->get('ilc_france_worldspeak_shared_res.date_formatter');

			$dtStartTxt = $dateFormatter->format($dtStart, 'full', 'long');
			$dtEndTxt = $dateFormatter->format($dtEnd, 'full', 'long');

			$em = $this->getEntityManager();
			$response = new Response();
			try {
				$teacherAvailability = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TeacherAvailability')->findOneBy(array(
					'id' => $taId
				));

				if (null == $teacherAvailability) {
					$response->setStatusCode(404);
					$response->setContent($this->translate('TeacherAvailability.editNotfound'));
					return $response;
				} else {
					$teacher = $teacherAvailability->getTeacher();
					$teacherAvailabilityIntersectionCheck = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TeacherAvailability')->getAllArroundForTeacher($teacherAvailability->getTeacher(), $dtStart, $dtEnd, false);

					$taNotFound = false;
					if (count($teacherAvailabilityIntersectionCheck != 0)) {
						foreach ($teacherAvailabilityIntersectionCheck as $tAv) {
							if ($tAv->getId() != $taId) {
								$taNotFound = true;
							}
						}
					}
					if ($taNotFound) {
						$response->setStatusCode(409);
						$response->setContent($this->translate('TeacherAvailability.editFailureIntersection_txt', array(
							'%teacher%' => $teacher->getFullname(),
							'%dtStart%' => $dtStartTxt,
							'%dtEnd%' => $dtEndTxt
						)));

						return $response;
					} else {
						$teacherAvailability->setDtStart($dtStart);
						$teacherAvailability->setDtEnd($dtEnd);
						$em->persist($teacherAvailability);
						$em->flush();
						$jsonResponse = new JsonResponse();

						$jsonResponse->setData(array(
							'id' => $taId,
							'title' => $this->translate('TeacherAvailability') . '\n' . $teacherAvailability->getTeacher()->getFullname(),
							'backgroundColor' => $teacherAvailability->getBackgroundColor(),
							'borderColor' => $teacherAvailability->getBorderColor(),
							'textColor' => $teacherAvailability->getTextColor(),
							'allDay' => $teacherAvailability->getAllDay(),
							'dtStart' => $teacherAvailability->getEvStart(),
							'dtEnd' => $teacherAvailability->getEvEnd(),
							'msg' => $this->translate('TeacherAvailability.editSuccess_txt', array(
								'%teacher%' => $teacher->getFullname(),
								'%dtStart%' => $dtStartTxt,
								'%dtEnd%' => $dtEndTxt
							))
						));

						return $jsonResponse;
					}
				}
			} catch (\Exception $e) {
				$response->setStatusCode(415);
				$response->setContent($this->translate('TeacherAvailability.editFailureInvalid'));

				return $response;
			}
		} else {
			return $this->redirect($urlFrom);
		}
	}

	public function ajaxDeleteAction(Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('Admin__teacher_list'));
		}
		;
		if ($request->isXmlHttpRequest()) {
			$taId = $request->request->get('taId');
			$em = $this->getEntityManager();
			$response = new Response();
			try {
				$teacherAvailability = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TeacherAvailability')->findOneBy(array(
					'id' => $taId
				));

				if (null == $teacherAvailability) {
					$response->setStatusCode(404);
					$response->setContent($this->translate('TeacherAvailability.deleteNotfound'));

					return $response;
				} else {

					$dateFormatter = $this->get('ilc_france_worldspeak_shared_res.date_formatter');

					$dtStartTxt = $dateFormatter->format($teacherAvailability->getDtStart(), 'full', 'long');
					$dtEndTxt = $dateFormatter->format($teacherAvailability->getDtEnd(), 'full', 'long');
					$teacher = $teacherAvailability->getTeacher();

					$em->remove($teacherAvailability);
					$em->flush();
					$jsonResponse = new JsonResponse();

					$jsonResponse->setData(array(
						'id' => $taId,
						'msg' => $this->translate('TeacherAvailability.deleteSuccess_txt', array(
							'%teacher%' => $teacher->getFullname(),
							'%dtStart%' => $dtStartTxt,
							'%dtEnd%' => $dtEndTxt
						))
					));

					return $jsonResponse;
				}
			} catch (\Exception $e) {
				$response->setStatusCode(415);
				$response->setContent($this->translate('TeacherAvailability.deleteFailureInvalid'));

				return $response;
			}
		} else {
			return $this->redirect($urlFrom);
		}
	}
}
