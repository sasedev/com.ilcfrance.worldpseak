<?php
namespace Ilcfrance\Worldspeak\Shared\ResBundle\Controller;

/**
 * WidgetController
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class WidgetController extends BaseController
{

	/**
	 * whoamiAction
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function whoamiAction()
	{
		$user = $this->getSecurityTokenStorage()->getToken()->getUser();
		$this->addTwigVar('user', $user);

		return $this->render('IlcfranceWorldspeakSharedResBundle:Widget:whoami.html.twig', $this->getTwigVars());
	}
}
