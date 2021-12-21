diff --git a/modules/ui_patterns_views/src/Plugin/views/row/Pattern.php b/modules/ui_patterns_views/src/Plugin/views/row/Pattern.php
index 2a1a299..3b8d285 100644
--- a/modules/ui_patterns_views/src/Plugin/views/row/Pattern.php
+++ b/modules/ui_patterns_views/src/Plugin/views/row/Pattern.php
@@ -142,11 +142,11 @@ class Pattern extends Fields {
    *
    * @see template_preprocess_pattern_views_row()
    */
-  public function isFieldVisible(FieldPluginBase $field, $field_output) {
+  public function isFieldVisible(FieldPluginBase $field, $field_name, $field_output) {
     $empty_value = $field->isValueEmpty($field_output, $field->options['empty_zero']);
     $hide_field = !$empty_value || (empty($field->options['hide_empty']) && empty($this->options['hide_empty']));
     $empty = empty($field->options['exclude']) && $hide_field;
-    return $empty && $this->hasMappingDestination('views_row', $field->field, $this->options);
+    return $empty && $this->hasMappingDestination('views_row', $field_name, $this->options);
   }
 
 }
diff --git a/modules/ui_patterns_views/ui_patterns_views.module b/modules/ui_patterns_views/ui_patterns_views.module
index 0b2ad4c..afa2b2a 100644
--- a/modules/ui_patterns_views/ui_patterns_views.module
+++ b/modules/ui_patterns_views/ui_patterns_views.module
@@ -39,7 +39,7 @@ function template_preprocess_pattern_views_row(array &$variables) {
     $field_name = $mapping['source'];
     $field = $view->field[$field_name];
     $field_output = $view->style_plugin->getField($row->index, $field_name);
-    if ($row_plugin->isFieldVisible($field, $field_output)) {
+    if ($row_plugin->isFieldVisible($field, $field_name, $field_output)) {
       $destination = $row_plugin->getMappingDestination('views_row', $field_name, $options);
       $fields[$destination][] = $field_output;
     }
