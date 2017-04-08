<?php

namespace Drupal\Tests\ui_patterns\Unit;

use function bovigo\assert\assert;
use function bovigo\assert\predicate\equals;
use Drupal\Component\Serialization\Yaml;
use Drupal\ui_patterns\UiPatternsManager;

/**
 * @coversDefaultClass \Drupal\ui_patterns\UiPatternsManager
 *
 * @group ui_patterns
 */
class UiPatternsManagerTest extends AbstractUiPatternsTest {

  /**
   * Test hookLibraryInfoBuild.
   *
   * @covers ::hookLibraryInfoBuild
   */
  public function testHookLibraryInfoBuild() {
    $items = Yaml::decode(file_get_contents(dirname(__FILE__) . '/../fixtures/libraries.yml'));

    foreach ($items as $item) {
      $manager = $this->createPartialMock(UiPatternsManager::class, ['getDefinitions']);
      $manager->method('getDefinitions')->willReturn([$item['actual']]);

      /** @var \Drupal\ui_patterns\UiPatternsManager $manager */
      $libraries = $manager->hookLibraryInfoBuild();
      assert($libraries, equals($item['expected']));
    }
  }

}
