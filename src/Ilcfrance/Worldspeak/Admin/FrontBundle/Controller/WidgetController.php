<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Controller;

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
     * @param Request $request
     *
     * @return Response
     */
    public function whoIsOnlineAction(Request $request)
    {
        $em = $this->getEntityManager();
        $admins = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Admin')->getAllActiveNow();
        $teachers = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Teacher')->getAllActiveNow();
        $trainees = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Trainee')->getAllActiveNow();

        $this->addTwigVar('admins', $admins);
        $this->addTwigVar('teachers', $teachers);
        $this->addTwigVar('trainees', $trainees);

        return $this->render('IlcfranceWorldspeakAdminFrontBundle:Widget:whoIsOnline.html.twig', $this->getTwigVars());
    }

    /**
     *
     * @param Request $request
     *
     * @return Response
     */
    public function countAdminsAction(Request $request)
    {
        $em = $this->getEntityManager();
        $count = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Admin')->count();
        $this->addTwigVar('count', $count);

        return $this->render('IlcfranceWorldspeakAdminFrontBundle:Widget:count.html.twig', $this->getTwigVars());
    }

    /**
     *
     * @param Request $request
     *
     * @return Response
     */
    public function countAdminsBuggyAction(Request $request)
    {
        $em = $this->getEntityManager();
        $count = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Admin')->countBuggy();
        $this->addTwigVar('count', $count);

        return $this->render('IlcfranceWorldspeakAdminFrontBundle:Widget:count.html.twig', $this->getTwigVars());
    }

    /**
     *
     * @param Request $request
     *
     * @return Response
     */
    public function countTeachersAction(Request $request)
    {
        $em = $this->getEntityManager();
        $count = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Teacher')->count();
        $this->addTwigVar('count', $count);

        return $this->render('IlcfranceWorldspeakAdminFrontBundle:Widget:count.html.twig', $this->getTwigVars());
    }

    /**
     *
     * @param Request $request
     *
     * @return Response
     */
    public function countTeachersBuggyAction(Request $request)
    {
        $em = $this->getEntityManager();
        $count = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Teacher')->countBuggy();
        $this->addTwigVar('count', $count);

        return $this->render('IlcfranceWorldspeakAdminFrontBundle:Widget:count.html.twig', $this->getTwigVars());
    }

    /**
     *
     * @param Request $request
     *
     * @return Response
     */
    public function countTeachingResourcesAction(Request $request)
    {
        $dm = $this->getMongoManager();
        $count = $dm->getRepository('IlcfranceWorldspeakSharedDataBundle:TeachingResource')->count();
        $this->addTwigVar('count', $count);

        return $this->render('IlcfranceWorldspeakAdminFrontBundle:Widget:count.html.twig', $this->getTwigVars());
    }

    /**
     *
     * @param Request $request
     *
     * @return Response
     */
    public function countCompaniesAction(Request $request)
    {
        $em = $this->getEntityManager();
        $count = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Company')->count();
        $this->addTwigVar('count', $count);

        return $this->render('IlcfranceWorldspeakAdminFrontBundle:Widget:count.html.twig', $this->getTwigVars());
    }

    /**
     *
     * @param Request $request
     *
     * @return Response
     */
    public function countTraineesAction(Request $request)
    {
        $em = $this->getEntityManager();
        $count = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Trainee')->count();
        $this->addTwigVar('count', $count);

        return $this->render('IlcfranceWorldspeakAdminFrontBundle:Widget:count.html.twig', $this->getTwigVars());
    }

    /**
     *
     * @param Request $request
     *
     * @return Response
     */
    public function countTraineesBuggyAction(Request $request)
    {
        $em = $this->getEntityManager();
        $count = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Trainee')->countBuggy();
        $this->addTwigVar('count', $count);

        return $this->render('IlcfranceWorldspeakAdminFrontBundle:Widget:count.html.twig', $this->getTwigVars());
    }

    /**
     *
     * @param Request $request
     *
     * @return Response
     */
    public function countTimeCreditsAction(Request $request)
    {
        $em = $this->getEntityManager();
        $count = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCredit')->count();
        $this->addTwigVar('count', $count);

        return $this->render('IlcfranceWorldspeakAdminFrontBundle:Widget:count.html.twig', $this->getTwigVars());
    }

    /**
     *
     * @param Request $request
     *
     * @return Response
     */
    public function countTimeCreditsBuggyAction(Request $request)
    {
        $em = $this->getEntityManager();
        $count = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:TimeCredit')->countBuggy();
        $this->addTwigVar('count', $count);

        return $this->render('IlcfranceWorldspeakAdminFrontBundle:Widget:count.html.twig', $this->getTwigVars());
    }

    /**
     *
     * @param Request $request
     *
     * @return Response
     */
    public function countCoursesAction(Request $request)
    {
        $em = $this->getEntityManager();
        $count = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Cours')->count();
        $this->addTwigVar('count', $count);

        return $this->render('IlcfranceWorldspeakAdminFrontBundle:Widget:count.html.twig', $this->getTwigVars());
    }

    /**
     *
     * @param Request $request
     *
     * @return Response
     */
    public function countCoursesBuggyAction(Request $request)
    {
        $em = $this->getEntityManager();
        $count = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Cours')->countBuggy();
        $this->addTwigVar('count', $count);

        return $this->render('IlcfranceWorldspeakAdminFrontBundle:Widget:count.html.twig', $this->getTwigVars());
    }

    /**
     *
     * @param Request $request
     * @param string $menu_active
     *
     * @return Response
     */
    public function getAdminNotifsAction(Request $request, $menu_active)
    {
        $em = $this->getEntityManager();
        $count = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:AdminNotif')->countOldPending();
        $this->addTwigVar('count', $count);

        $this->addTwigVar('menu_active', $menu_active);

        return $this->render('IlcfranceWorldspeakAdminFrontBundle:Widget:notifs.html.twig', $this->getTwigVars());
    }
}
