<?php
namespace Ilcfrance\Worldspeak\Trainee\FrontBundle\Controller;

use Ilcfrance\Worldspeak\Shared\ResBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class TimeCreditDocumentController extends BaseController
{

	/**
	 * Class Constructor
	 */
	public function __construct()
	{
		$this->addTwigVar('menu_active', '');
	}

	/**
	 *
	 * @param guid $id
	 *
	 * @throws NotFoundHttpException
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function downloadAction($id, Request $request)
	{
		$urlFrom = $this->getReferer($request);
		if (null == $urlFrom || trim($urlFrom) == '') {
			$urlFrom = $this->generateUrl('Trainee__default_homepage');
		}
		$em = $this->getEntityManager();
		try {
			$timeCreditDocument = $em->find('IlcfranceWorldspeakSharedDataBundle:TimeCreditDocument', $id);
			if (null == $timeCreditDocument || null == $timeCreditDocument->getTeachingResource()) {
				throw new NotFoundHttpException();
			}
			$response = new Response();
			$response->headers->set('Cache-Control', 'private');
			$response->headers->set('Content-type', $timeCreditDocument->getTeachingResource()->getMimeType());
			$response->headers->set('Content-Disposition', 'attachment; filename="' . $timeCreditDocument->getTeachingResource()->getFilename() . '"');
			$response->headers->set('Content-length', $timeCreditDocument->getTeachingResource()->getLength());
			// Send headers before outputting anything
			$response->sendHeaders();

			$response->setContent($timeCreditDocument->getTeachingResource()->getFile()->getBytes());

			return $response;
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->addError($e->getMessage());
		}

		return $this->redirect($urlFrom);
	}
}
