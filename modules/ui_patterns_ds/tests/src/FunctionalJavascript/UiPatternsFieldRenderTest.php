<?php

namespace Drupal\Tests\ui_patterns_ds\FunctionalJavascript;

use Drupal\FunctionalJavascriptTests\WebDriverTestBase;
use Drupal\Tests\ui_patterns\Traits\TwigDebugTrait;

/**
 * Test Display Suite field template rendering.
 *
 * @group ui_patterns_ds
 */
class UiPatternsFieldRenderTest extends WebDriverTestBase {

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
  public function testUiPatternsFieldRendering() {
    $assert_session = $this->assertSession();

    $this->enableTwigDebugMode();

    $user = $this->drupalCreateUser([], NULL, TRUE);
    $this->drupalLogin($user);

    $node = $this->drupalCreateNode([
      'title' => 'Test article',
      'body' => 'Test body',
      'type' => 'article',
    ]);

    $this->drupalGet($node->toUrl());

    // Assert correct variant suggestions.
    $suggestions = [
      'pattern-field--variant-default--ds-field-template--body--node--article--full.html.twig',
      'pattern-field--variant-default--ds-field-template--body--node--full.html.twig',
      'pattern-field--variant-default--ds-field-template--body--node--article.html.twig',
      'pattern-field--variant-default--ds-field-template--body--node.html.twig',
      'pattern-field--variant-default--ds-field-template--body.html.twig',
      'pattern-field--variant-default--ds-field-template.html.twig',

      'pattern-field--ds-field-template--body--node--article--full.html.twig',
      'pattern-field--ds-field-template--body--node--full.html.twig',
      'pattern-field--ds-field-template--body--node--article.html.twig',
      'pattern-field--ds-field-template--body--node.html.twig',
      'pattern-field--ds-field-template--body.html.twig',
      'pattern-field--ds-field-template.html.twig',

      'pattern-field--variant-default.html.twig',
      'pattern-field.html.twig',
    ];
    foreach ($suggestions as $suggestion) {
      $assert_session->responseContains($suggestion);
    }

    // Test content is rendered in the pattern.
    $assert_session->pageTextContains('Value: Test body');
  }

}
