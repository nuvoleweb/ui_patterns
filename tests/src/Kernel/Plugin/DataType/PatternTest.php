<?php

namespace Drupal\Tests\ui_patterns\Kernel\Plugin\DataType;

use function bovigo\assert\assert;
use function bovigo\assert\predicate\equals;
use Drupal\Tests\ui_patterns\Kernel\AbstractUiPatternsTest;
use Drupal\ui_patterns\UiPatterns;
use Drupal\Component\Serialization\Yaml;

/**
 * @coversDefaultClass \Drupal\ui_patterns\Plugin\DataType\Pattern
 *
 * @group ui_patterns
 */
class PatternTest extends AbstractUiPatternsTest {

  /**
   * Test plugin validation.
   *
   * @dataProvider validationProvider
   */
  public function testValidation($data, $expected) {
    $pattern = UiPatterns::getPatternDefinition($data);
    $actual = [];
    foreach ($pattern->getErrorMessages() as $message) {
      $actual[] = $message->render();
    }
    assert($actual, equals($expected));
  }

  /**
   * Return validation data.
   *
   * @return array
   *    Pattern validation data.
   */
  public function validationProvider() {
    return Yaml::decode(file_get_contents($this->getFixturePath() . '/validation.yml'));
  }

}
