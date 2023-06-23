<?php

namespace App;

use Exception;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

/**
 * Class Kernel
 */
class Kernel extends BaseKernel
{
  use MicroKernelTrait;

  private const CONFIG_EXTS = '.{php,xml,yaml,yml}';

  /**
  * @return iterable<BundleInterface>
  */
  public function registerBundles(): iterable
  {
    $contents = require $this->getProjectDir() . '/config/bundles.php';
    foreach ($contents as $class => $envs) {
      /** @noinspection PhpIllegalArrayKeyTypeInspection */
      if ($envs[$this->environment] ?? $envs['all'] ?? false) {
        yield new $class();
      }
    }
  }

  /**
   * @return string
   */
  public function getCacheDir(): string
  {
    return $this->getProjectDir() . '/var/cache/' . $this->environment;
  }

  /**
   * @param ContainerBuilder $container
   * @param LoaderInterface $loader
   * @throws Exception
   */
  protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
  {
    $container->addResource(new FileResource($this->getProjectDir() . '/config/bundles.php'));
    $container->setParameter('container.dumper.inline_class_loader', true);

    $confDirs = [
      $this->getProjectDir() . '/config',
      __DIR__ . '/Todo/config',
    ];

    foreach ($confDirs as $confDir) {
      $loader->load(
        $confDir . '/{packages}/*' . self::CONFIG_EXTS,
        'glob'
      );
      $loader->load(
        $confDir . '/{packages}/' . $this->environment . '/**/*' . self::CONFIG_EXTS,
        'glob'
      );
      $loader->load(
        $confDir . '/{services}' . self::CONFIG_EXTS,
        'glob'
      );
      $loader->load(
        $confDir . '/{services}_' . $this->environment . self::CONFIG_EXTS,
        'glob'
      );
    }
  }

  /**
  * @param RoutingConfigurator $routes
  */
  protected function configureRoutes(RoutingConfigurator $routes): void
  {
    $confDirs = [
        $this->getProjectDir() . '/config',
        __DIR__ . '/Todo/config',
    ];

    foreach ($confDirs as $confDir) {
      $routes->import(
        $confDir . '/{routes}/' . $this->environment . '/**/*' . self::CONFIG_EXTS,
        'glob'
      );
      $routes->import(
        $confDir . '/{routes}/*' . self::CONFIG_EXTS,
        'glob'
      );
      $routes->import(
        $confDir . '/{routes}' . self::CONFIG_EXTS,
        'glob'
      );
    }
  }
}