<?php

/**
 * @file
 * This module provides a samples of Drush 8.x commands.
 */

use Drupal\Component\Serialization\Json;
use Drupal\node\Entity\Node;

/**
 * Implements hook_drush_command().
 */
function drush8_sample_drush_command() {
  $commands['simp-com'] = [
    'description' => 'Simple command to show message in terminal(console)',
    'aliases' => ['s-com'],
  ];
  $commands['nodes-update'] = [
    'description' => 'Update nodes title',
    'aliases' => ['n-upd'],
  ];
  $commands['post-parser'] = [
    'description' => 'Parse site with posts and create nodes',
    'aliases' => ['p-p'],
  ];
  return $commands;
}

/**
 * Implements drush_{module_name}_{item_id_for_command} ()
 */
function drush_drush8_sample_my_example_command($arg1 = 'Andrii', $arg2 = 'Dreichan') {
  $opt1 = drush_get_option('opt1', $arg2);
  $tokens = ['@arg1' => $arg1, '@opt1' => $opt1];
  $this->output()->writeln(dt('Hello, user! Your name: @arg1 @opt1', $tokens));
}

/**
 * Implement drush_{module_name}_{item_id_for_command}()
 */
function drush_drush8_sample_nodes_update() {
  $query = \Drupal::entityQuery('node');
  $node_ids = $query->execute();
  $n = 0;
  foreach ($node_ids as $nodes) {
    $n++;
    $node = Node::load($nodes);
    $old_title = $node->getTitle();
    $node->set('title', $old_title . '_updated');
    $node->save();
    $new_title = $node->getTitle();
    $this->output()->writeln('Nodes(titles) from current site, ' . $old_title . ' and UPDATED titles is - ' . $new_title);
  }
}

/**
 * Implements drush_{module_name}_{item_id_for_command} ()
 */
function drush_drush8_sample_post_parser() {
  $uri_to_site = "https://jsonplaceholder.typicode.com/posts/";
  $uri_to_post = $uri_to_site;

  $resp_from_site = \Drupal::httpClient()->get($uri_to_post, ['headers' => ['Accept' => 'application/json']]);
  $data_from_site = $resp_from_site->getBody();
  $rendered = Json::decode($data_from_site);

  foreach ($rendered as $nodes) {
    $pars_user_id = $nodes['userId'];
    $pars_id = $nodes['id'];
    $pars_title = $nodes['title'];
    $pars_body = $nodes['body'];

    $node = Node::create([
      'type' => 'parsed',
      'title' => $pars_title,
      'body' => $pars_body,
      'field_userid‎' => $pars_user_id,
      'field_article_id‎' => $pars_id,
    ]);
    $node->save();
    $this->output()->writeln('Node number _' . $pars_id . ' created. Creating next one node.');
  }
  $this->output()->writeln('_________________________________________');
  $this->output()->writeln('All needed nodes created from parsed site');
}
