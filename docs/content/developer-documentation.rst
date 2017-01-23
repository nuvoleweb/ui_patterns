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

.. image:: ../images/developer-1.png
   :align: center
   :width: 650

It is also possible to just render a pattern preview as displayed on the patterns overview page in the following way
(since fields are already bundled within the pattern definition we don't need to re-declare them here):

.. code-block:: php

   <?php
   $elements['quote'] = [
     '#type' => 'pattern_preview',
     '#id' => 'blockquote',
   ];

   \Drupal::service('renderer')->render($elements);


Rendering the code above will produce the following output:

.. image:: ../images/developer-2.png
   :align: center
   :width: 650

Expose source field plugins
===========================

When using a pattern on a view or an entity display form we are provided with a set of possible patterns source fields
that we can map to our pattern destination fields. Available source fields depends on the context in which a pattern is
being configured.

Pattern source fields are provided by plugins of type ``@UiPatternsSource``.

For example, when a pattern is used as a Views row template then the ``UiPatternsSourceManager`` collects all plugins
annotated with ``@UiPatternsSource`` and tagged by ``views_row``. A context array describing the current view is then
passed to each of the ``@UiPatternsSource`` plugins.

In the example below we can see the actual implementation of such a system:

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

At the moment the available source plugin tags are the following:

- ``entity_display``: provided by the ``ui_patterns`` module and triggered on an entity display configuration page.
- ``ds_field_template``: provided by the ``ui_patterns_ds`` module and triggered when setting up a field template
  on an entity display configuration page.
- ``views_row``: provided by the ``ui_patterns_views`` module and triggered on a Views row setting pane.
- ``test``: provided by the ``ui_patterns_test`` module and used in tests.
