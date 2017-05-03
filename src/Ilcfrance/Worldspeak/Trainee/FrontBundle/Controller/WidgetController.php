<?php
namespace Ilcfrance\Worldspeak\Trainee\FrontBundle\Controller;

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
	public function getTraineeNotifsAction($menu_active, Request $request)
	{
		$trainee = $this->getSecurityTokenStorage()->getToken()->getUser();

		$em = $this->getEntityManager();
		$count = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TraineeNotif')->countOldPendingTxtByTrainee($trainee);
		$this->addTwigVar('count', $count);

		$this->addTwigVar('menu_active', $menu_active);

		return $this->render('IlcfranceWorldspeakTraineeFrontBundle:Widget:notifs.html.twig', $this->getTwigVars());
	}
}
