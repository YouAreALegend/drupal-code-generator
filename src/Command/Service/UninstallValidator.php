<?php declare(strict_types = 1);

namespace DrupalCodeGenerator\Command\Service;

use DrupalCodeGenerator\Application;
use DrupalCodeGenerator\Asset\AssetCollection as Assets;
use DrupalCodeGenerator\Attribute\Generator;
use DrupalCodeGenerator\Command\BaseGenerator;
use DrupalCodeGenerator\GeneratorType;

#[Generator(
  name: 'service:uninstall-validator',
  description: 'Generates a uninstall validator service',
  aliases: ['uninstall-validator'],
  templatePath: Application::TEMPLATE_PATH . '/service/uninstall-validator',
  type: GeneratorType::MODULE_COMPONENT,
)]
final class UninstallValidator extends BaseGenerator {

  protected function generate(array &$vars, Assets $assets): void {
    $ir = $this->createInterviewer($vars);
    $vars['machine_name'] = $ir->askMachineName();
    $vars['name'] = $ir->askName();
    $vars['class'] = $ir->ask('Class', '{name|camelize}UninstallValidator');

    $assets->addFile('src/{class}.php', 'uninstall-validator');
    $assets->addServicesFile()->template('services');
  }

}
