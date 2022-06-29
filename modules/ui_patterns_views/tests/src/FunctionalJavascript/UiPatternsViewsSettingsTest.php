<?php

namespace Drupal\Tests\ui_patterns_views\FunctionalJavascript;

use Drupal\FunctionalJavascriptTests\WebDriverTestBase;
use Drupal\views\Entity\View;

/**
 * Test that UI Patterns Views display formatter can be properly configured.
 *
 * @group ui_patterns_field_group
 */
class UiPatternsViewsSettingsTest extends WebDriverTestBase {

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
   * @todo Fix this by providing actual schema validation.
   */
  protected $strictConfigSchema = FALSE;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'field',
    'ui_patterns_views_test',
  ];

  /**
   * Test that pattern field group settings are correctly saved.
   */
  public function testUiPatternsViewsSettings() {
    $page = $this->getSession()->getPage();
    $assert_session = $this->assertSession();

    $user = $this->drupalCreateUser([], NULL, TRUE);
    $this->drupalLogin($user);

    // Visit Articles views setting page.
    $this->drupalGet('/admin/structure/views/view/articles');

    // Access row style settings.
    $page->clickLink('Change settings for this style');
    $assert_session->assertWaitOnAjaxRequest();

    // Configure row style.
    $page->selectFieldOption('Variant', 'Highlighted');
    $page->selectFieldOption('Destination for Content: Title', 'Description');

    // Submit row style settings.
    $page->find('css', '.ui-dialog-buttonpane .form-actions')->pressButton('Apply');
    $assert_session->assertWaitOnAjaxRequest();

    // Save view.
    $page->find('css', '#edit-actions')->pressButton('Save');

    $view = View::load('articles');
    $settings = $view->getDisplay('default')['display_options']['row']['options'];

    // Assert settings values.
    $this->assertEquals($settings['pattern'], 'teaser');
    $this->assertEquals($settings['pattern_variant'], 'highlighted');

    // Assert mappings.
    $this->assertNotEmpty($settings['pattern_mapping'], "Pattern mapping is empty.");

    $mapping = $settings['pattern_mapping'];
    $this->assertArrayHasKey('views_row:title', $mapping, 'Mapping not found.');
    $this->assertEquals($mapping['views_row:title']['destination'], 'description', "Mapping not valid.");
  }

}
