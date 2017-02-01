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
    return realpath(dirname(__FILE__) . '/fixtures/' . $name . '/');
  }

  /**
   * Get ModuleHandler mock.
   *
   * @return \Drupal\Core\Extension\ModuleHandlerInterface
   *    ModuleHandler mock.
   */
  protected function getModuleHandlerMock() {
    $module_handler = $this->createMock('Drupal\Core\Extension\ModuleHandlerInterface');
    $module_handler->method('getModuleDirectories')->willReturn($this->getModuleDirectoriesMock());

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
    $extension->method('getPath')->willReturn($this->getExtensionsPath('module'));

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
    $theme_handler->method('getThemeDirectories')->willReturn($this->getDefaultAndBaseThemesDirectoriesMock());
    $theme_handler->method('themeExists')->willReturn(TRUE);
    $theme_handler->method('getDefault')->willReturn('theme');
    $theme_handler->method('listInfo')->willReturn([]);
    $theme_handler->method('getBaseThemes')->willReturn([
      'base_theme' => new \stdClass(),
    ]);

    /** @var \Drupal\Core\Extension\ThemeHandlerInterface $theme_handler */
    return $theme_handler;
  }

  /**
   * Get YamlDiscovery mock.
   *
   * @return \Drupal\ui_patterns\Discovery\YamlDiscovery
   *   YamlDiscovery mock.
   */
  protected function getYamlDiscoveryMock() {
    $discovery = $this->getMockBuilder('Drupal\ui_patterns\Discovery\YamlDiscovery')
      ->setConstructorArgs([
        'ui_patterns',
        $this->getModuleDirectoriesMock() + $this->getDefaultAndBaseThemesDirectoriesMock(),
      ])
      ->setMethods(['fileScanDirectory'])
      ->getMock();
    $discovery->method('fileScanDirectory')
      ->willReturnCallback([$this, 'fileScanDirectoryMock']);

    /** @var \Drupal\ui_patterns\Discovery\YamlDiscovery $discovery */
    return $discovery;
  }

  /**
   * ModuleHandlerInterface::getModuleDirectories method mock.
   *
   * @return array
   *   Array with module names as keys and full paths as values.
   */
  protected function getModuleDirectoriesMock() {
    return [
      'module' => $this->getExtensionsPath('module'),
    ];
  }

  /**
   * UiPatternsDiscovery::getDefaultAndBaseThemesDirectories method mock.
   *
   * @return array
   *   Array with theme names as keys and full paths as values.
   */
  protected function getDefaultAndBaseThemesDirectoriesMock() {
    return [
      'theme' => $this->getExtensionsPath('theme'),
      'base_theme' => $this->getExtensionsPath('base_theme'),
    ];
  }

  /**
   * Get Loader mock.
   *
   * @return \Twig_Loader_Chain
   *    Loader mock.
   */
  protected function getLoaderMock() {
    $loader = $this->getMockBuilder('Twig_Loader_Chain')
      ->disableOriginalConstructor()
      ->getMock();

    /** @var \Twig_Loader_Chain $loader */
    return $loader;
  }

  /**
   * Get UI Pattern validation service mock.
   *
   * @return \Drupal\ui_patterns\UiPatternsValidation
   *    UI Pattern validation service.
   */
  protected function getValidationMock() {
    $validation = $this->getMockBuilder('Drupal\ui_patterns\UiPatternsValidation')
      ->disableOriginalConstructor()
      ->setMethods(['validate'])
      ->getMock();

    /** @var \Drupal\ui_patterns\UiPatternsValidation $validation */
    return $validation;
  }

  /**
   * YamlDiscovery::fileScanDirectory method mock.
   *
   * @return array
   *   Array keyed with full file paths of all definition files.
   */
  public function fileScanDirectoryMock($dir) {
    $files = [];

    switch ($dir) {
      case $this->getExtensionsPath('module'):
        $files[] = $this->getExtensionsPath('module') . '/module.ui_patterns.yml';
        break;

      case $this->getExtensionsPath('theme'):
        $files = [
          $this->getExtensionsPath('theme') . '/theme.ui_patterns.yml',
          $this->getExtensionsPath('theme') . '/pattern/pattern.ui_patterns.yml',
        ];
        break;
    }

    $return = [];
    foreach ($files as $file) {
      $return[$file] = (object) ['uri' => $file];
    }

    return $return;
  }

}
