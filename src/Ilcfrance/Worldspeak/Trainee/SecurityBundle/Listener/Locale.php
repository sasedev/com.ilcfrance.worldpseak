<?php
namespace Ilcfrance\Worldspeak\Trainee\SecurityBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Locale Listener
 *
 * @author sasedev <sasedev.bifidis@gmail.com>
 */
class Locale
{

	/**
	 * Set Interface Locale from session
	 *
	 * @param GetResponseEvent $event
	 */
	public function setLocale(GetResponseEvent $event)
	{
		if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
			return;
		}
		$request = $event->getRequest();

		$session = $request->getSession();

		$currentlocale = $session->get("_locale");

		$request->setLocale(locale_get_primary_language($currentlocale));
	}
}
