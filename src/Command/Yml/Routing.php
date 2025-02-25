<?php declare(strict_types = 1);

namespace DrupalCodeGenerator\Command\Yml;

use DrupalCodeGenerator\Application;
use DrupalCodeGenerator\Asset\AssetCollection as Assets;
use DrupalCodeGenerator\Attribute\Generator;
use DrupalCodeGenerator\Command\BaseGenerator;
use DrupalCodeGenerator\GeneratorType;

#[Generator(
  name: 'yml:routing',
  description: 'Generates a routing yml file',
  aliases: ['routing'],
  templatePath: Application::TEMPLATE_PATH . '/yml/routing',
  type: GeneratorType::MODULE_COMPONENT,
)]
final class Routing extends BaseGenerator {

  protected function generate(array &$vars, Assets $assets): void {
    $ir = $this->createInterviewer($vars);
    $vars['machine_name'] = $ir->askMachineName();
    $vars['name'] = $ir->askName();
    $vars['class'] = '{machine_name|camelize}Controller';
    $assets->addFile('{machine_name}.routing.yml', 'routing');
  }

}
