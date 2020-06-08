<?php

namespace Drupal\Tests\ui_patterns_layout\FunctionalJavascript;

use Drupal\Core\Entity\Entity\EntityViewDisplay;
use Drupal\FunctionalJavascriptTests\WebDriverTestBase;

/**
 * Test Layouts template settings UI.
 *
 * @group ui_patterns_layouts
 */
class UiPatternsLayoutsSettingsTest extends WebDriverTestBase {

  /**
   * Default theme.
   *
   * @var string
   */
  protected $defaultTheme = 'stark';

  /**
   * Disable schema validation when running tests.
   *
   * @var bool
   *
   * @todo: Fix this by providing actual schema validation.
   */
  protected $strictConfigSchema = FALSE;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'node',
    'field',
    'field_ui',
    'field_layout',
    'text',
    'ui_patterns',
    'ui_patterns_layouts',
    'ui_patterns_layouts_test',
    'ui_patterns_library',
  ];

  /**
   * Tests field template settings.
   */
  public function testUiPatternsLayoutsSettings() {
    $page = $this->getSession()->getPage();

    $user = $this->drupalCreateUser([], NULL, TRUE);
    $this->drupalLogin($user);

    // Visit Article's default display settings page.
    $this->drupalGet('/admin/structure/types/manage/article/display');

    // Click on Pattern settings.
    $page->pressButton('Layout settings');
    $page->pressButton('Pattern settings');

    // Select "Highlighted" field template.
    $page->selectFieldOption('Variant', 'Highlighted');

    $page->pressButton('Save');

    // Get default view mode for Article node bundle.
    $display = EntityViewDisplay::load("node.article.default");

    // Assert existence of third party settings.
    $third_party_settings = $display->getThirdPartySettings('field_layout');

    // Assert settings value.
    $this->assertEquals($third_party_settings['settings']['pattern']['variant'], 'highlighted');
  }

}
