<?php

function render_component(string $template, array $data)
{
  extract($data); // Array keys are now variables

  ob_start(); // Start capturing the output

  include __DIR__ . "/components/" . $template . ".php";

  $rendered = ob_end_flush(); // Gives output back to default
}
