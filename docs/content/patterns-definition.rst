Define you patterns
===================

UI patterns can be exposed by both modules and themes: all defined patterns will be collected and managed by a centralized
UI Pattern plugin manager.

Since the plugin manager uses a `YAML discovery method <https://www.drupal.org/docs/8/api/plugin-api/d8-plugin-discovery>`_
to define your patterns you simply create a YAML file named ``MY_MODULE.ui_patterns.yml`` or ``MY_THEME.ui_patterns.yml``
and list them using the following format:

.. code-block:: yaml

   blockquote:
     theme hook: blockquote_custom
     label: Blockquote
     description: Display a quote with attribution information.
     fields:
       quote:
         type: text
         label: Quote
         description: Quote text.
         preview: Life is like riding a bicycle. To keep your balance, you must keep moving.
       attribution:
         type: text
         label: Attribution
         description: Quote attribution.
         preview: Albert Einstein
     libraries:
       - module/library1
       - module/library2
