<?php

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // Symfony Standard Edition
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            // Extra bundles
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            new Mopa\Bundle\BootstrapBundle\MopaBootstrapBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Oneup\UploaderBundle\OneupUploaderBundle(),
            new DCS\DynamicDiscriminatorMapBundle\DCSDynamicDiscriminatorMapBundle(),
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            new EE\DataExporterBundle\EEDataExporterBundle(),
            new Nc\Bundle\ElephantIOBundle\NcElephantIOBundle(),
            // BlueBear
            new BlueBear\BaseBundle\BlueBearBaseBundle(),
            new BlueBear\CoreBundle\BlueBearCoreBundle(),
            new BlueBear\BackofficeBundle\BlueBearBackofficeBundle(),
            new BlueBear\EditorBundle\BlueBearEditorBundle(),
            new BlueBear\EngineBundle\BlueBearEngineBundle(),
            new BlueBear\UserBundle\BlueBearUserBundle(),
            //new BlueBear\GameBundle\BlueBearGameBundle(),
            new BlueBear\MenuBundle\BlueBearMenuBundle(),
            new BlueBear\FileUploadBundle\BlueBearFileUploadBundle(),
            new BlueBear\AdminBundle\BlueBearAdminBundle(),
            new BlueBear\GameChessBundle\BlueBearGameChessBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }
        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/config_' . $this->getEnvironment() . '.yml');
    }
}
