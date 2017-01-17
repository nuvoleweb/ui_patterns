<?php

namespace Drupal\ui_patterns\Tests\Unit;

use Drupal\Component\FileCache\FileCacheFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractUiPatternsTest.
 *
 * @package Drupal\ui_patterns\Tests\Unit
 */
abstract class AbstractUiPatternsTest extends TestCase {

  /**
   * Get full test extension path.
   *
   * @param string $name
   *    Test extension name.
   *
   * @return string
   *    Full test extension path.
   */
  protected function getExtensionsPath($name) {
    return realpath(dirname(__FILE__) . '/../../../tests/target/custom/' . $name);
  }

  /**
   * Get ModuleHandler mock.
   *
   * @return \Drupal\Core\Extension\ModuleHandlerInterface
   *    ModuleHandler mock.
   */
  protected function getModuleHandlerMock() {
    $module_handler = $this->createMock('Drupal\Core\Extension\ModuleHandlerInterface');
    $module_handler->method('getModuleDirectories')->willReturn([
      'ui_patterns_test' => $this->getExtensionsPath('ui_patterns_test'),
    ]);

    $extension = $this->getExtensionMock();
    $module_handler->method('getModule')->willReturn($extension);
    $module_handler->method('moduleExists')->willReturn(TRUE);

    /** @var \Drupal\Core\Extension\ModuleHandlerInterface $module_handler */
    return $module_handler;
  }

  /**
   * Get Extension mock.
   *
   * @return \Drupal\Core\Extension\Extension
   *    Extension mock.
   */
  protected function getExtensionMock() {
    $extension = $this->getMockBuilder('Drupal\Core\Extension\Extension')
      ->disableOriginalConstructor()
      ->getMock();
    $extension->method('getPath')->willReturn($this->getExtensionsPath('ui_patterns_test'));

    /** @var \Drupal\Core\Extension\Extension $extension */
    return $extension;
  }

  /**
   * Get CacheBackend mock.
   *
   * @return \Drupal\Core\Cache\CacheBackendInterface
   *    CacheBackend mock.
   */
  protected function getCacheBackendMock() {
    FileCacheFactory::setPrefix('something');
    $cache_backend = $this->createMock('Drupal\Core\Cache\CacheBackendInterface');

    /** @var \Drupal\Core\Cache\CacheBackendInterface $cache_backend */
    return $cache_backend;
  }

  /**
   * Get ThemeHandler mock.
   *
   * @return \Drupal\Core\Extension\ThemeHandlerInterface
   *    ThemeHandler mock.
   */
  protected function getThemeHandlerMock() {
    $theme_handler = $this->getMockBuilder('Drupal\Core\Extension\ThemeHandlerInterface')
      ->disableOriginalConstructor()
      ->getMock();
    $theme_handler->method('getThemeDirectories')->willReturn([
      'ui_patterns_test_theme' => $this->getExtensionsPath('ui_patterns_test_theme'),
    ]);
    $theme_handler->method('themeExists')->willReturn(TRUE);

    /** @var \Drupal\Core\Extension\ThemeHandlerInterface $theme_handler */
    return $theme_handler;
  }

  /**
   * Get ThemeManager mock.
   *
   * @return \Drupal\Core\Theme\ThemeManager
   *    ThemeManager mock.
   */
  protected function getThemeManagerMock() {
    $theme_manager = $this->getMockBuilder('Drupal\Core\Theme\ThemeManager')
      ->disableOriginalConstructor()
      ->getMock();

    /** @var \Drupal\Core\Theme\ThemeManager $theme_manager */
    return $theme_manager;
  }

}
