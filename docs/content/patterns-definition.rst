Define your patterns
====================

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

Let's break this down:

``id``
    The root of a new pattern definition (``blockquote`` in the example above). it must contain only lowercase
    characters, numbers and underscores (i.e. it should validate against ``[^a-z0-9_]+``).
``theme hook``
    If specified it overrides the automatically derived theme hook described above.
``label``
    Pattern label, used on pattern library page.
``description``
    Pattern description, used on pattern library page.
``fields``
    Hash defining the pattern fields. Each field must have the following properties defined below.

    ``type``
        Field type, can be ``text``, ``numeric``, etc. only for documentation purposes, at the moment.
    ``label``
        Field label,  used on pattern library page.
    ``description``
        Field description, used on pattern library page.
    ``preview``
        Preview content, used on pattern library page. It can be either a string or a Drupal render array.

``libraries``
    Libraries that are to be loaded when rendering the pattern. UI patterns are supposed to be
    self-contained so they should load along all libraries that are needed for a proper rendering.

The ``blockquote`` pattern defined above will be rendered in the pattern library as follow:

.. image:: ../_static/blockquote-preview.png
     :align: center

