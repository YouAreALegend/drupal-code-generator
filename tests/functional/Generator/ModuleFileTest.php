<?php declare(strict_types=1);

namespace DrupalCodeGenerator\Tests\Functional\Generator;

use DrupalCodeGenerator\Command\ModuleFile;
use DrupalCodeGenerator\Test\Functional\GeneratorTestBase;

/**
 * Tests module-file generator.
 */
final class ModuleFileTest extends GeneratorTestBase {

  protected string $fixtureDir = __DIR__ . '/_module_file';

  public function testGenerator(): void {

    $this->execute(new ModuleFile(), ['foo']);

    $expected_display = <<< 'TXT'

     Welcome to module-file generator!
    –––––––––––––––––––––––––––––––––––

     Module machine name [%default_name%]:
     ➤ 

     The following directories and files have been created or updated:
    –––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––––
     • foo.module

    TXT;
    $this->assertDisplay($expected_display);

    $this->assertGeneratedFile('foo.module');
  }

}
