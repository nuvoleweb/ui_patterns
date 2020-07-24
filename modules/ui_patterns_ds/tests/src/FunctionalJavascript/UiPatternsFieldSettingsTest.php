<?php

namespace Drupal\Tests\ui_patterns_ds\FunctionalJavascript;

use Drupal\Core\Entity\Entity\EntityViewDisplay;
use Drupal\FunctionalJavascriptTests\WebDriverTestBase;

/**
 * Test Display Suite field template settings UI.
 *
 * @group ui_patterns_ds
 */
class UiPatternsFieldSettingsTest extends WebDriverTestBase {

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
    'text',
    'ui_patterns',
    'ui_patterns_ds',
    'ui_patterns_library',
    'ui_patterns_ds_test',
  ];

  /**
   * Tests field template settings.
   */
  public function testUiPatternsFieldSettings() {
    $session = $this->getSession();
    $page = $session->getPage();
    $assert_session = $this->assertSession();

    $user = $this->drupalCreateUser([], NULL, TRUE);
    $this->drupalLogin($user);

    // Visit Article's default display settings page.
    $this->drupalGet('/admin/structure/types/manage/article/display');

    // Click on Body field display settings.
    $page->pressButton('body_plugin_settings_edit');
    $assert_session->assertWaitOnAjaxRequest();

    // Select "Pattern" field template.
    $page->selectFieldOption('Choose a Field Template', 'Pattern');
    $assert_session->assertWaitOnAjaxRequest();

    // Choose test pattern.
    $page->selectFieldOption('fields[body][settings_edit_form][third_party_settings][ds][ft][settings][pattern]', 'Field');
    $assert_session->assertWaitOnAjaxRequest();

    // Choose test variant.
    $page->selectFieldOption('Variant', 'Overridden');
    $assert_session->assertWaitOnAjaxRequest();

    // Map pattern fields.
    $page->selectFieldOption('Destination for Body', '- Hidden -');
    $page->selectFieldOption('Destination for Body: value', 'Value');
    $page->selectFieldOption('Destination for Body: format', 'Format');

    // Submit field settings.
    // @todo: Make sure values are persisted when re-editing the field settings.
    $page->pressButton('Update');
    $assert_session->assertWaitOnAjaxRequest();

    // Save view mode setting page.
    $page->pressButton('Save');

    // Get default view mode for Article node bundle.
    $display = EntityViewDisplay::load("node.article.default");

    // Assert existence of third party settings.
    $third_party_settings = $display->getComponent('body')['third_party_settings'];
    $this->assertNotEmpty($third_party_settings['ds']['ft'], "Field template settings not found.");

    // Assert settings value.
    $settings = $third_party_settings['ds']['ft'];
    $this->assertEquals($settings['id'], 'pattern');
    $this->assertEquals($settings['settings']['pattern'], 'field');
    $this->assertEquals($settings['settings']['pattern_variant'], 'overridden');

    // Assert mappings.
    $this->assertNotEmpty($settings['settings']['pattern_mapping'], "Pattern mapping is empty.");

    $mapping = $settings['settings']['pattern_mapping'];
    $this->assertArrayNotHasKey('ds_field_template:body', $mapping, "Body mapping found.");
    $this->assertArrayHasKey('ds_field_template:body__value', $mapping, "Body value mapping not found.");
    $this->assertArrayHasKey('ds_field_template:body__format', $mapping, "Body format mapping not found.");

    $this->assertEquals($mapping['ds_field_template:body__value']['destination'], 'value', "Body value mapping not valid.");
    $this->assertEquals($mapping['ds_field_template:body__format']['destination'], 'format', "Body format mapping not valid.");
  }

}
