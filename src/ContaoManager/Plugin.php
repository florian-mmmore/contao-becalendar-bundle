<?php
/**
 * Created by PhpStorm.
 * User: thomasvoggenreiter
 * Date: 26.01.18
 * Time: 08:40
 * License: LGPL-3.0+
 */

namespace Dreibein\BeCalendarBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Routing\RoutingPluginInterface;
use Dreibein\BeCalendarBundle\DreibeinBeCalendarBundle;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouteCollection;

class Plugin implements BundlePluginInterface, RoutingPluginInterface
{
    /**
     * @param ParserInterface $parser
     * @return array
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(DreibeinBeCalendarBundle::class)->setLoadAfter([ContaoCoreBundle::class])
        ];
    }

    /**
     * @param LoaderResolverInterface $resolver
     * @param KernelInterface $kernel
     *
     * @return null|RouteCollection
     *
     * @throws \Exception
     */
    public function getRouteCollection(LoaderResolverInterface $resolver, KernelInterface $kernel)
    {
        return $resolver
            ->resolve(__DIR__.'/../Resources/config/routing.yml')
            ->load(__DIR__.'/../Resources/config/routing.yml');
    }
}