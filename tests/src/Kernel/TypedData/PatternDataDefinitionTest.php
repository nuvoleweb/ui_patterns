<?php

namespace Drupal\Tests\ui_patterns\Kernel\TypedData;

use Drupal\Tests\ui_patterns\Kernel\AbstractUiPatternsTest;
use Drupal\ui_patterns\TypedData\PatternDataDefinition;

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
    $this->assertEquals($expected, $actual);
  }

  /**
   * Return validation data.
   *
   * @return array
   *   Pattern validation data.
   */
  public function validationProvider() {
    return $this->getFixtureContent('validation.yml');
  }

}
