<?php declare(strict_types = 1);

namespace DrupalCodeGenerator\Command;

use DrupalCodeGenerator\Application;
use DrupalCodeGenerator\Asset\Assets;
use DrupalCodeGenerator\Attribute\Generator;
use DrupalCodeGenerator\GeneratorType;

#[Generator(
  name: 'theme-settings',
  description: 'Generates Drupal theme-settings.php file',
  templatePath: Application::TEMPLATE_PATH . '/theme-settings',
  type: GeneratorType::THEME_COMPONENT,
)]
final class ThemeSettings extends BaseGenerator {

  protected function generate(array &$vars, Assets $assets): void {
    $ir = $this->createInterviewer($vars);
    $vars['machine_name'] = $ir->askMachineName();
    $vars['name'] = $ir->askName();

    $assets->addFile('theme-settings.php', 'form');
    $assets->addFile('config/install/{machine_name}.settings.yml', 'config');
    $assets->addFile('config/schema/{machine_name}.schema.yml', 'schema');
  }

}
