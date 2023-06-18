<?php

namespace Drupal\Tests\ui_patterns\Kernel;

use Drupal\ui_patterns\UiPatterns;

/**
 * @coversDefaultClass \Drupal\ui_patterns\UiPatternsManager
 *
 * @group ui_patterns
 */
class UiPatternsManagerTest extends AbstractUiPatternsTest {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'system',
    'ui_patterns',
    'ui_patterns_library',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Theme with existing patterns has to be enabled.
    $default_theme = 'ui_patterns_library_theme_test';
    $this->container->get('theme_installer')->install([$default_theme]);
    $this->container->get('config.factory')->getEditable('system.theme')->set('default', $default_theme)->save();
  }

  /**
   * Test UiPatternsManager::getPatternDefinition.
   *
   * @covers ::getPatterns
   */
  public function testGetPattern() {
    $manager = UiPatterns::getManager();
    $definitions = $manager->getDefinitions();

    foreach ($manager->getPatterns() as $pattern) {
      $this->assertEquals($definitions[$pattern->getPluginId()]->id(), $pattern->getBaseId());
    }
  }

}
