<?php
namespace Ilcfrance\Worldspeak\Teacher\SecurityBundle\Controller;

use Ilcfrance\Worldspeak\Shared\ResBundle\Controller\BaseController;
use Ilcfrance\Worldspeak\Teacher\SecurityBundle\Form\SecurityDontRememberUsernameTForm;
use Ilcfrance\Worldspeak\Teacher\SecurityBundle\Form\SecurityLostPasswordTForm;
use Ilcfrance\Worldspeak\Teacher\SecurityBundle\Form\UserAvatarResizeTForm;
use Ilcfrance\Worldspeak\Teacher\SecurityBundle\Form\UserAvatarTForm;
use Ilcfrance\Worldspeak\Teacher\SecurityBundle\Form\UserEditCoursPhoneTForm;
use Ilcfrance\Worldspeak\Teacher\SecurityBundle\Form\UserEditEmailTForm;
use Ilcfrance\Worldspeak\Teacher\SecurityBundle\Form\UserEditPasswordTForm;
use Ilcfrance\Worldspeak\Teacher\SecurityBundle\Form\UserEditPreferedLocaleTForm;
use Ilcfrance\Worldspeak\Teacher\SecurityBundle\Form\UserEditProfileTForm;
use Ilcfrance\Worldspeak\Shared\DataBundle\Document\TeacherAvatar;
use Ilcfrance\Worldspeak\Shared\DataBundle\Entity\Teacher;
use Imagine\Imagick\Imagine;
use Imagine\Image\Point;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\Pbkdf2PasswordEncoder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Ilcfrance\Worldspeak\Teacher\SecurityBundle\Form\LoginTForm;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Security Controller
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class SecurityController extends BaseController
{

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->addTwigVar('menu_active', 'account');
	}

	/**
	 * login Action
	 *
	 * @param Request $request
	 *
	 * @return RedirectResponse|Response
	 */
	public function loginAction(Request $request)
	{
		// si l'utilisateur est déja connecté on le redirige vers sa page de
		// profile
		if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
			return $this->redirect($this->generateUrl('teacher_security_profile'));
		}
		$session = $this->getSession();
		if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
			$error = $request->attributes->get(Security::AUTHENTICATION_ERROR);
			$request->attributes->remove(Security::AUTHENTICATION_ERROR);
			$msg = $this->translate($error->getMessage());
			$this->addFlash('error', $msg);
		} elseif ($session->has(Security::AUTHENTICATION_ERROR)) {
			$error = $session->get(Security::AUTHENTICATION_ERROR);
			$session->remove(Security::AUTHENTICATION_ERROR);
			$msg = $this->translate($error->getMessage());
			$this->addFlash('error', $msg);
		}

		$lastUsername = $session->get('_security.last_username');
		$referer = $this->getReferer($request);

		$loginForm = $this->createForm(LoginTForm::class);

		$loginForm->get('username')->setData($lastUsername);
		$loginForm->get('target_path')->setData($referer);
		$loginForm->get('remember_me')->setData(true);
		$this->addTwigVar('LoginForm', $loginForm->createView());

		$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitle_login'));
		$this->addTwigVar('last_username', $lastUsername);

		return $this->render('IlcfranceWorldspeakTeacherSecurityBundle:Security:login.html.twig', $this->getTwigVars());
	}

	/**
	 * dontRememberUsername Action
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse Ambigous Response>
	 */
	public function dontRememberUsernameAction(Request $request)
	{
		if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
			return $this->redirect($this->generateUrl('teacher_security_profile'));
		}
		$securityDontRememberUsernameForm = $this->createForm(SecurityDontRememberUsernameTForm::class);
		;
		if ($request->getMethod() == 'POST') {
			$securityDontRememberUsernameForm->handleRequest($request);
			if ($securityDontRememberUsernameForm->isValid()) {
				$email = $securityDontRememberUsernameForm['email']->getData();
				$em = $this->getEntityManager();
				$user = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Teacher')->findOneBy(array(
					'email' => $email
				));
				if (null != $user) {
					$locale = null;
					if (null != $user->getPreferedLocale()) {
						$locale = $user->getPreferedLocale()->getPrefix();
					}
					$mvars = array();
					$mvars['user'] = $user;
					$mvars['userPreferedLocale'] = $locale;
					$from = $this->getParameter('mail_from');
					$fromName = $this->getParameter('mail_from_name');
					$subject = $this->translate('_mail.dontRememberUsername_subject', array(), 'messages', $locale);

					$message = \Swift_Message::newInstance()->setFrom($from, $fromName)->setTo($user->getEmail(), $user->getFullname())->setSubject($subject)->setBody($this->renderView('IlcfranceWorldspeakTeacherSecurityBundle:Mail:dontRememberUsername.html.twig', $mvars), 'text/html');

					$this->sendmail($message);

					$this->addFlash('info', $this->translate('_sec.dontRememberUsername_mail_username_sent', array(
						'%mail%' => $email
					)));

					return $this->redirect($this->generateUrl('teacher_security_login'));
				} else {
					$this->addFlash('error', $this->translate('_sec.dontRememberUsername_notfound', array(
						'%mail%' => $email
					)));

					return $this->redirect($this->generateUrl('teacher_security_lost_username'));
				}
			}
		}
		$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitle_dontRememberUsername'));
		$this->addTwigVar('securityDontRememberUsernameForm', $securityDontRememberUsernameForm->createView());

		return $this->render('IlcfranceWorldspeakTeacherSecurityBundle:Security:dontRememberUsername.html.twig', $this->getTwigVars());
	}

	/**
	 * lostPassword Action
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse Ambigous Response>
	 */
	public function lostPasswordAction(Request $request)
	{
		if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
			return $this->redirect($this->generateUrl('teacher_security_profile'));
		}
		$securityLostPasswordForm = $this->createForm(SecurityLostPasswordTForm::class);
		;
		if ($request->getMethod() == 'POST') {
			$securityLostPasswordForm->handleRequest($request);
			if ($securityLostPasswordForm->isValid()) {
				$username = $securityLostPasswordForm['username']->getData();
				$em = $this->getEntityManager();
				$user = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Teacher')->findOneBy(array(
					'username' => $username
				));
				if (null != $user) {
					$now = new \DateTime('now');
					$nexthour = new \DateTime();
					$nexthour->setTimestamp(strtotime('+1 hour'));
					if (null == $user->getRecoveryExpiration() || $user->getRecoveryExpiration() < $now) {
						$locale = null;
						if (null != $user->getPreferedLocale()) {
							$locale = $user->getPreferedLocale()->getPrefix();
						}
						$user->setRecoveryExpiration($nexthour);
						$user->setRecoveryCode(Teacher::generateRandomChar(20, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'));
						$em->persist($user);
						$em->flush();

						$mvars = array();
						$mvars['user'] = $user;
						$mvars['userPreferedLocale'] = $locale;
						$mvars['url'] = $this->generateUrl('teacher_security_lost_genpassword', array(
							'id' => $user->getId(),
							'code' => $user->getRecoveryCode()
						), UrlGeneratorInterface::ABSOLUTE_URL);
						$from = $this->getParameter('mail_from');
						$fromName = $this->getParameter('mail_from_name');
						$subject = $this->translate('_mail.lostPassword_subject', array(), 'messages', $locale);

						$message = \Swift_Message::newInstance()->setFrom($from, $fromName)->setTo($user->getEmail(), $user->getFullname())->setSubject($subject)->setBody($this->renderView('IlcfranceWorldspeakTeacherSecurityBundle:Mail:getPasswordResetLink.html.twig', $mvars), 'text/html');

						$this->sendmail($message);

						$this->addFlash('info', $this->translate('_sec.lostPassword_mail_newpass_sent', array(
							'%mail%' => $user->getEmail()
						)));

						return $this->redirect($this->generateUrl('teacher_security_login'));
					} else {
						$this->addFlash('error', $this->translate('_sec.lostPassword.already_sent'));

						return $this->redirect($this->generateUrl('teacher_security_login'));
					}
				} else {
					$this->addFlash('error', $this->translate('_sec.lostPassword_notfound', array(
						'%username%' => $username
					)));

					return $this->redirect($this->generateUrl('teacher_security_lost_password'));
				}
			}
		}
		$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitle_lostPassword'));
		$this->addTwigVar('securityLostPasswordForm', $securityLostPasswordForm->createView());

		return $this->render('IlcfranceWorldspeakTeacherSecurityBundle:Security:lostPassword.html.twig', $this->getTwigVars());
	}

	/**
	 * genNewPassword Action
	 *
	 * @param guid $id
	 * @param string $code
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function genNewPasswordAction($id, $code, Request $request)
	{
		if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
			return $this->redirect($this->generateUrl('teacher_security_profile'));
		}
		$em = $this->getEntityManager();
		try {
			$user = $em->getRepository('IlcfranceWorldspeakSharedDataBundle:Teacher')->find($id);
			if (null != $user) {
				$now = new \DateTime('now');
				if (null == $user->getRecoveryExpiration() || $user->getRecoveryExpiration() < $now) {
					$this->addFlash('error', $this->translate('_sec.genNewPassword_errorparams2'));
				} elseif ($user->getRecoveryCode() != $code) {
					$this->addFlash('error', $this->translate('_sec.genNewPassword_errorparams3'));
				} else {
					$user->setSalt(md5(uniqid(null, true)));
					$user->setClearPassword(Teacher::generateRandomChar(8, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'));
					$user->setRecoveryExpiration(null);
					$user->setRecoveryCode(null);
					$em->persist($user);
					$em->flush();
					$locale = null;
					if (null != $user->getPreferedLocale()) {
						$locale = $user->getPreferedLocale()->getPrefix();
					}

					$mvars = array();
					$mvars['user'] = $user;
					$mvars['userPreferedLocale'] = $locale;
					$from = $this->getParameter('mail_from');
					$fromName = $this->getParameter('mail_from_name');
					$subject = $this->translate('_mail.genNewPassword_subject', array(), 'messages', $locale);
					$message = \Swift_Message::newInstance()->setFrom($from, $fromName)->setTo($user->getEmail(), $user->getFullname())->setSubject($subject)->setBody($this->renderView('IlcfranceWorldspeakTeacherSecurityBundle:Mail:genNewPassword.html.twig', $mvars), 'text/html');
					$this->sendmail($message);

					$this->addFlash('info', $this->translate('_sec.genNewPassword_ok'));
				}
			} else {
				$this->addFlash('error', $this->translate('_sec.genNewPassword_errorparams1'));
			}
		} catch (\Exception $e) {
			$logger = $this->getLogger();
			$logger->error($e->getMessage());
			$this->addFlash('error', $this->translate('_sec.genNewPassword_errorparams4'));
		}

		return $this->redirect($this->generateUrl('teacher_security_login'));
	}

	/**
	 * myAvatar Action
	 *
	 * @throws NotFoundHttpException
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function myAvatarAction(Request $request)
	{
		$user = $this->getSecurityTokenStorage()->getToken()->getUser();
		$avatar = $user->getAvatar();
		if (null == $avatar) {
			throw new NotFoundHttpException();
		}
		$response = new Response();
		$response->headers->set('Content-Type', $avatar->getMimeType());
		$response->setContent($avatar->getFile()->getBytes());

		return $response;
	}

	/**
	 * myProfile Action
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse Ambigous Response>
	 */
	public function myProfileAction(Request $request)
	{
		$user = $this->getSecurityTokenStorage()->getToken()->getUser();
		$em = $this->getEntityManager();
		;
		$oldDbpass = $user->getPassword();
		$userBack = $user;

		$tabActive = 1;
		$this->addTwigVar('user', $user);

		$userEditPasswordForm = $this->createForm(UserEditPasswordTForm::class, $user);
		$userEditCoursPhoneForm = $this->createForm(UserEditCoursPhoneTForm::class, $user);
		$userEditEmailForm = $this->createForm(UserEditEmailTForm::class, $user);
		$userEditProfileForm = $this->createForm(UserEditProfileTForm::class, $user);
		$userEditPreferedLocaleForm = $this->createForm(UserEditPreferedLocaleTForm::class, $user);
		$userAvatarForm = $this->createForm(UserAvatarTForm::class);
		$userAvatarResizeForm = $this->createForm(UserAvatarResizeTForm::class);

		if ($request->getMethod() == 'POST') {
			$data = $request->request->all();
			if (isset($data['UserEditPasswordForm'])) {
				$tabActive = 2;
				$userEditPasswordForm->handleRequest($request);
				if ($userEditPasswordForm->isValid()) {
					$oldPassword = $userEditPasswordForm['oldPassword']->getData();
					$encoder = new Pbkdf2PasswordEncoder('sha512', true, 1000);
					$oldpassEncoded = $encoder->encodePassword($oldPassword, $userBack->getSalt());
					if ($oldpassEncoded != $oldDbpass) {
						$formError = new FormError($this->translate('user.oldPassword.incorrect', array(), 'validators'));
						$userEditPasswordForm['oldPassword']->addError($formError);
					} else {
						$em->persist($user);
						$em->flush();
						$this->addFlash('info', 'profile.updatesuccess.password');

						return $this->redirect($this->generateUrl('teacher_security_profile'));
					}
				} else {
					$user = $userBack;
				}
			}
			if (isset($data['UserEditCoursPhoneForm'])) {
				$tabActive = 2;
				$userEditCoursPhoneForm->handleRequest($request);
				if ($userEditCoursPhoneForm->isValid()) {
					$em->persist($user);
					$em->flush();
					$this->addFlash('info', 'profile.updatesuccess.coursPhone');

					return $this->redirect($this->generateUrl('teacher_security_profile'));
				} else {
					$user = $userBack;
				}
			}
			if (isset($data['UserEditEmailForm'])) {
				$tabActive = 2;
				$userEditEmailForm->handleRequest($request);
				if ($userEditEmailForm->isValid()) {
					$em->persist($user);
					$em->flush();
					$this->addFlash('info', 'profile.updatesuccess.mail');

					return $this->redirect($this->generateUrl('teacher_security_profile'));
				} else {
					$user = $userBack;
				}
			}
			if (isset($data['UserEditProfileForm'])) {
				$tabActive = 2;
				$userEditProfileForm->handleRequest($request);
				if ($userEditProfileForm->isValid()) {
					$em->persist($user);
					$em->flush();
					$this->addFlash('info', 'profile.updatesuccess.advancedprofile');

					return $this->redirect($this->generateUrl('teacher_security_profile'));
				} else {
					$user = $userBack;
				}
			}
			if (isset($data['UserEditPreferedLocaleForm'])) {
				$tabActive = 2;
				$userEditPreferedLocaleForm->handleRequest($request);
				if ($userEditPreferedLocaleForm->isValid()) {
					if (null != $user->getPreferedLocale()) {
						$locale = $user->getPreferedLocale();
						$session = $this->getSession();
						$session->set('_locale', $locale->getPrefix());
					}
					$em->persist($user);
					$em->flush();
					$this->addFlash('info', 'profile.updatesuccess.preferedLocale');

					return $this->redirect($this->generateUrl('teacher_security_profile'));
				} else {
					$user = $userBack;
				}
			}
			if (isset($data['UserAvatarForm'])) {
				$tabActive = 2;
				$userAvatarForm->handleRequest($request);
				if ($userAvatarForm->isValid()) {
					$filename = $user->getUsername() . "_" . uniqid() . '.' . $userAvatarForm['avatar']->getData()->guessExtension();
					$userAvatarForm['avatar']->getData()->move($this->getParameter('adapter_tmp_files'), $filename);
					$this->addTwigVar('tmp_avatar', $filename);
					$userAvatarResizeForm = $this->createForm(UserAvatarResizeTForm::class, null, array(
						'filename' => $filename
					));
					$this->addTwigVar('userAvatarResizeForm', $userAvatarResizeForm->createView());

					return $this->render('IlcfranceWorldspeakTeacherSecurityBundle:Security:resize_avatar.html.twig', $this->getTwigVars());
				} else {
					$this->addTwigVar('userAvatarForm', $userAvatarForm->createView());

					return $this->render('IlcfranceWorldspeakTeacherSecurityBundle:Security:resize_avatar_error.html.twig', $this->getTwigVars());
				}
			}
			if (isset($data['UserAvatarResizeForm'])) {
				$tabActive = 2;
				$userAvatarResizeForm->handleRequest($request);
				if ($userAvatarResizeForm->isValid()) {

					$filename = $userAvatarResizeForm['avatar_tmp']->getData();
					$path = $this->getParameter('adapter_tmp_files') . '/' . $filename;
					$x1 = $userAvatarResizeForm['x1']->getData();
					$y1 = $userAvatarResizeForm['y1']->getData();
					$w = $userAvatarResizeForm['w']->getData();
					$h = $userAvatarResizeForm['h']->getData();

					$imagine = new Imagine();
					$image = $imagine->open($path);
					$widh = $image->getSize()->getWidth();
					$mult = 1;
					if ($widh > 400) {
						$mult = $widh / 400;
					}
					if ($widh < 130) {
						$mult = $widh / 130;
					}
					$x1 = round($x1 * $mult);
					$y1 = round($y1 * $mult);
					$w = round($w * $mult);
					$h = round($h * $mult);

					$firstpoint = new Point($x1, $y1);
					$selbox = new Box($w, $h);
					$lastbox = new Box(130, 170);
					$mode = ImageInterface::THUMBNAIL_OUTBOUND;

					$image->crop($firstpoint, $selbox)->thumbnail($lastbox, $mode)->save($path);

					$file = new File($path);
					$dm = $this->getMongoManager();
					if (null == $user->getAvatar()) {
						$avatar = new TeacherAvatar();
					} else {
						$avatar = $user->getAvatar();
						$dm->remove($avatar);
						$dm->flush();
						$avatar = new TeacherAvatar();
					}
					$avatar->setFile($file->getPathname());
					$avatar->setMimeType($file->getMimeType());
					$avatar->setFilename($filename);

					$dm->persist($avatar);
					$dm->flush();

					$user->setAvatar($avatar);

					$em->persist($user);
					$em->flush();
					unlink($path);
					$this->addFlash('info', 'profile.updatesuccess.avatar');
				} else {
					$this->addFlash('error', 'profile.updatefailure.avatar');
				}

				return $this->redirect($this->generateUrl('teacher_security_profile'));
			}
		}

		$stylesheets = array();
		$stylesheets[] = 'css/jquery.Jcrop.min.css';
		$javascripts = array();
		$javascripts[] = 'js/jquery.Jcrop.min.js';
		$javascripts[] = 'js/jquery.color.js';
		$javascripts[] = 'js/jquery.form.min.js';
		$this->addTwigVar('stylesheets', $stylesheets);
		$this->addTwigVar('javascripts', $javascripts);

		$this->addTwigVar('userEditPasswordForm', $userEditPasswordForm->createView());
		$this->addTwigVar('userEditCoursPhoneForm', $userEditCoursPhoneForm->createView());
		$this->addTwigVar('userEditEmailForm', $userEditEmailForm->createView());
		$this->addTwigVar('userEditPreferedLocaleForm', $userEditPreferedLocaleForm->createView());
		$this->addTwigVar('userAvatarForm', $userAvatarForm->createView());
		$this->addTwigVar('userAvatarResizeForm', $userAvatarResizeForm->createView());
		$this->addTwigVar('userEditProfileForm', $userEditProfileForm->createView());
		$this->addTwigVar('pagetitle', $this->translate('_pagetitle_myProfile'));
		$this->addTwigVar('pagetitle_txt', $this->translate('_pagetitle_myProfile'));
		$this->addTwigVar('tab_active', $tabActive);

		return $this->render('IlcfranceWorldspeakTeacherSecurityBundle:Security:myprofile.html.twig', $this->getTwigVars());
	}
}
