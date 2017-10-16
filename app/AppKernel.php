<?php
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{

    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            new Snc\RedisBundle\SncRedisBundle(),
            new Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle(),
            new Doctrine\Bundle\MongoDBBundle\DoctrineMongoDBBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Liuggio\ExcelBundle\LiuggioExcelBundle(),
            new Gregwar\CaptchaBundle\GregwarCaptchaBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Braincrafted\Bundle\BootstrapBundle\BraincraftedBootstrapBundle(),
            new WhiteOctober\TCPDFBundle\WhiteOctoberTCPDFBundle(),

            new Ilcfrance\Worldspeak\Shared\DataBundle\IlcfranceWorldspeakSharedDataBundle(),
            new Ilcfrance\Worldspeak\Shared\ResBundle\IlcfranceWorldspeakSharedResBundle(),
            new Ilcfrance\Worldspeak\Admin\FrontBundle\IlcfranceWorldspeakAdminFrontBundle(),
            new Ilcfrance\Worldspeak\Admin\SecurityBundle\IlcfranceWorldspeakAdminSecurityBundle(),
            new Ilcfrance\Worldspeak\Teacher\FrontBundle\IlcfranceWorldspeakTeacherFrontBundle(),
            new Ilcfrance\Worldspeak\Teacher\SecurityBundle\IlcfranceWorldspeakTeacherSecurityBundle(),
            new Ilcfrance\Worldspeak\Trainee\FrontBundle\IlcfranceWorldspeakTraineeFrontBundle(),
            new Ilcfrance\Worldspeak\Trainee\SecurityBundle\IlcfranceWorldspeakTraineeSecurityBundle()
        ];

        if (in_array($this->getEnvironment(), [
            'dev',
            'test'
        ], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__) . '/var/cache/' . $this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__) . '/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml');
    }
}
