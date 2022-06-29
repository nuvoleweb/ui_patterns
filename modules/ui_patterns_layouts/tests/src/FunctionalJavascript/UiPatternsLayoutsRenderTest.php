<?php

namespace Drupal\Tests\ui_patterns_layout\FunctionalJavascript;

use Drupal\FunctionalJavascriptTests\WebDriverTestBase;
use Drupal\Tests\ui_patterns\Traits\TwigDebugTrait;

/**
 * Test Layouts template rendering.
 *
 * @group ui_patterns_layouts
 */
class UiPatternsLayoutsRenderTest extends WebDriverTestBase {

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

  use TwigDebugTrait;

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
  public function testUiPatternsLayoutsRendering() {
    $this->enableTwigDebugMode();
    $this->drupalLogin($this->drupalCreateUser([], NULL, TRUE));

    $node = $this->drupalCreateNode([
      'title' => 'Test article',
      'body' => 'Test body',
      'type' => 'article',
    ]);

    $this->drupalGet($node->toUrl());

    $assert_session = $this->assertSession();

    // Assert correct variant suggestions.
    $suggestions = [
      'pattern-one-column--variant-default--layout--node--1.html.twig',
      'pattern-one-column--variant-default--layout--node--article--full.html.twig',
      'pattern-one-column--variant-default--layout--node--full.html.twig',
      'pattern-one-column--variant-default--layout--node--article.html.twig',
      'pattern-one-column--variant-default--layout--node.html.twig',
      'pattern-one-column--variant-default--layout.html.twig',

      'pattern-one-column--layout--node--1.html.twig',
      'pattern-one-column--layout--node--article--full.html.twig',
      'pattern-one-column--layout--node--full.html.twig',
      'pattern-one-column--layout--node--article.html.twig',
      'pattern-one-column--layout--node.html.twig',
      'pattern-one-column--layout.html.twig',

      'pattern-one-column--variant-default.html.twig',
      'pattern-one-column.html.twig',
    ];
    foreach ($suggestions as $suggestion) {
      $assert_session->responseContains($suggestion);
    }

    // Test content is rendered in the pattern.
    $assert_session->elementContains('css', 'article', 'Test body');
  }

}
