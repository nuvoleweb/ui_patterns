<?php declare(strict_types = 1);

namespace Drupal\Tests\ui_patterns\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\ui_patterns\SourcePluginBase;

/**
 * Test description.
 *
 * @group ui_patterns
 */
final class SourcePluginManagerTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['sdc', 'ui_patterns', 'ui_patterns_test'];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
  }

  /**
   * Test callback.
   */
  public function testGetSourcePlugins(): void {
    /** @var \Drupal\ui_patterns\SourcePluginManager $source_provider_plugin_manager */
    $source_plugin_manager = \Drupal::service('plugin.manager.ui_patterns_source');
    $sources = $source_plugin_manager->getSourcePlugins('string', 'test', ['title' => 'test title']);
    /** @var SourcePluginBase $source */
    foreach ($sources as $source) {
      self::assertNotNull($source);
      self::assertInstanceOf(SourcePluginBase::class, $source);
      self::assertNotNull($source->getPropId());
      self::assertNotNull($source->getPropDefinition());
    }
    self::assertGreaterThan(1, $sources);
  }

}
