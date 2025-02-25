<?php declare(strict_types = 1);

namespace DrupalCodeGenerator;

use Drupal\Core\DependencyInjection\ClassResolverInterface;
use DrupalCodeGenerator\Command\BaseGenerator;
use DrupalCodeGenerator\Command\LegacyGenerator;

/**
 * Defines generator factory.
 *
 * This factory only supports DCG core generators.
 */
final class GeneratorFactory {

  private const DIRECTORY = Application::ROOT . '/src/Command';
  private const NAMESPACE = '\DrupalCodeGenerator\Command';

  public function __construct(
    private readonly ClassResolverInterface $classResolver,
  ) {}

  /**
   * Finds and instantiates DCG core generators.
   *
   * @return \Symfony\Component\Console\Command\Command[]
   *   Array of generators.
   */
  public function getGenerators(): array {
    $commands = [];

    $iterator = new \RecursiveIteratorIterator(
      new \RecursiveDirectoryIterator(self::DIRECTORY, \FilesystemIterator::SKIP_DOTS),
    );
    foreach ($iterator as $file) {
      if ($file->getExtension() !== 'php') {
        continue;
      }

      $sub_path = $iterator->getInnerIterator()->getSubPath();
      $sub_namespace = $sub_path ? \str_replace(\DIRECTORY_SEPARATOR, '\\', $sub_path) . '\\' : '';
      $class = self::NAMESPACE . '\\' . $sub_namespace . $file->getBasename('.php');

      $reflected_class = new \ReflectionClass($class);

      if ($reflected_class->isInterface() || $reflected_class->isAbstract() || $reflected_class->isTrait()) {
        continue;
      }

      // @todo Update this once we remove LegacyGenerator class.
      if (!$reflected_class->isSubclassOf(BaseGenerator::class) && !$reflected_class->isSubclassOf(LegacyGenerator::class)) {
        continue;
      }

      $commands[] = $this->classResolver->getInstanceFromDefinition($class);
    }

    return $commands;
  }

}
