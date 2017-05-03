<?php
namespace Ilcfrance\Worldspeak\Admin\FrontBundle\Controller;

use Ilcfrance\Worldspeak\Shared\ResBundle\Controller\BaseController;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\AdminNotif;
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
	 * @return \Symfony\Component\HttpFoundation\Response
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
	 * @return \Symfony\Component\HttpFoundation\Response
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
	 * @return \Symfony\Component\HttpFoundation\Response
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
	 * @return \Symfony\Component\HttpFoundation\Response
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
	 * @return \Symfony\Component\HttpFoundation\Response
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
	 * @return \Symfony\Component\HttpFoundation\Response
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
	 * @return \Symfony\Component\HttpFoundation\Response
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
	 * @return \Symfony\Component\HttpFoundation\Response
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
	 * @return \Symfony\Component\HttpFoundation\Response
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
	 * @return \Symfony\Component\HttpFoundation\Response
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
	 * @return \Symfony\Component\HttpFoundation\Response
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
	 * @return \Symfony\Component\HttpFoundation\Response
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
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function getAdminNotifsAction($menu_active, Request $request)
	{
		$em = $this->getEntityManager();
		$count = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:AdminNotif')->countOldPending();
		$this->addTwigVar('count', $count);

		$this->addTwigVar('menu_active', $menu_active);

		return $this->render('IlcfranceWorldspeakAdminFrontBundle:Widget:notifs.html.twig', $this->getTwigVars());
	}
}
