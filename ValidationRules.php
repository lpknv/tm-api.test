<?php

class ValidationRules
{
  public static function required($value, $param = null): bool
  {
    return trim((string)$value) !== '';
  }

  public static function min($value, $param): bool
  {
    return mb_strlen(trim((string)$value)) >= (int)$param;
  }

  public static function max($value, $param): bool
  {
    return mb_strlen(trim((string)$value)) <= (int)$param;
  }

  public static function email($value, $param = null): bool
  {
    return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
  }
}
