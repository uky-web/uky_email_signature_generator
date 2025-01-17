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

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return [
      'uky_email_signature_generator.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form = parent::buildForm($form, $form_state);
    $config = $this->config('uky_email_signature_generator.settings');

    $form['page_title'] = [
      '#type' => 'textfield',
      '#title' => t('Signature generator page title:'),
      '#default_value' => $config->get('uky_email_signature_generator.page_title'),
      '#required' => TRUE,
    ];

    $form['university_name'] = [
      '#type' => 'textfield',
      '#title' => t('University name:'),
      '#default_value' => $config->get('uky_email_signature_generator.university_name'),
    ];

    $form['instructions'] = [
      '#type' => 'text_format',
      '#format'=> 'full_html',
      '#title' => t('Signature copy instructions:'),
      '#default_value' => $config->get('uky_email_signature_generator.instructions')['value'],
      '#description' => t('Instructions that display below the signature preview for copying and using it in email clients.'),
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
    $config->set('uky_email_signature_generator.university_name', $form_state->getValue('university_name'));
    $config->set('uky_email_signature_generator.instructions', $form_state->getValue('instructions'));
    $config->save();
    return parent::submitForm($form, $form_state);
  }
}
