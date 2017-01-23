Use patterns with Layout Plugin
-------------------------------

The ``ui_patterns_layouts`` module integrates UI Patterns with the `Layout Plugin <https://www.drupal.org/project/layout_plugin>`_
module turning your patterns into layouts.

By simply enabling the module you'll have the possibility to use patterns to arrange fields on entities such as nodes,
users, `paragraphs <https://www.drupal.org/project/paragraphs>`_, etc. or to place blocks on a page using `Panels <https://www.drupal.org/project/panels>`_.

The example below we will style a **Jumbotron** paragraph using the Jumbotron pattern.

Once on the paragraph **Manage display** page you can choose the Jumbotron pattern as layout:

.. image:: ../images/layouts-1.png
   :align: center
   :width: 450

After doing that and saving the display the Jumbotron pattern fields will be exposed as layout regions, so given the
following definition:

.. code-block:: yaml

   jumbotron:
     label: Jumbotron
     description: A lightweight, flexible component that can optionally extend the entire viewport to showcase key content on your site.
     fields:
       title:
         type: text
         label: Title
         description: Jumbotron title.
         preview: Hello, world!
       subtitle:
         type: text
         label: Description
         description: Jumbotron description.
         preview: This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.

You'll get the following layout:

.. image:: ../images/layouts-2.png
   :align: center
   :width: 450

You can now arrange the paragraph fields on the layout and save your settings.

The paragraph below:

.. image:: ../images/layouts-3.png
   :align: center
   :width: 450

will be now styled using the Jumbotron pattern:

.. image:: ../images/layouts-4.png
   :align: center
   :width: 550

