<?php declare(strict_types = 1);

namespace DrupalCodeGenerator\Command\Yml;

use DrupalCodeGenerator\Application;
use DrupalCodeGenerator\Asset\AssetCollection as Assets;
use DrupalCodeGenerator\Attribute\Generator;
use DrupalCodeGenerator\Command\BaseGenerator;
use DrupalCodeGenerator\GeneratorType;

#[Generator(
  name: 'yml:services',
  description: 'Generates a services yml file',
  aliases: ['services'],
  templatePath: Application::TEMPLATE_PATH . '/yml/services',
  type: GeneratorType::MODULE_COMPONENT,
)]
final class Services extends BaseGenerator {

  protected function generate(array &$vars, Assets $assets): void {
    $ir = $this->createInterviewer($vars);
    $vars['machine_name'] = $ir->askMachineName();
    $vars['name'] = $ir->askName();
    $vars['class'] = '{machine_name|camelize}';
    $assets->addFile('{machine_name}.services.yml', 'services');
  }

}
