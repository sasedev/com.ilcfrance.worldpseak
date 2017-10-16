<?php
namespace Ilcfrance\Worldspeak\Shared\ResBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @param Request $request
     *
     * @return Response
     */
    public function whoamiAction(Request $request)
    {
        $user = $this->getSecurityTokenStorage()
            ->getToken()
            ->getUser();
        $this->addTwigVar('user', $user);

        return $this->render('IlcfranceWorldspeakSharedResBundle:Widget:whoami.html.twig', $this->getTwigVars());
    }
}
