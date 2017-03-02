<?php

namespace Drupal\ss_location\Plugin\Layout;

use Drupal\layout_plugin\Plugin\Layout\LayoutBase;

/**
 * The plugin that handles the location view template.
 *
 * @ingroup layout_template_plugins
 *
 * @Layout(
 *   id = "page_layout_location_view",
 *   label = @Translation("Location View"),
 *   category = @Translation("Incisive Media"),
 *   description = @Translation("Location View Layout"),
 *   type = "page",
 *   help = @Translation("Layout"),
 *   template = "page-layout-location-view",
 *   regions = {
 *     "main" = {
 *       "label" = @Translation("Main content region"),
 *       "plugin_id" = "default"
 *     }
 *   }
 * )
 */
class Location extends LayoutBase {}
