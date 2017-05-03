<?php
namespace Ilcfrance\Worldspeak\Teacher\FrontBundle\Controller;

use Ilcfrance\Worldspeak\Shared\ResBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class WidgetController extends BaseController
{

	/**
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function getTeacherNotifsAction($menu_active, Request $request)
	{
		$teacher = $this->getSecurityTokenStorage()->getToken()->getUser();

		$em = $this->getEntityManager();
		$count = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TeacherNotif')->countOldPendingTxtByTeacher($teacher);
		$this->addTwigVar('count', $count);

		$this->addTwigVar('menu_active', $menu_active);

		return $this->render('IlcfranceWorldspeakTeacherFrontBundle:Widget:notifs.html.twig', $this->getTwigVars());
	}
}
