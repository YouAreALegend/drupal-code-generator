<?php declare(strict_types = 1);

namespace DrupalCodeGenerator\Command\Yml;

use DrupalCodeGenerator\Application;
use DrupalCodeGenerator\Asset\AssetCollection as Assets;
use DrupalCodeGenerator\Attribute\Generator;
use DrupalCodeGenerator\Command\BaseGenerator;
use DrupalCodeGenerator\GeneratorType;

#[Generator(
  name: 'yml:permissions',
  description: 'Generates a permissions yml file',
  aliases: ['permissions'],
  templatePath: Application::TEMPLATE_PATH . '/yml/permissions',
  type: GeneratorType::MODULE_COMPONENT,
)]
final class Permissions extends BaseGenerator {

  protected function generate(array &$vars, Assets $assets): void {
    $vars['machine_name'] = $this->createInterviewer($vars)->askMachineName();
    $assets->addFile('{machine_name}.permissions.yml', 'permissions');
  }

}
