<?php

namespace Drupal\Tests\ui_patterns_field_group\FunctionalJavascript;

use Drupal\FunctionalJavascriptTests\WebDriverTestBase;
use Drupal\Tests\ui_patterns\Traits\TwigDebugTrait;

/**
 * Test Field Group pattern rendering.
 *
 * @group ui_patterns_field_group
 */
class UiPatternsFieldGroupRenderTest extends WebDriverTestBase {

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

  use TwigDebugTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'node',
    'field',
    'field_group',
    'field_ui',
    'text',
    'ui_patterns_field_group_test',
  ];

  /**
   * Test that pattern field group settings are correctly saved.
   */
  public function testUiPatternsFieldGroupRendering() {
    $assert_session = $this->assertSession();

    $this->enableTwigDebugMode();

    $user = $this->drupalCreateUser([], NULL, TRUE);
    $this->drupalLogin($user);

    $node = $this->drupalCreateNode([
      'title' => 'Test article',
      'field_text' => 'Test text field',
      'type' => 'article',
    ]);

    $this->drupalGet($node->toUrl());

    // Assert correct variant suggestions.
    $suggestions = [
      'pattern-metadata--variant-first--field-group--group-pattern-group--node--article--default.html.twig',
      'pattern-metadata--variant-first--field-group--group-pattern-group--node--default.html.twig',
      'pattern-metadata--variant-first--field-group--group-pattern-group--node--article.html.twig',
      'pattern-metadata--variant-first--field-group--group-pattern-group--node.html.twig',
      'pattern-metadata--variant-first--field-group--group-pattern-group.html.twig',
      'pattern-metadata--variant-first--field-group.html.twig',

      'pattern-metadata--field-group--group-pattern-group--node--article--default.html.twig',
      'pattern-metadata--field-group--group-pattern-group--node--default.html.twig',
      'pattern-metadata--field-group--group-pattern-group--node--article.html.twig',
      'pattern-metadata--field-group--group-pattern-group--node.html.twig',
      'pattern-metadata--field-group--group-pattern-group.html.twig',
      'pattern-metadata--field-group.html.twig',

      'pattern-metadata--variant-first.html.twig',
      'pattern-metadata.html.twig',
    ];
    foreach ($suggestions as $suggestion) {
      $assert_session->responseContains($suggestion);
    }

    // Test field content is rendered in field group pattern.
    $assert_session->pageTextContains('Field 1: Text Test text field');
  }

}
