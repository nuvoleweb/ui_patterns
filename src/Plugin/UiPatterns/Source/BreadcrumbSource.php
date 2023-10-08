<?php

declare(strict_types = 1);

namespace Drupal\ui_patterns\Plugin\UiPatterns\Source;

use Drupal\ui_patterns\SourcePluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the source_provider.
 *
 * @Source(
 *   id = "breadcrumb",
 *   label = @Translation("Breadcrumb"),
 *   description = @Translation("Foo description."),
 *   prop_types = {
 *     "links"
 *   }
 * )
 */
final class BreadcrumbSource extends SourcePluginBase {

  /**
   * The current route match.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * The breadcrumb manager.
   *
   * @var \Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface
   */
  protected $breadcrumbManager;

  /**
   *
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $plugin = new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('module_handler'),
      $container->get('entity_type.manager'),
    );
    /** @var \Drupal\Core\StringTranslation\TranslationInterface $translation */
    $translation = $container->get('string_translation');
    $plugin->setStringTranslation($translation);
    $plugin->breadcrumbManager = $container->get('breadcrumb');
    $plugin->routeMatch = $container->get('current_route_match');
    return $plugin;
  }

  /**
   *
   */
  public function getData(): mixed {
    $breadcrumb = $this->breadcrumbManager->build($this->routeMatch);
    $renderable = $breadcrumb->toRenderable();
    if (isset($renderable["#cache"])) {
      $this->cacheArray = $renderable["#cache"];
    }
    $links = [];
    foreach ($breadcrumb->getLinks() as $link) {
      $links[] = [
        "title" => $link->getText(),
        "url" => $link->getUrl()->toString(),
      ];
    }
    return $links;

    return [];
  }

  /**
   *
   */
  public function defaultConfiguration() {
    return [];
  }

}
