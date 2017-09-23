<?php

namespace Drupal\Tests\ui_patterns\Kernel\Plugin;

use Drupal\Component\Serialization\Yaml;
use Drupal\Tests\ui_patterns\Kernel\AbstractUiPatternsTest;
use Drupal\ui_patterns\Plugin\PatternBase;

/**
 * @coversDefaultClass \Drupal\ui_patterns\Plugin\PatternBase
 *
 * @group ui_patterns
 */
class PatternBaseTest extends AbstractUiPatternsTest {

  /**
   * Test hookLibraryInfoBuild.
   *
   * @covers ::hookLibraryInfoBuild
   */
  public function testHookLibraryInfoBuild() {
    $items = Yaml::decode(file_get_contents($this->getFixturePath() . '/libraries.yml'));

    foreach ($items as $item) {
      $pattern = $this->getUiPatternBaseMock($item['actual']);

      /** @var \Drupal\ui_patterns\Plugin\PatternBase $pattern */
      $libraries = $pattern->getLibraryDefinitions();
      expect($libraries)->to->loosely->equal($item['expected']);
    }
  }

  /**
   * Get PatternBase mock.
   *
   * @param array $plugin_definition
   *    Plugin definition.
   * @param array $methods
   *    List of methods to mock.
   *
   * @return \PHPUnit_Framework_MockObject_MockObject
   *    Mock object.
   */
  protected function getUiPatternBaseMock(array $plugin_definition = [], array $methods = []) {
    return $this->getMockForAbstractClass(PatternBase::class, [
      [],
      'plugin_id',
      $plugin_definition,
      \Drupal::service('app.root'),
      \Drupal::service('module_handler'),
    ], '', TRUE, TRUE, TRUE, $methods);
  }

}
