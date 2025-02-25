<?php declare(strict_types = 1);

namespace DrupalCodeGenerator\Command;

use DrupalCodeGenerator\Helper\DrupalContext;
use DrupalCodeGenerator\Utils;
use DrupalCodeGenerator\Validator\Chained;
use DrupalCodeGenerator\Validator\MachineName;
use DrupalCodeGenerator\Validator\Required;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for Drupal generators.
 */
abstract class DrupalGenerator extends LegacyGenerator {

  public const EXTENSION_TYPE_MODULE = 0x01;
  public const EXTENSION_TYPE_THEME = 0x02;
  public const EXTENSION_TYPE_PROFILE = 0x03;

  /**
   * Name question.
   */
  protected ?string $nameQuestion = 'Extension name';

  /**
   * Machine name question.
   */
  protected ?string $machineNameQuestion = 'Extension machine name';

  /**
   * Indicates that generated code is a new Drupal extension.
   *
   * Most of Drupal generators fall into two categories:
   *  - new extensions (i.e. module, profile, theme)
   *  - components for existing extensions (i.e. controller, form, service, etc)
   * This flag makes the distinction. It is used for setting autocompleter
   * values for the extension name and defining correct destination for
   * generated code.
   */
  protected bool $isNewExtension = FALSE;

  /**
   * Extension type (module, theme, profile).
   */
  protected ?int $extensionType = NULL;

  /**
   * Drupal context.
   *
   * This helper is set if Drupal is fully bootstrapped.
   */
  protected DrupalContext $drupalContext;

  /**
   * Constructs the generator.
   */
  public function __construct(protected ContainerInterface $container) {
    parent::__construct();
  }

  public static function create(ContainerInterface $container): static {
    return new static($container);
  }

  /**
   * {@inheritdoc}
   */
  protected function initialize(InputInterface $input, OutputInterface $output): void {
    parent::initialize($input, $output);

    $this->drupalContext = $this->getHelper('drupal_context');

    // Set working directory to extension root.
    if (!$this->isNewExtension) {
      $this->directory = Utils::getExtensionRoot($this->directory) ?: $this->directory;
    }
  }

  /**
   * Collects default variables.
   */
  protected function collectDefault(array &$vars): void {
    // If Drupal context is available it is quite possible that we can provide
    // the extension name without interacting with a user.
    if (!$this->isNewExtension) {
      $vars['machine_name'] = $this->askMachineName($vars);
      $vars['name'] = $this->getExtensionList()[$vars['machine_name']]
        ?? Utils::machine2human($vars['machine_name']);
    }
    else {
      if ($this->nameQuestion) {
        $vars['name'] = $this->askName();
      }
      if ($this->machineNameQuestion) {
        $vars['machine_name'] = $this->askMachineName($vars);
      }
    }
  }

  /**
   * Asks name question.
   */
  protected function askName(): string {
    $question = new Question($this->nameQuestion);
    $question->setValidator(new Required());
    if (!$this->isNewExtension && $extensions = $this->getExtensionList()) {
      $question->setAutocompleterValues($extensions);
    }
    return $this->io()->askQuestion($question);
  }

  /**
   * Asks machine name question.
   */
  protected function askMachineName(array $vars): string {
    $default_value = isset($vars['name']) ? Utils::human2machine($vars['name']) : NULL;
    $question = new Question($this->machineNameQuestion, $default_value);
    $question->setValidator(new Chained(new Required(), new MachineName()));
    if (!$this->isNewExtension && $extensions = $this->getExtensionList()) {
      $question->setAutocompleterValues(\array_keys($extensions));
    }
    return $this->io()->askQuestion($question);
  }

  /**
   * Returns extension list.
   *
   * @return \Drupal\Core\Extension\Extension[]
   *   An associative array whose keys are the machine names of the extensions
   *   and whose values are extension names.
   */
  protected function getExtensionList(): array {
    return match ($this->extensionType) {
      DrupalGenerator::EXTENSION_TYPE_MODULE => $this->getHelper('module_info')->getExtensions(),
      DrupalGenerator::EXTENSION_TYPE_THEME => $this->getHelper('theme_info')->getExtensions(),
    };
  }

  /**
   * {@inheritdoc}
   */
  protected function getDestination(array $vars): ?string {
    // @todo Figure out the case when machine name is not provided.
    return match ($this->extensionType) {
      DrupalGenerator::EXTENSION_TYPE_MODULE => $this->getHelper('module_info')
        ->getDestination($vars['machine_name'], $this->isNewExtension),
      DrupalGenerator::EXTENSION_TYPE_THEME => $this->getHelper('theme_info')
        ->getDestination($vars['machine_name'], $this->isNewExtension),
      default => parent::getDestination($vars),
    };
  }

}
