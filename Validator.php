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

  public function message($label) {}

  public function required($value, $param = null): bool
  {
    if (is_array($value)) {
      return isset($value) && !empty($value);
    }
    return trim($value) !== '';
  }

  public function min($value, $param): bool
  {
    if (is_array($value)) {
      return count($value) >= (int)$param;
    }
    return mb_strlen(trim((string)$value)) >= (int)$param;
  }

  public function max($value, $param): bool
  {
    if (is_array($value)) {
      return count($value) <= (int)$param;
    }
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
      $rules = $value['rules'];
      if (is_array($rules)) {
        foreach ($rules as $rule => $rule_val) {
          $this->filter_rules($rule_val, $field);
        }
      } else {
        $this->filter_rules($rules, $field);
      }
    }

    return empty($this->errors);
  }

  public function filter_rules($rules, $field)
  {
    $rules = explode(self::RULE_SEPERATOR, $rules);
    foreach ($rules as $rule) {
      if (str_contains($rule, self::RULE_INNER_SEPERATOR)) {
        [$rule_key, $rule_val] = explode(self::RULE_INNER_SEPERATOR, $rule);
        $this->errors[$field][] = "$rule_key >>>> $rule_val";
        $this->$rule_key($this->data[$field], $rule_val);
      } else {
        $this->errors[$field][] = $rule;
        $this->$rule($this->data[$field]);
      }
    }
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
