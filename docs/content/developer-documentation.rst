Developer documentation
-----------------------

Render patterns programmatically
================================

Patterns can be rendered programmatically by using the following syntax:

.. code-block:: php

   <?php
   $elements['quote'] = [
     '#type' => 'pattern',
     '#id' => 'blockquote',
     '#fields' => [
       'quote' => 'You must do the things you think you cannot do.',
       'attribution' => 'Eleanor Roosevelt'
     ]
   ];

   \Drupal::service('renderer')->render($elements);

The code above will produce the following result:

.. image:: ../_static/developer-1.png
   :align: center
   :width: 650

It is also possible to just render the pattern preview as show on the patterns overview page in the following way (since
fields are already provided in the pattern definition you don't need to declare them):

.. code-block:: php

   <?php
   $elements['quote'] = [
     '#type' => 'pattern_preview',
     '#id' => 'blockquote',
   ];

   \Drupal::service('renderer')->render($elements);


Rendering the code above will produce the following output:

.. image:: ../_static/developer-2.png
   :align: center
   :width: 650

Expose source field plugins
===========================

When configuring a pattern in a view or on an entity display form you are provided with a set of source fields that you
can map into your pattern's fields. Available source fields depends on the context in which a pattern is configured.

In order to provide custom source fields to your patterns you must provide a ``@UiPatternsSource`` plugin.

For example, when a pattern is used as a views row template then the ``UiPatternsSourceManager`` collects all plugins
annotated with ``@UiPatternsSource`` and tagged by ``views_row``.

In the example below you can see an actual implementation of such system:

.. code-block:: php

   <?php

   namespace Drupal\ui_patterns_views\Plugin\UiPatterns\Source;

   use Drupal\ui_patterns\Plugin\UiPatternsSourceBase;

   /**
    * Defines Views row pattern source plugin.
    *
    * @UiPatternsSource(
    *   id = "views_row",
    *   label = @Translation("Views row"),
    *   provider = "views",
    *   tags = {
    *     "views_row"
    *   }
    * )
    */
   class ViewsRowSource extends UiPatternsSourceBase {

     /**
      * {@inheritdoc}
      */
     public function getSourceFields() {
       $sources = [];
       /** @var \Drupal\views\ViewExecutable $view */
       $view = $this->getContextProperty('view');
       foreach ($view->display_handler->getFieldLabels() as $name => $label) {
         $sources[] = $this->getSourceField($name, $label);
       }
       return $sources;
     }

   }

At the moment the available source plugin contexts are the following:

- ``entity_display``: provided by the ``ui_patterns`` module and triggered on an entity display configuration page.
- ``ds_field_template``: provided by the ``ui_patterns_ds`` module and triggered when setting up a field template
  on an entity display configuration page.
- ``views_row``: provided by the ``ui_patterns_views`` module and triggered when setting up a views row.
- ``test``: provided by the ``ui_patterns_test`` module and used on tests.
