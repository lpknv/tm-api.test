<?php

class Validator
{
  private array $data;
  private array $fields;
  private array $errors = [];

  const RULE_SEPERATOR = "|";
  const RULE_INNER_SEPERATOR = ":";

  public function __construct(array $data, array $fields)
  {
    $this->data = $data;
    $this->fields = $fields;
  }

  public function required($value, $param = null): bool
  {
    return trim($value) !== '';
  }

  public function min($value, $param): bool
  {
    return mb_strlen(trim((string)$value)) >= (int)$param;
  }

  public function max($value, $param): bool
  {
    return mb_strlen(trim((string)$value)) <= (int)$param;
  }

  public function email($value, $param = null): bool
  {
    return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
  }

  public function accepted($value, $param = null): bool
  {
    return in_array($value, ['1', 1, true, 'true', 'on', 'yes'], true);
  }

  public function validate()
  {
    foreach ($this->fields as $field => $value) {

      $rules = explode(self::RULE_SEPERATOR, $value['rules']);

      foreach ($rules as $rule) {
        if (str_contains($rule, ":")) {
          [$rule_key, $rule_val] = explode(":", $rule);

          echo $rule_key . self::RULE_INNER_SEPERATOR . $rule_val . " => " . $field . "\n";

          // $this->$rule();
        } else {
          echo $rule . " => " . $field . "\n";
        }
      }
      echo "\n";
    }

    return empty($this->errors);
  }

  public function errors(): array
  {
    return $this->errors;
  }

  public function data(): array
  {
    return $this->data;
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
