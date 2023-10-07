<?php declare(strict_types = 1);

namespace Drupal\Tests\ui_patterns\Kernel;

use Drupal\KernelTests\KernelTestBase;

/**
 * Test description.
 *
 * @group ui_patterns
 */
final class SourceProviderPluginManagerTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['sdc', 'ui_patterns', 'ui_patterns_test'];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    // Mock necessary services here.
  }

  /**
   * Test callback.
   */
  public function testGetSourcePlugins(): void {
    /** @var \Drupal\sdc\ComponentPluginManager $sdc_plugin_manager */
    $sdc_plugin_manager = \Drupal::service('plugin.manager.sdc');
    $component_metadata = $sdc_plugin_manager->find('ui_patterns_test:alert');
    self::assertNotNull($component_metadata);
    /** @var \Drupal\ui_patterns\SourceProviderPluginManager $source_provider_plugin_manager */
    $source_provider_plugin_manager = \Drupal::service('plugin.manager.ui_patterns_source_provider');
    $source_providers = $source_provider_plugin_manager->getSourceProviders('');
    self::assertCount(3, count($source_providers));
  }

}
