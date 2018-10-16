<?php

namespace Drupal\Tests\ui_patterns\Unit\Definition;

use Drupal\Component\Serialization\Yaml;
use Drupal\Tests\ui_patterns\Unit\AbstractUiPatternsTest;
use Drupal\ui_patterns\Definition\PatternDefinition;

/**
 * @coversDefaultClass \Drupal\ui_patterns\Definition\PatternDefinition
 *
 * @group ui_patterns
 */
class PatternDefinitionTest extends AbstractUiPatternsTest {

  /**
   * Test getters.
   *
   * @dataProvider definitionGettersProvider
   */
  public function testGettersSetters($getter, $name, $value) {
    $pattern_definition = new PatternDefinition([$name => $value]);
    $this->assertEquals(call_user_func([$pattern_definition, $getter]), $value);
  }

  /**
   * Test field singleton.
   *
   * @dataProvider definitionGettersProvider
   */
  public function testFields() {
    $fields = [
      'name' => [
        'name' => 'name',
        'label' => 'Label',
      ],
    ];
    $pattern_definition = new PatternDefinition();
    $pattern_definition->setFields($fields);
    $this->assertEquals(
      [
        $fields['name']['label'],
        $fields['name']['name'],
        NULL,
        NULL,
        NULL,
      ],
      [
        $pattern_definition->getField('name')->getLabel(),
        $pattern_definition->getField('name')->getName(),
        $pattern_definition->getField('name')->getType(),
        $pattern_definition->getField('name')->getDescription(),
        $pattern_definition->getField('name')->getPreview(),
      ]);

    $pattern_definition->getField('name')->setType('type');
    $pattern_definition->getField('name')->setPreview('preview');
    $pattern_definition->getField('name')->setDescription('description');

    $this->assertEquals(
      [
        'type',
        'description',
        'preview',
      ],
      [
        $pattern_definition->getField('name')->getType(),
        $pattern_definition->getField('name')->getDescription(),
        $pattern_definition->getField('name')->getPreview(),
      ]);
  }

  /**
   * Test fields processing.
   *
   * @dataProvider fieldsProcessingProvider
   */
  public function testFieldsProcessing($actual, $expected) {
    $pattern_definition = new PatternDefinition();
    $data = $pattern_definition->setFields($actual)->toArray();
    $this->assertEquals($expected, $data['fields']);
  }

  /**
   * Provider.
   *
   * @return array
   *   Data.
   */
  public function fieldsProcessingProvider() {
    return Yaml::decode(file_get_contents($this->getFixturePath() . '/definition/fields_processing.yml'));
  }

  /**
   * Test fields processing.
   *
   * @dataProvider variantsProcessingProvider
   */
  public function testVariantsProcessing($actual, $expected) {
    $pattern_definition = new PatternDefinition();
    $data = $pattern_definition->setVariants($actual)->toArray();
    $this->assertEquals($expected, $data['variants']);
  }

  /**
   * Provider.
   *
   * @return array
   *   Data.
   */
  public function variantsProcessingProvider() {
    return Yaml::decode(file_get_contents($this->getFixturePath() . '/definition/variants_processing.yml'));
  }

  /**
   * Provider.
   *
   * @return array
   *   Data.
   */
  public function definitionGettersProvider() {
    return [
      ['getProvider', 'provider', 'my_module'],
      ['id', 'id', 'pattern_id'],
      ['getLabel', 'label', 'Pattern label'],
      ['getDescription', 'description', 'Pattern description.'],
      ['getUse', 'use', 'template.twig'],
      ['hasCustomThemeHook', 'custom theme hook', FALSE],
      ['getThemeHook', 'theme hook', 'eme hook: custom_my_theme_hook'],
      ['getTemplate', 'template', 'my-template.html.twig'],
      ['getFileName', 'file name', '/path/to/filename.ui_patterns.yml'],
      ['getClass', 'class', '\Drupal\ui_patterns\MyClass'],
      ['getBasePath', 'base path', '/path/to'],
      ['getTags', 'tags', ['a', 'b']],
    ];
  }

}
