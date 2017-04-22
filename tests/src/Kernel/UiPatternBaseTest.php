<?php

namespace Drupal\Tests\ui_patterns\Kernel;

use function bovigo\assert\assert;
use function bovigo\assert\predicate\equals;
use Drupal\Component\Serialization\Yaml;
use Drupal\ui_patterns\UiPatternBase;

/**
 * @coversDefaultClass \Drupal\ui_patterns\UiPatternBase
 *
 * @group ui_patterns
 */
class UiPatternBaseTest extends AbstractUiPatternsTest {

  /**
   * Test hookLibraryInfoBuild.
   *
   * @covers ::hookLibraryInfoBuild
   */
  public function testHookLibraryInfoBuild() {
    $items = Yaml::decode(file_get_contents(dirname(__FILE__) . '/../fixtures/libraries.yml'));

    foreach ($items as $item) {
      $pattern = $this->getUiPatternBaseMock($item['actual']);

      /** @var \Drupal\ui_patterns\UiPatternBase $pattern */
      $libraries = $pattern->getLibraryDefinitions();
      assert($libraries, equals($item['expected']));
    }
  }

  /**
   * Get UiPatternBase mock.
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
    return $this->getMockForAbstractClass(UiPatternBase::class, [
      [],
      'plugin_id',
      $plugin_definition,
      \Drupal::service('app.root'),
      \Drupal::service('typed_data_manager'),
      \Drupal::service('module_handler'),
    ], '', TRUE, TRUE, TRUE, $methods);
  }

}
