<?php

namespace Drupal\ui_patterns\Tests\Unit;

use function bovigo\assert\assert;
use function bovigo\assert\predicate\equals;
use Drupal\Component\Serialization\Yaml;
use Drupal\ui_patterns\Element\Pattern;

/**
 * @coversDefaultClass \Drupal\ui_patterns\Element\Pattern
 *
 * @group ui_patterns
 */
class PatternElementTest extends AbstractUiPatternsTest {

  /**
   * Test processLibraries.
   *
   * @covers ::processLibraries
   */
  public function testProcessLibraries() {
    $items = Yaml::decode(file_get_contents(dirname(__FILE__) . '/fixtures/pattern_element_libraries.yml'));

    foreach ($items as $item) {
      $element = [];
      $pattern = new Pattern([], '', '');
      // @codingStandardsIgnoreStart
      $pattern::$definition = $item['actual'];
      // @codingStandardsIgnoreEnd
      $element = $pattern::processLibraries($element);
      assert($element['#attached']['library'], equals($item['expected']));
    }
  }

}
