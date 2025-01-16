<?php

namespace Drupal\uky_email_signature_generator\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\ScrollTopCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Renderer;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Class SignatureGeneratorForm.
 */
class SignatureGeneratorForm extends FormBase {
  protected Renderer $renderer;

  public function __construct(Renderer $renderer) {
    $this->renderer = $renderer;
  }

  public static function create(ContainerInterface $container): SignatureGeneratorForm {
    return new static(
      $container->get('renderer')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'signature_generator_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['first_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('First Name'),
      '#required' => TRUE,
    ];

    $form['last_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Last Name'),
      '#required' => TRUE,
    ];

    $form['credentials'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Credentials'),
    ];

    $form['pronouns'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Pronouns'),
    ];

    $form['position_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Position/Title'),
    ];

    $form['department_unit'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Department/Unit'),
    ];

    $form['sub_unit'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Sub-Unit'),
    ];

    $form['address'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Address'),
    ];

    $form['city_state_zip'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City, State ZIP'),
    ];

    $form['phone'] = [
      '#type' => 'tel',
      '#title' => $this->t('Phone'),
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email'),
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Generate Signature'),
      '#ajax' => [
        'event' => 'click',
        'callback' => '::ajaxSubmitCallback',
        'wrapper' => 'signature-generator',
      ],
    ];

    return $form;
  }

  /**
   * AJAX callback for the form submission.
   */
  public function ajaxSubmitCallback(array &$form, FormStateInterface $form_state) {
    $form_values = $form_state->getValues();
    $data = [
      '#theme' => 'signature_result',
      '#data' => [
        'name' => implode(' ', [$form_values['first_name'], $form_values['last_name']]),
        'creds' => $form_values['credentials'],
        'pronouns' => $form_values['pronouns'],
        'title' => $form_values['position_title'],
        'university' => 'University of Kentucky',
        'department' => $form_values['department_unit'],
        'sub_unit' => $form_values['sub_unit'],
        'address' => $form_values['address'],
        'city' => $form_values['city_state_zip'],
        'phone' => $form_values['phone'],
        'email' => $form_values['email'],
      ],
    ];

    $response = new AjaxResponse();
    // Scroll to top of form/page
    $response->addCommand(new ScrollTopCommand('.scroll-to'));

    if ($form_state->hasAnyErrors()) {
      // Display errors
      $form_state->setRebuild();
      $message = [
        '#theme' => 'status_messages',
        '#message_list' => [
          [
            '#message' => 'Please check that required fields are filled out.',
          ],
        ],
      ];
      $messages = \Drupal::service('renderer')->render($message);
      $response->addCommand(new HtmlCommand('#signature-generator-form', $form));
      $response->addCommand(new HtmlCommand('#form-messages', $messages));
      return $response;
    }

    // Build signature and clear any error messages
    $response->addCommand(new HtmlCommand('#result-wrapper', $this->renderer->render($data)));
    $response->addCommand(new InvokeCommand('#result-column', 'addClass', ['generated']));
    $response->addCommand(new HtmlCommand('#form-messages', ''));

    $form_state->setRebuild(FALSE);
    return $response;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    if (empty($form_state->getValue('first_name'))) {
      $form_state->setErrorByName('first_name', $this->t('First Name is required.'));
    }
    if (empty($form_state->getValue('last_name'))) {
      $form_state->setErrorByName('last_name', $this->t('Last Name is required.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    // Handle form submission.
    \Drupal::messenger()->addMessage($this->t('Signature generated for @first_name @last_name.', [
      '@first_name' => $form_state->getValue('first_name'),
      '@last_name' => $form_state->getValue('last_name'),
    ]));
  }
}
