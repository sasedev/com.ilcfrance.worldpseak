<?php
namespace Ilcfrance\Worldspeak\Shared\ResBundle\Extension;

use Symfony\Component\Intl\Intl;

/**
 *
 * @author sasedev
 */
class CountryExtension extends \Twig_Extension
{

	public function getFilters()
	{
		return array(
			new \Twig_SimpleFilter('country', array(
				$this,
				'countryName'
			))
		);
	}

	public function countryName($countryCode)
	{
		return Intl::getRegionBundle()->getCountryName($countryCode);
	}

	public function getName()
	{
		return 'country_extension';
	}
}