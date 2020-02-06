<?php

namespace Drupal\contact_us\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Component\Utility\Xss;

/**
 * Provides "Contact us section" block.
 *
 * @Block(
 *  id = "contact_us_section_block",
 *  admin_label = @Translation("Contact us section block"),
 * )
 */
class ContactUsSectionBlock extends BlockBase implements BlockPluginInterface, ContainerFactoryPluginInterface {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a new UserLoginBlock instance.
   *
   * @param array $configuration
   *   The plugin configuration, i.e. an array with configuration values keyed
   *   by configuration option name. The special key 'context' may be used to
   *   initialize the defined contexts by setting it to an array of context
   *   values keyed by context names.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    ConfigFactoryInterface $config_factory
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
    $configuration,
    $plugin_id,
    $plugin_definition,
    $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'text_associate' => [
        'value' => '',
        'format' => 'rich_text',
      ],
      'button_text' => '',
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
    $form['text_associate'] = [
      '#type' => 'text_format',
      '#format' => 'rich_text',
      '#allowed_formats' => ['rich_text'],
      '#title' => $this->t('Text associate'),
      '#default_value' => $config['text_associate']['value'],
    ];
    $form['button_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Button text'),
      '#default_value' => $config['button_text'],
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);
    $values = $form_state->getValues();
    $this->configuration['text_associate'] = $values['text_associate'];
    $this->configuration['button_text'] = $values['button_text'];
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $config_contact_us = $this->configFactory->get('contact_us.settings');
    $build = [];
    $content = $this->getConfiguration();
    $open_window = $config_contact_us->get('open_window');
    if (!empty($open_window)) {
      $open_window = Xss::filter($config_contact_us->get('open_window'));
    }
    $build = [
      '#theme' => 'contact_us_section_block',
      '#content' => $content,
      '#open_window' => $open_window,
      '#cache' => [
        'max-age' => 0,
      ],
    ];
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }

}
