<?php

namespace Drupal\Tests\ui_patterns\Kernel\TypedData;

use Drupal\Tests\ui_patterns\Kernel\AbstractUiPatternsTest;
use Drupal\ui_patterns\TypedData\PatternDataDefinition;
use Drupal\Component\Serialization\Yaml;

/**
 * @coversDefaultClass \Drupal\ui_patterns\TypedData\PatternDataDefinition
 *
 * @group ui_patterns
 */
class PatternDataDefinitionTest extends AbstractUiPatternsTest {

  /**
   * Test plugin validation.
   *
   * @dataProvider validationProvider
   */
  public function testValidation($data, $expected) {
    $definition = PatternDataDefinition::create();
    $violations = \Drupal::typedDataManager()->create($definition, $data)->validate();

    $actual = [];
    foreach ($violations as $violation) {
      $actual[] = $violation->getPropertyPath() . ': ' . $violation->getMessage();
    }
    expect($actual)->to->equal($expected);
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
