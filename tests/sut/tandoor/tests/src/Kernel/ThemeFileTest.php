<?php declare(strict_types = 1);

namespace Drupal\Tests\tandoor\Kernel;

use Drupal\KernelTests\KernelTestBase;

/**
 * Tests theme file.
 *
 * @group DCG
 */
final class ThemeFileTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->container->get('theme_installer')->install(['azalea']);
    $this->container->get('extension.list.theme')->get('azalea')->load();
  }

  /**
   * Test callback.
   */
  public function testThemeFile(): void {
    // There is not a lot we can test here. Just call the preprocess functions
    // to make sure they are loaded.
    $variables = [];
    \azalea_preprocess_html($variables);
    \azalea_preprocess_page($variables);
    \azalea_preprocess_node($variables);
  }

}
