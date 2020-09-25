<?php

namespace Drupal\Tests\ui_patterns_library\FunctionalJavascript;

/**
 * Test patterns overview page displays for non-default themes.
 *
 * @group ui_patterns_library
 */
class UiPatternsLibraryAlternativeThemeTest extends UiPatternsLibraryOverviewTest {

  /**
   * Default theme.
   *
   * @var string
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $active_theme = $this->container->get('theme.initialization')->initTheme('ui_patterns_library_theme_test');
    $this->container->get('theme.manager')->setActiveTheme($active_theme);
  }

}
