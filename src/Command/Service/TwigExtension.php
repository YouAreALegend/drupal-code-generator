<?php declare(strict_types = 1);

namespace DrupalCodeGenerator\Command\Service;

use DrupalCodeGenerator\Application;
use DrupalCodeGenerator\Asset\AssetCollection as Assets;
use DrupalCodeGenerator\Attribute\Generator;
use DrupalCodeGenerator\Command\BaseGenerator;
use DrupalCodeGenerator\GeneratorType;

#[Generator(
  name: 'service:twig-extension',
  description: 'Generates Twig extension service',
  aliases: ['twig-extension'],
  templatePath: Application::TEMPLATE_PATH . '/service/twig-extension',
  type: GeneratorType::MODULE_COMPONENT,
)]
final class TwigExtension extends BaseGenerator {

  protected function generate(array &$vars, Assets $assets): void {
    $ir = $this->createInterviewer($vars);
    $vars['machine_name'] = $ir->askMachineName();
    $vars['class'] = $ir->ask('Class', '{machine_name|camelize}TwigExtension');
    $vars['services'] = $ir->askServices();
    $assets->addFile('src/{class}.php', 'twig-extension');
    $assets->addServicesFile()->template('services');
  }

}
