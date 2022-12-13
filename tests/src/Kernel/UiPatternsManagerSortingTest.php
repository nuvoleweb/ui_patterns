<?php

namespace Drupal\Tests\ui_patterns\Kernel;

use Drupal\ui_patterns\UiPatterns;

/**
 * @coversDefaultClass \Drupal\ui_patterns\UiPatternsManager
 *
 * @group ui_patterns
 */
class UiPatternsManagerSortingTest extends AbstractUiPatternsTest {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'system',
    'ui_patterns',
    'ui_patterns_library',
    'ui_patterns_definitions_sort_test',
  ];

  /**
   * Test UiPatternsManager::getDefinitions.
   *
   * Source:
   * aaa: (Label 2)
   * bbb: Label 2
   * ccc: Label 1
   * lll: Label 3
   * zzz: Label 1
   *
   * Expected sort:
   * ccc
   * zzz
   * aaa
   * bbb
   * lll
   *
   * @covers ::getSortedDefinitions
   */
  public function testDefinitionsSorting() {
    $manager = UiPatterns::getManager();
    $definitions = $manager->getDefinitions();

    $keys = array_keys($definitions);
    $expected_keys_order = [
      'ccc',
      'zzz',
      'aaa',
      'bbb',
      'lll',
    ];

    $this->assertEquals($expected_keys_order, $keys);
  }

}
