<?php

namespace Drupal\uky_email_signature_generator\TwigExtension;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class SignatureFormatter
 *
 * @package Drupal\uky_email_signature_generator\TwigExtension
 */
class SignatureFormatter extends AbstractExtension {

  /**
   *
   * @var EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   *
   * @param EntityTypeManagerInterface $entityTypeManager
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * @inheritdoc
   *
   * @return TwigFilter[]
   */
  public function getFilters(): array {
    return [
      new TwigFilter(
        'format_phone',
        [$this, 'format_phone']
      ),
    ];
  }

  /**
   * Take in a 10-character phone number and format it for display.
   *
   * @param string $string The unformatted phone number (##########)
   * @return string The formatted phone number (###-###-####)
   */
  public static function format_phone(string $string): string {
    return preg_replace('/(\d{3})(\d{3})(\d{4})/', '$1-$2-$3', $string);
  }

}
