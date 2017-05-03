<?php
namespace Ilcfrance\Worldspeak\Teacher\FrontBundle\Controller;

use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\TeacherAvailability;
use Ilcfrance\Worldspeak\Shared\ResBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * TeacherAvailability Controller
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TeacherAvailabilityController extends BaseController
{

	public function ajaxAddAction(Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			return $this->redirect($this->generateUrl('Teacher__default_homepage'));
		}
		;
		if ($request->isXmlHttpRequest()) {
			$currentUser = $this->getSecurityTokenStorage()->getToken()->getUser();
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
				$availabilitycheck = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TeacherAvailability')->getAllArroundForTeacher($currentUser, $dtStart, $dtEnd);

				if (count($availabilitycheck) != 0) {
					$response->setStatusCode(409);
					$response->setContent($this->translate('TeacherAvailability.addFailureIntersection_txt', array(
						'%dtStart%' => $dtStartTxt,
						'%dtEnd%' => $dtEndTxt
					)));

					return $response;
				} else {
					$teacherAvailability = new TeacherAvailability();
					$teacherAvailability->setTeacher($currentUser);
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
							'%dtStart%' => $dtStartTxt,
							'%dtEnd%' => $dtEndTxt
						))
					));

					return $jsonResponse;
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
			return $this->redirect($this->generateUrl('Teacher__default_homepage'));
		}
		;
		if ($request->isXmlHttpRequest()) {
			$currentUser = $this->getSecurityTokenStorage()->getToken()->getUser();
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
				} elseif ($teacherAvailability->getTeacher()->getId() != $currentUser->getId()) {
					$response->setStatusCode(403);
					$response->setContent($this->translate('TeacherAvailability.editForbidden'));

					return $response;
				} else {
					$teacherAvailabilityIntersection = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TeacherAvailability')->getAllArroundForTeacher($currentUser, $dtStart, $dtEnd);

					$taNotFound = false;
					if (count($teacherAvailabilityIntersection != 0)) {
						foreach ($teacherAvailabilityIntersection as $tAv) {
							if ($tAv->getId() != $taId) {
								$taNotFound = true;
							}
						}
					}
					if ($taNotFound) {
						$response->setStatusCode(409);
						$response->setContent($this->translate('TeacherAvailability.editFailureIntersection_txt', array(
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
							'title' => $this->translate('TeacherAvailability'),
							'backgroundColor' => $teacherAvailability->getBackgroundColor(),
							'borderColor' => $teacherAvailability->getBorderColor(),
							'textColor' => $teacherAvailability->getTextColor(),
							'allDay' => $teacherAvailability->getAllDay(),
							'dtStart' => $teacherAvailability->getEvStart(),
							'dtEnd' => $teacherAvailability->getEvEnd(),
							'msg' => $this->translate('TeacherAvailability.editSuccess_txt', array(
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
			return $this->redirect($this->generateUrl('Teacher__default_homepage'));
		}
		;
		if ($request->isXmlHttpRequest()) {
			$currentUser = $this->getSecurityTokenStorage()->getToken()->getUser();
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
				} elseif ($teacherAvailability->getTeacher()->getId() != $currentUser->getId()) {
					$response->setStatusCode(403);
					$response->setContent($this->translate('TeacherAvailability.deleteForbidden'));

					return $response;
				} else {

					$dateFormatter = $this->get('ilc_france_worldspeak_shared_res.date_formatter');

					$dtStartTxt = $dateFormatter->format($teacherAvailability->getDtStart(), 'full', 'long');
					$dtEndTxt = $dateFormatter->format($teacherAvailability->getDtEnd(), 'full', 'long');

					$em->remove($teacherAvailability);
					$em->flush();
					$jsonResponse = new JsonResponse();

					$jsonResponse->setData(array(
						'id' => $taId,
						'msg' => $this->translate('TeacherAvailability.deleteSuccess_txt', array(
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
