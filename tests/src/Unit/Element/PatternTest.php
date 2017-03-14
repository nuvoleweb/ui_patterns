<?php

namespace Drupal\Tests\ui_patterns\Unit\Element;

use function bovigo\assert\assert;
use function bovigo\assert\predicate\equals;
use Drupal\Component\Serialization\Yaml;
use Drupal\Tests\ui_patterns\Unit\AbstractUiPatternsTest;
use Drupal\ui_patterns\Element\Pattern;

/**
 * @coversDefaultClass \Drupal\ui_patterns\Element\Pattern
 *
 * @group ui_patterns
 */
class PatternTest extends AbstractUiPatternsTest {

  /**
   * Test getPreviewMarkup.
   *
   * @covers ::processFields
   * @covers ::processMultipleSources
   *
   * @dataProvider fieldsProvider
   */
  public function testMultipleSources($actual, $expected) {
    $actual = Pattern::processMultipleSources($actual);
    $result = Pattern::processFields($actual);
    assert($result, equals($expected));
  }

  /**
   * Data provider.
   */
  public function fieldsProvider() {
    return Yaml::decode(file_get_contents($this->getFixturePath() . '/pattern_multiple_sources.yml'));
  }

}
