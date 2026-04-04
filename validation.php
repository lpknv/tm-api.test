<?php

use Rakit\Validation\Validator;

$validator = new Validator;

$validator->setMessages([
  'required' => 'Bitte :attribute angeben',
  'email' => 'Üngultige E-Mail Adresse :email',
  'min' => ':attribute ist zu kurz',
  'max' => ':attribute ist zu lang',
  'in' => ':attribute erlaubt nur :allowed_values',
]);

$validator->setTranslations([
  'and' => 'und',
  'or' => 'oder',
]);

$validation = $validator->make($_POST, [
  'familienname' => 'required|min:2|max:50',
  'email' => 'required|email',
  'telefonnummer' => 'required|min:2|max:50',
  'strasse_hausnummer' => 'required|min:2|max:50',
  'plz' => 'required|min:2|max:50',
  'ort' => 'required|min:2|max:50',
  'datenschutz' => 'required',
  'agb' => 'required',
  'kids' => 'array',
  'kids.*.name' => 'required|min:2|max:50',
  'kids.*.alter' => 'required|in:' . implode(',', $ages),
  'kids.*.tshirt' => 'required|in:' . implode(',', $tshirt_sizes),
  'kids.*.heimweg' => 'required|in:Ja,Nein',
  'kids.*.height' => 'nullable|min:2|max:5',
  'how_did_you_find_out_about_us' => 'nullable|in:' . implode(',', $marketing),
  'infos' => 'nullable|max:500'
]);

$validation->setAliases(
  [
    'familienname'                  => 'Familienname',
    'email'                         => 'E-Mail Adresse',
    'telefonnummer'                 => 'Telefonnummer',
    'strasse_hausnummer'            => 'Straße + Hausnummer',
    'plz'                           => 'Postleitzahl',
    'ort'                           => 'Ort',
    'datenschutz'                   => 'Datenschutzerklärung',
    'agb'                           => 'Allgemeine Geschäftsbedingungen',
    'kids'                          => 'Teilnehmer',
    'how_did_you_find_out_about_us' => '\"Wie bist du auf unser Baseballcamp aufmerksam geworden?\"',
    'infos'                         => '\"Weitere Informationen (Krankheiten, Allergien usw.)\"',
  ],
);
