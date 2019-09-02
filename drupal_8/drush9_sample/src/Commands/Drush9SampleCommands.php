<?php

namespace Drupal\drush9_sample\Commands;

use Drupal\node\Entity\Node;
use Drush\Commands\DrushCommands;

/**
 * A Drush commandfile.
 *
 * In addition to this file, you need a drush.services.yml
 * in root of your module, and a composer.json file that provides the name
 * of the services file to use.
 */
class Drush9SampleCommands extends DrushCommands {

  /**
   * Echos back hello with the argument provided.
   *
   * @param string $id
   *   Argument provided to the drush command.
   *
   * @command drush9_sample:nod_upd
   * @aliases n-upd
   * @options arr An option that takes multiple values.
   * @options msg Whether or not an extra message should be displayed to the
   *   user.
   * @usage drush9_sample:nod_upd id
   *   Display updated node and.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function nod_upd($id, $options = ['msg' => FALSE]) {
    $this->output()->writeln('Node ID# ' . $id . '! will be updated');
    $query = \Drupal::entityQuery('node');
    $query->condition('nid', $id, '=');
    $node_ids = $query->execute();

    $n = 0;
    $currentDate = date("d.m.y");
    foreach ($node_ids as $nodes) {
      $n++;

      $node = Node::load($nodes);
      $old_title = $node->getTitle();
      $node->set('title', $old_title . '_' . $currentDate);
      $node->save();
      $new_title = $node->getTitle();
      $this->output()->writeln('________________________________________');
      $this->output()->writeln('Node id# ' . $id . ' have title "' . $old_title . '" UPDATED to "' . $new_title . '"');
    }
  }

  /**
   * Echos back hello with the argument provided.
   *
   *   Argument provided to the drush command.
   *
   * @command drush9_sample:nod_delete
   * @aliases ndel
   * @options arr An option that takes multiple values.
   * @usage drush9_sample:nod_delete
   *   Delete all nodes by c_type.
   */
  public function nod_delete() {
    $nodes = \Drupal::entityTypeManager()->getStorage('node')->loadByProperties(['type' => 'Article']);
    foreach ($nodes as $node) {
      $node->delete();
    }
  }

}
