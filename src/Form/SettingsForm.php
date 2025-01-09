<?php

namespace Drupal\uky_email_signature_generator\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Module settings form.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'uky_email_signature_generator_settings_form';
  }

  protected function getEditableConfigNames(): array {
    return [
      'uky_email_signature_generator.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    // Form constructor.
    $form = parent::buildForm($form, $form_state);
    // Default settings.
    $config = $this->config('uky_email_signature_generator.settings');

    // Page title field
    $form['page_title'] = [
      '#type' => 'textfield',
      '#title' => t('Signature generator page title:'),
      '#default_value' => $config->get('uky_email_signature_generator.page_title'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    if ($form_state->getValue('page_title') == NULL) {
      $form_state->setErrorByName('page_title', t('Please enter a valid Page title.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('uky_email_signature_generator.settings');
    $config->set('uky_email_signature_generator.page_title', $form_state->getValue('page_title'));
    $config->save();
    return parent::submitForm($form, $form_state);
  }
}
