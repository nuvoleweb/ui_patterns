<?php

namespace Drupal\Tests\ui_patterns\Kernel\Plugin;

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
   * @dataProvider hookLibraryInfoBuildDataProvider
   *
   * @covers ::getLibraryDefinitions
   */
  public function testHookLibraryInfoBuild($actual, $expected) {
    $pattern = $this->getUiPatternBaseMock($actual);
    /** @var \Drupal\ui_patterns\Plugin\PatternBase $pattern */
    $libraries = $pattern->getLibraryDefinitions();
    $this->assertEquals($expected, $libraries);
  }

  /**
   * Data provider for rendering tests.
   *
   * The actual data is read from fixtures stored in a YAML configuration.
   *
   * @return array
   *   A set of dump data for testing.
   */
  public function hookLibraryInfoBuildDataProvider() {
    return $this->getFixtureContent('libraries.yml');
  }

  /**
   * Get PatternBase mock.
   *
   * @param array $plugin_definition
   *   Plugin definition.
   * @param array $methods
   *   List of methods to mock.
   *
   * @return \PHPUnit\Framework\MockObject\MockObject
   *   Mock object.
   */
  protected function getUiPatternBaseMock(array $plugin_definition = [], array $methods = []) {
    return $this->getMockForAbstractClass(PatternBase::class, [
      [],
      'plugin_id',
      $plugin_definition,
      \Drupal::getContainer()->getParameter('app.root'),
      \Drupal::service('module_handler'),
    ], '', TRUE, TRUE, TRUE, $methods);
  }

}
