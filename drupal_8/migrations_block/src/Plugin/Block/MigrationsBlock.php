<?php

namespace Drupal\migrations_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides Migrations block.
 *
 * @Block(
 *  id = "migrations_block",
 *  admin_label = @Translation("Migrations block"),
 * )
 * 
 */
class MigrationsBlock extends BlockBase implements BlockPluginInterface {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'label' => 'Lorem ipsum dolor sit amet',
      'message' => [
        'format' => 'full_html',
        'value' => 'Ut enim ad minim veniam quis nostrud exercitation ullamco.',
      ],
      'sub_message' => [
        'format' => 'full_html',
        'value' => 'Consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.',
      ],
    ];
  }
  
  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);
    $form['label_display']['#default_value'] = FALSE;
    return $form;
  }
  
  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $config = $this->getConfiguration();
    $form['message'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Message'),
      '#default_value' => $config['message']['value'],
    ];
    $form['sub_message'] = [
      '#type' => 'text_format',
      '#title' => $this->t('Sub Message'),
      '#default_value' => $config['sub_message']['value'],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    $values = $form_state->getValues();
    $this->configuration['message'] = $values['message'];
    $this->configuration['sub_message'] = $values['sub_message'];
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $content = $this->getConfiguration();
    return [
      '#theme' => 'migrations_block',
      '#content' => $content,
      '#cache' => ['max-age' => 0],
      '#attached' => [
        'library' => [
          'migrations_block/main',
        ],
      ],
    ];
  }

}
