<?php
namespace Ilcfrance\Worldspeak\Shared\ResBundle\Extension;

use Twig_Extension;
use Twig_ExtensionInterface;
use Twig_SimpleFunction;

/**
 *
 * @author sasedev <seif.salah@gmail.com>
 */
class PhpExtension extends Twig_Extension
{

    /**
     *
     * {@inheritdoc}
     * @see Twig_Extension::getFunctions()
     */
    public function getFunctions()
    {
        $fonctions = array();

        $fonctions['sasedevphp_*'] = new Twig_SimpleFunction('sasedevphp_*', array(
            $this,
            'twigToPhp'
        ), array(
            'pre_escape' => 'html',
            'is_safe' => array(
                'html'
            )
        ));

        return $fonctions;
    }

    public function twigToPhp()
    {
        $arg_list = func_get_args();
        $function = array_shift($arg_list);

        return call_user_func_array($function, $arg_list);
    }

    /**
     *
     * {@inheritdoc}
     * @see Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return 'Ilcfrance.Worldspeak.Shared.ResBundle.CallPhp';
    }
}
