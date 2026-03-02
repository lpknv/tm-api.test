<?php
$showContent = false;
$age_info = false;
?>

<!DOCTYPE html>
<html lang="de">
  <head>
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Baseballcamp Anmeldung | EFG Hückelhoven-Baal">
    <meta property="og:description" content="Baseballcamp 2026 Anmeldung | EFG Hückelhoven-Baal">
    <meta property="og:image" content="https://efg-hueckelhoven.de/wp-content/uploads/2022/08/cropped-efg-logo.png">
    <link rel="canonical" href="https://efg-hueckelhoven.de/">
    <link rel="shortlink" href="https://efg-hueckelhoven.de/">
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" href="/cropped-efg-favicon-32x32.png" sizes="32x32">
    <link rel="icon" href="/cropped-efg-favicon-192x192.png" sizes="192x192">
    <link rel="stylesheet" href="./assets/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/main.css">
  </head>
<body>
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container">
      <div><img width="180" src="./assets/logo.png" alt="">
        <p>Baseballcamp <?= date('Y') ?> Anmeldung</p>
      </div>
      <a href="https://efg-hueckelhoven.de">
        Zur Webseite
      </a>
    </div>
  </nav>
  <div class="container">
    <?php if ($showContent): ?>
    <h3 class="mt-5">Willkommen zu der Baseballcamp Anmeldung</h3>
    <p class="lead">Bitte nutze das Formular unten, um dich für die Veranstaltung anzumelden.
      <br />
      Solltest du Fragen haben, so melde dich gerne bei Melanie Pfaffenrot unter der E-Mail Adresse
      <a href="mailto:baseballcamp@efg-hueckelhoven.de">baseballcamp@efg-hueckelhoven.de</a>
    </p>
    <?php if ($age_info): ?>
    <div class="alert alert-warning d-inline-flex align-items-center" role="alert">
      <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
        <path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.15.15 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.2.2 0 0 1-.054.06.1.1 0 0 1-.066.017H1.146a.1.1 0 0 1-.066-.017.2.2 0 0 1-.054-.06.18.18 0 0 1 .002-.183L7.884 2.073a.15.15 0 0 1 .054-.057m1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767z" />
        <path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z" />
      </svg>
      <div>
        Anmeldungen für die Altersgruppe von 12 bis 18 Jahren sind nicht mehr möglich.
      </div>
    </div>
    <?php endif; ?>
    <form class="contact-form py-5">
      <input type="hidden" name="hp">
      <div class="row">
        <div class="col-12">
          <p class="text-secondary d-flex align-items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-20">
              <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
            </svg>
            <span>Bitte fülle alle Felder mit * aus</span>
          </p>
          <h5 class="mt-2">Allgemeine Daten</h5>
          <div class="row">
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="familienname" class="form-label required">Familienname</label>
                <input required type="text" class="form-control" name="familienname" id="familienname">
              </div>
              <div class="mb-3">
                <label for="email" class="form-label required">E-Mail Adresse</label>
                <input required type="email" class="form-control" name="email" id="email">
              </div>
              <div class="mb-3">
                <label for="telefonnummer" class="form-label required">Telefonnummer / Handynummer der Eltern</label>
                <input required type="text" class="form-control" name="telefonnummer" id="telefonnummer">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="mb-3">
                <label for="strasse_hausnummer" class="form-label required">Straße + Hausnummer</label>
                <input required type="text" class="form-control" name="strasse_hausnummer" id="strasse_hausnummer">
              </div>
              <div class="mb-3">
                <label for="plz" class="form-label required">Postleitzahl</label>
                <input required type="text" class="form-control" name="plz" id="plz">
              </div>
              <div class="mb-3">
                <label for="ort" class="form-label required">Ort</label>
                <input required type="text" class="form-control" name="ort" id="ort">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <h5 class="mt-5">Kind 1</h5>
          <h6>Kosten: 70,- Euro</h6>
          <div class="mb-3">
            <label for="name1" class="form-label required">Name</label>
            <input required type="text" class="form-control" name="name1" id="name1">
          </div>
          <div class="mb-3">
            <label for="alter1" class="form-label required">Alter</label>
            <select name="alter1" id="alter1" class="form-select">
              <option value="8">8</option>
              <option value="9">9</option>
              <option value="10">10</option>
              <option value="11">11</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="heimweg1" class="form-label required">Nach dem Baseballcamp selbständig den Heimweg antreten?</label>
            <select name="heimweg1" id="heimweg1" class="form-select">
              <option value="Ja">Ja</option>
              <option value="Nein">Nein</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="tshirt1" class="form-label required">T-Shirt-Größe</label>
            <select name="tshirt1" id="tshirt1" class="form-select">
              <option value="Kids M">Kids M</option>
              <option value="S">S</option>
              <option value="M">M</option>
              <option value="L">L</option>
              <option value="XL">XL</option>
            </select>
          </div>
        </div>
        <div class="col-sm-6">
          <h5 class="mt-5">Kind 2</h5>
          <h6>Kosten: 60,- Euro</h6>
          <div class="mb-3">
            <label for="name2" class="form-label">Name</label>
            <input type="text" class="form-control" name="name2" id="name2">
          </div>
          <div class="mb-3">
            <label for="alter2" class="form-label">Alter</label>
            <select name="alter2" id="alter2" class="form-select">
              <option value="8">8</option>
              <option value="9">9</option>
              <option value="10">10</option>
              <option value="11">11</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="heimweg2" class="form-label">Nach dem Baseballcamp selbständig den Heimweg antreten?</label>
            <select name="heimweg2" id="heimweg2" class="form-select">
              <option value="Ja">Ja</option>
              <option value="Nein">Nein</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="tshirt2" class="form-label">T-Shirt-Größe</label>
            <select name="tshirt2" id="tshirt2" class="form-select">
              <option value="Kids M">Kids M</option>
              <option value="S">S</option>
              <option value="M">M</option>
              <option value="L">L</option>
              <option value="XL">XL</option>
            </select>
          </div>
        </div>
        <div class="col-sm-6">
          <h5 class="mt-5">Kind 3</h5>
          <h6>Kosten: 60,- Euro</h6>
          <div class="mb-3">
            <label for="name3" class="form-label">Name</label>
            <input type="text" class="form-control" name="name3" id="name3">
          </div>
          <div class="mb-3">
            <label for="alter3" class="form-label">Alter</label>
            <select name="alter3" id="alter3" class="form-select">
              <option value="8">8</option>
              <option value="9">9</option>
              <option value="10">10</option>
              <option value="11">11</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="heimweg3" class="form-label">Nach dem Baseballcamp selbständig den Heimweg antreten?</label>
            <select name="heimweg3" id="heimweg3" class="form-select">
              <option value="Ja">Ja</option>
              <option value="Nein">Nein</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="tshirt3" class="form-label">T-Shirt-Größe</label>
            <select name="tshirt3" id="tshirt3" class="form-select">
              <option value="Kids M">Kids M</option>
              <option value="S">S</option>
              <option value="M">M</option>
              <option value="L">L</option>
              <option value="XL">XL</option>
            </select>
          </div>
        </div>
        <div class="col-sm-6">
          <h5 class="mt-5">Kind 4</h5>
          <h6>Kosten: 60,- Euro</h6>
          <div class="mb-3">
            <label for="name4" class="form-label">Name</label>
            <input type="text" class="form-control" name="name4" id="name4">
          </div>
          <div class="mb-3">
            <label for="alter4" class="form-label">Alter</label>
            <select name="alter4" id="alter4" class="form-select">
              <option value="8">8</option>
              <option value="9">9</option>
              <option value="10">10</option>
              <option value="11">11</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="heimweg4" class="form-label">Nach dem Baseballcamp selbständig den Heimweg antreten?</label>
            <select name="heimweg4" id="heimweg4" class="form-select">
              <option value="Ja">Ja</option>
              <option value="Nein">Nein</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="tshirt4" class="form-label">T-Shirt-Größe</label>
            <select name="tshirt4" id="tshirt4" class="form-select">
              <option value="Kids M">Kids M</option>
              <option value="S">S</option>
              <option value="M">M</option>
              <option value="L">L</option>
              <option value="XL">XL</option>
            </select>
          </div>
        </div>
        <div class="col-sm-6">
          <h5 class="mt-5">Kind 5</h5>
          <h6>Kosten: 60,- Euro</h6>
          <div class="mb-3">
            <label for="name5" class="form-label">Name</label>
            <input type="text" class="form-control" name="name5" id="name5">
          </div>
          <div class="mb-3">
            <label for="alter5" class="form-label">Alter</label>
            <select name="alter5" id="alter5" class="form-select">
              <option value="8">8</option>
              <option value="9">9</option>
              <option value="10">10</option>
              <option value="11">11</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="heimweg5" class="form-label">Nach dem Baseballcamp selbständig den Heimweg antreten?</label>
            <select name="heimweg5" id="heimweg5" class="form-select">
              <option value="Ja">Ja</option>
              <option value="Nein">Nein</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="tshirt5" class="form-label">T-Shirt-Größe</label>
            <select name="tshirt5" id="tshirt5" class="form-select">
              <option value="Kids M">Kids M</option>
              <option value="S">S</option>
              <option value="M">M</option>
              <option value="L">L</option>
              <option value="XL">XL</option>
            </select>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <h5 class="mt-5">Allgemeine Informationen</h5>
          <div class="mb-3">
            <label for="marketing" class="form-label">Wie bist du auf unser Baseballcamp aufmerksam geworden?</label>
            <select name="marketing" id="marketing" class="form-select">
              <option disabled selected>-</option>
              <option value="Internet">Internet</option>
              <option value="Freunde">Freunde</option>
              <option value="Familie">Familie</option>
              <option value="Webseite">Webseite</option>
            </select>
          </div>
          <div class="mb-5">
            <label for="infos" class="form-label">Weitere Informationen (Krankheiten, Allergien usw.):</label>
            <textarea class="form-control" name="infos" id="infos" rows="3"></textarea>
          </div>
          <div class="mb-3">
            <input class="form-check-input" required type="checkbox" id="datenschutz" name="datenschutz">
            <label class="form-check-label required" for="datenschutz">
              Ich habe die <a target="_blank" href="https://efg-hueckelhoven.de/datenschutz">Datenschutzerklärung</a> gelesen und bin damit einverstanden
            </label>
          </div>
          <div class="mb-3">
            <input class="form-check-input" required type="checkbox" id="agb" name="agb">
            <label class="form-check-label required" for="agb">
              Ich habe die <a target="_blank" href="https://efg-hueckelhoven.de/baseballcamp-agb">AGB</a> gelesen und bin damit einverstanden
            </label>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <button id="submit-button" class="btn btn-primary" type="submit">
            <span id="submit">Jetzt anmelden</span>
            <span id="loader">Wird gesendet...</span>
          </button>
        </div>
      </div>
    </form>
    <?php else: ?>
      
      <h1 class="mt-5">Herzliche Einladung zum
Baseballcamp 2026</h1>
      <strong class="d-block mt-4">20. - 25. Juli 2026</strong>

      <p>Das Baseballcamp 2026 rückt näher - und die Vorfreude steigt! ⚾️✨<br/> Wir sind mitten in den Vorbereitungen und planen schon fleißig.
Die Anmeldungen werden in Kürze freigeschaltet!</p>

      <p>In diesem Jahr findet das Baseballcamp an folgender Adresse statt:
Hückelhoven - Am Schacht 3 (Glück-auf-Stadion) </p>
      
      <p>Bei Fragen melde dich gerne bei Melanie Pfaffenrot unter der E-Mail Adresse
      <a href="mailto:baseballcamp@efg-hueckelhoven.de">baseballcamp@efg-hueckelhoven.de</a></p>
    </p>
    <?php endif; ?>
  </div>
  <footer>
    <div class="container">
      Realisiert durch <a class="external" target="_blank" href="https://efg-hueckelhoven.de">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="link">
          <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
        </svg>
        <span>EFG Hückelhoven Baal</span>
      </a>
    </div>
  </footer>
  <script src="./assets/jquery.min.js"></script>
  <script src="./assets/notify.min.js"></script>
  <script src="./assets/bootstrap.min.js"></script>
  <script src="./assets/main.js"></script>
</body>

</html>