<?php

namespace Drupal\Tests\ui_patterns\Kernel\Plugin\Deriver;

use Drupal\Tests\ui_patterns\Kernel\AbstractUiPatternsTest;
use Drupal\ui_patterns\UiPatterns;

/**
 * @coversDefaultClass \Drupal\ui_patterns\Plugin\Deriver\AbstractYamlPatternsDeriver
 *
 * @group ui_patterns
 */
class YamlDeriverTest extends AbstractUiPatternsTest {

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
   * Test get derivative definitions.
   *
   * @covers ::getDerivativeDefinitions
   */
  public function testGetDerivativeDefinitions() {
    UiPatterns::getManager()->clearCachedDefinitions();
    foreach (UiPatterns::getManager()->getDefinitions() as $definition) {
      $this->assertNotEmpty($definition->id(), 'Pattern definition id is empty');
      $this->assertNotEmpty($definition->getProvider(), 'Pattern definition provider is empty');
      $this->assertNotEmpty($definition->getBasePath(), 'Pattern definition base path is empty');
    }
  }

}
