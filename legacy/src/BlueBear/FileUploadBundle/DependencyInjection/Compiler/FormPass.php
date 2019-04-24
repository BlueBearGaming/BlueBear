<?php

namespace BlueBear\FileUploadBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class FormPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $template = 'BlueBearFileUploadBundle:Form:fields.html.twig';
        $resources = $container->getParameter('twig.form.resources');
        // Ensure it wasn't already added via config
        if (!in_array($template, $resources)) {
            $resources[] = $template;
            $container->setParameter('twig.form.resources', $resources);
        }
    }
}