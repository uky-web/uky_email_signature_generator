<?php
namespace Drupal\uky_email_signature_generator\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\AutowireTrait;
use Drupal\Core\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\uky_email_signature_generator\Form\SignatureGeneratorForm;

/**
 * Provides route responses for the Example module.
 */
class GeneratorFormController extends ControllerBase {
  /**
   * The form builder.
   *
   * @var FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * Constructs a GeneratorFormController object.
   *
   * @param FormBuilderInterface $form_builder
   *   The form builder.
   */
  public function __construct(FormBuilderInterface $form_builder) {
    $this->formBuilder = $form_builder;
  }

  public static function create(ContainerInterface $container): GeneratorFormController|static {
    return new static(
      $container->get('form_builder')
    );
  }

  public function getTitle(): string {
    return $this->config('uky_email_signature_generator.settings')
      ->get('uky_email_signature_generator.page_title');
  }

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function page(): array {
    $page = [
      '#theme' => 'uky_email_signature_generator_form',
      '#form' => $this->formBuilder->getForm(SignatureGeneratorForm::class),
      '#attached' => [
        'library' => [
          'core/drupal.ajax',
          'views/views.ajax',
          'uky_email_signature_generator/uky-email-signature-generator',
        ]
      ],
    ];

    if ($this->config('uky_email_signature_generator.settings')
      ->get('uky_email_signature_generator.randomize_example')) {
      $dummy_data = $this->getDummyData();
      $demo_data = [
        '#first_name' => $dummy_data['first_name'],
        '#last_name' => $dummy_data['last_name'],
        '#credentials' => $dummy_data['credentials'],
        '#pronouns' => $dummy_data['pronouns'],
        '#position_title' => $dummy_data['position_title'],
        '#department_unit' => $dummy_data['department_unit'],
        '#email' => strtolower($dummy_data['first_name']) . '.' . strtolower($dummy_data['last_name']) . '@example.com',
      ];
      $page = array_merge($page, $demo_data);
    }

    return $page;
  }

  protected function getDummyData(): array {
    $field_opts = [
      'first_name' => [
        'Jane',    'John',
        'Michael', 'Sarah',
      ],
      'last_name' => [
        'Doe',     'Smith',
        'Johnson', 'Williams',
      ],
      'credentials' => [
        '', '', '',
        'Ph.D', 'M.D.',
        'M.S.', 'M.A.',
      ],
      'pronouns' => [
        '', '',
        'he/him',    'she/her',
        'they/them', 'ze/zir',
        'he/they',   'she/they',
        'they/he',   'they/she',
      ],
      'position_title' => [
        '',
        'Director',    'Manager',
        'Coordinator', 'Developer',
        'Analyst',     'Instructor',
      ],
      'department_unit' => [
        '',
        'PR & Marketing',      'College of Law',
        'College of Medicine', 'College of Design',
        'Dean\'s Office',      'College of Business',
      ],
    ];

    return array_map(static function($opts) {
      return $opts[array_rand($opts)];
    }, $field_opts);
  }
}
