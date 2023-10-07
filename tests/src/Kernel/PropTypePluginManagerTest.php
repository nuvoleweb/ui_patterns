<?php declare(strict_types = 1);

namespace Drupal\Tests\ui_patterns\Kernel;

use Drupal\KernelTests\KernelTestBase;
use Drupal\ui_patterns\Plugin\UiPatterns\PropType\StringPropType;

/**
 * Test description.
 *
 * @group ui_patterns
 */
final class PropTypePluginManagerTest extends KernelTestBase {

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
  public function testGetPropTypePlugin(): void {
    /** @var \Drupal\ui_patterns\PropTypePluginManager $prop_type_plugin_manager */
    $prop_type_plugin_manager = \Drupal::service('plugin.manager.ui_patterns_prop_type');
    $plugin_type = $prop_type_plugin_manager->getPropTypePlugin(['type' => 'string']);
    self::assertInstanceOf(StringPropType::class, $plugin_type);
  }

}
