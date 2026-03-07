<?php

require_once __DIR__ . '/ValidationRules.php';

class Validator
{
  private array $data;
  private array $fields;
  private array $errors = [];

  public function __construct(array $data, array $fields)
  {
    $this->data = $data;
    $this->fields = $fields;
  }

  public function validate(): bool
  {
    foreach ($this->fields as $field => $config) {
      $value = $this->data[$field] ?? '';
      $label = $config['label'] ?? $field;
      $rules = $config['rules'] ?? [];

      foreach ($rules as $rule => $message) {
        $ruleName = $rule;
        $param = null;

        if (str_contains($rule, ':')) {
          [$ruleName, $param] = explode(':', $rule, 2);
        }

        if (!method_exists(ValidationRules::class, $ruleName)) {
          continue;
        }

        $isValid = ValidationRules::$ruleName($value, $param);

        if (!$isValid) {
          $this->errors[$field][] = str_replace(
            [':label', ':value', ':param'],
            [$label, (string)$value, (string)$param],
            $message
          );
        }
      }
    }

    return empty($this->errors);
  }

  public function errors(): array
  {
    return $this->errors;
  }

  public function labels(): array
  {
    $labels = [];

    foreach ($this->fields as $field => $config) {
      $labels[$field] = $config['label'] ?? $field;
    }

    return $labels;
  }
}
