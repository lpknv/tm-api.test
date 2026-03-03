<?php
require_once __DIR__ . '/../bootstrap.php';

$showContent = IS_DEV;
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
    <link rel="icon" href="cropped-efg-favicon-32x32.png" sizes="32x32">
    <link rel="icon" href="cropped-efg-favicon-192x192.png" sizes="192x192">
    <link rel="stylesheet" href="./assets/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/main.css">
  </head>
<body>
  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container">
      <div><img width="180" src="./assets/logo.png" alt="">
        <p>Baseballcamp <?= CURRENT_YEAR ?> Anmeldung</p>
      </div>
      <a href="https://efg-hueckelhoven.de">
        Zur Webseite
      </a>
    </div>
  </nav>
  <div class="container">
    <?php if (true): ?>
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
          <h5 class="mt-5">Allgemeine Daten</h5>
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
        <div class="col-12">
          <h5 class="mt-5">Teilnehmer hinzufügen</h5>
        </div>
      </div>
      <div id="kids-container" class="row g-4"></div>
      <button type="button" id="add-kid" class="btn btn-outline-success w-100 mt-4">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M14 14.252V16.3414C13.3744 16.1203 12.7013 16 12 16C8.68629 16 6 18.6863 6 22H4C4 17.5817 7.58172 14 12 14C12.6906 14 13.3608 14.0875 14 14.252ZM12 13C8.685 13 6 10.315 6 7C6 3.685 8.685 1 12 1C15.315 1 18 3.685 18 7C18 10.315 15.315 13 12 13ZM12 11C14.21 11 16 9.21 16 7C16 4.79 14.21 3 12 3C9.79 3 8 4.79 8 7C8 9.21 9.79 11 12 11ZM18 17V14H20V17H23V19H20V22H18V19H15V17H18Z"></path></svg>
        Weiteres Kind
      </button>
      <div class="row">
        <div class="col-12">
          <h5 class="mt-5">Allgemeine Informationen</h5>
          <div class="row g-3">
            <div>
              <label for="marketing" class="form-label">Wie bist du auf unser Baseballcamp aufmerksam geworden?</label>
              <select name="marketing" id="marketing" class="form-select">
                <option disabled selected>-</option>
                <option value="Internet">Internet</option>
                <option value="Freunde">Freunde</option>
                <option value="Familie">Familie</option>
                <option value="Webseite">Webseite</option>
              </select>
            </div>
            <div>
              <label for="infos" class="form-label">Weitere Informationen (Krankheiten, Allergien usw.):</label>
              <textarea class="form-control" name="infos" id="infos" rows="3"></textarea>
            </div>
            <div>
              <input class="form-check-input" required type="checkbox" id="datenschutz" name="datenschutz">
              <label class="form-check-label required" for="datenschutz">
                Ich habe die <a target="_blank" href="https://efg-hueckelhoven.de/datenschutz">Datenschutzerklärung</a> gelesen und bin damit einverstanden
              </label>
            </div>
            <div>
              <input class="form-check-input" required type="checkbox" id="agb" name="agb">
              <label class="form-check-label required" for="agb">
                Ich habe die <a target="_blank" href="https://efg-hueckelhoven.de/baseballcamp-agb">AGB</a> gelesen und bin damit einverstanden
              </label>
            </div>
          </div>
        </div>
        <div class="col-12">
          <button id="submit-button" class="btn btn-primary" type="submit">
            <span id="submit">Jetzt anmelden</span>
            <span id="loader">Wird gesendet...</span>
          </button>
        </div>
      </div>
    </form>
    <?php else: ?>
      <h1 class="mt-5">Herzliche Einladung zum Baseballcamp <?= CURRENT_YEAR ?></h1>
      <strong class="d-block mt-4">20. - 25. Juli 2026</strong>
      <p>
        Das Baseballcamp 2026 rückt näher - und die Vorfreude steigt! ⚾️✨<br/> Wir sind mitten in den Vorbereitungen und planen schon fleißig.
        Die Anmeldungen werden in Kürze freigeschaltet!
      </p>
      <p>In diesem Jahr findet das Baseballcamp an folgender Adresse statt: Hückelhoven - Am Schacht 3 (Glück-auf-Stadion) </p>
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
  <script>
    
    <?php if(IS_DEV): ?>
      $('.contact-form').find(':input[name]').each(function () {
        if (this.name === 'hp') {
          return;
        }
        let $el = $(this);
        if ($el.is(':checkbox, :radio')) {
          $el.prop('checked', true);
        }
        else if ($el.is('select')) {
          $el.prop('selectedIndex', 1);
        }
        else {
          $el.val('test_' + this.name + '@aasd.de');
        }
      }); 
    <?php endif; ?>

    const maxKids = <?= MAX_KIDS_NUMBER ?>;
    let kidIndex = 0;

    function restoreFormState() {
      const saved = localStorage.getItem('campFormData');
      if (!saved) return;

      const data = JSON.parse(saved);

      data.forEach(field => {
        const $elements = $('[name="' + field.name + '"]');

        if (!$elements.length) return;

        const type = $elements.first().attr('type');

        if (type === 'radio') {
          $elements.filter('[value="' + field.value + '"]').prop('checked', true);
        } else if (type === 'checkbox') {
          $elements.prop('checked', true);
        } else {
          $elements.val(field.value);
        }
      });
    }

    function updateAddButton() {
      if (getKidCount() >= maxKids) {
        $('#add-kid').hide();
      } else {
        $('#add-kid').show();
      }
      $('#add-kid').prop('disabled', getKidCount() >= maxKids);
    }

    function kidTemplate(i) {
      const n = i + 1;
      const cost = (n === 1) ? 70 : 60;
      const showRemove = (n !== 1);

      return `
        <div class="col-lg-6 col-md-12 kid" data-i="${i}">
          <div class="card shadow-sm">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                  <h5 class="mb-0">Kind ${n}</h5>
                  <small class="text-muted">Kosten: ${cost},- Euro</small>
                </div>
                ${showRemove ? `
                <button type="button" class="btn btn-sm btn-outline-danger remove-kid">
                  Entfernen
                </button>` : ``}
              </div>
              <div class="row g-3">
                <div class="col-12">
                  <label for="kid${i}_name" class="form-label">Name</label>
                  <input 
                    type="text"
                    class="form-control"
                    id="kid${i}_name"
                    name="kids[${i}][name]"
                    required>
                </div>
                <div class="col-12">
                  <label for="kid${i}_alter" class="form-label">Alter</label>
                  <select 
                    class="form-select"
                    id="kid${i}_alter"
                    name="kids[${i}][alter]"
                    required>
                    <option selected disabled value="">Bitte wählen...</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                  </select>
                </div>
                <div class="col-12">
                  <label class="form-label d-block">Nach dem Baseballcamp selbständig den Heimweg antreten?</label>

                  <div class="btn-group w-100" role="group">
                    
                    <input 
                      type="radio"
                      class="btn-check"
                      name="kids[${i}][heimweg]"
                      id="kid${i}_heimweg_ja"
                      value="Ja"
                      required>
                    <label 
                      class="btn btn-outline-secondary w-50"
                      for="kid${i}_heimweg_ja">
                      Ja
                    </label>

                    <input 
                      type="radio"
                      class="btn-check"
                      name="kids[${i}][heimweg]"
                      id="kid${i}_heimweg_nein"
                      value="Nein"
                      required>
                    <label 
                      class="btn btn-outline-secondary w-50"
                      for="kid${i}_heimweg_nein">
                      Nein
                    </label>

                  </div>
                </div>
            </div>
          </div>
        </div>`;
    }

    function reindexKids() {
      $('#kids-container .kid').each(function (newIndex) {
        const $kid = $(this);
        const n = newIndex + 1;
        const cost = (n === 1) ? 70 : 60;

        // data-i + Überschrift + Kosten
        $kid.attr('data-i', newIndex);
        $kid.find('h5').text(`Kind ${n}`);
        $kid.find('small.text-muted').text(`Kosten: ${cost},- Euro`);

        // Remove-Button nur ab Kind 2
        const $removeBtn = $kid.find('.remove-kid');
        if (n === 1) $removeBtn.remove();
        else if ($removeBtn.length === 0) {
          // optional: falls du den Button wieder hinzufügen willst
          // (nur nötig, wenn dein Template ihn nicht immer rendert)
        }

        // 1) NAME-Attribute: kids[OLD] -> kids[newIndex]
        $kid.find('[name]').each(function () {
          const $el = $(this);
          const name = $el.attr('name');
          const newName = name.replace(/^kids\[\d+]/, `kids[${newIndex}]`);
          $el.attr('name', newName);
        });

        // 2) ID-Attribute: kidOLD_... -> kidNEW_...
        $kid.find('[id]').each(function () {
          const $el = $(this);
          const id = $el.attr('id');
          const newId = id.replace(/^kid\d+_/, `kid${newIndex}_`);
          $el.attr('id', newId);
        });

        // 3) LABEL for= aktualisieren (wichtig für btn-check!)
        $kid.find('label[for]').each(function () {
          const $label = $(this);
          const f = $label.attr('for');
          const newFor = f.replace(/^kid\d+_/, `kid${newIndex}_`);
          $label.attr('for', newFor);
        });
      });
      updateAddButton();
    }

    function getKidCount() {
      return $('#kids-container .kid').length;
    }

    function saveFormState() {
      const data = $('.contact-form').serializeArray();
      localStorage.setItem('campFormData', JSON.stringify(data));
      localStorage.setItem('kidCount', $('#kids-container .kid').length);
    }

    function saveKidCount() {
      localStorage.setItem('kidCount', $('#kids-container .kid').length);
    }

    $(document).on('input change', '.contact-form input, .contact-form select, .contact-form textarea', function () {
      saveFormState();
    });
    
    const savedCount = parseInt(localStorage.getItem('kidCount')) || 1;

    for (let i = 0; i < savedCount; i++) {
      $('#kids-container').append(kidTemplate(i));
    }

    kidIndex = 1;

    $('#add-kid').on('click', function () {
      const index = getKidCount();
      if (index >= maxKids) return;

      const newKid = $(kidTemplate(index));
      $('#kids-container').append(newKid);

      updateAddButton();

      saveFormState();
      newKid[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    $(document).on('click', '.remove-kid', function () {
      const $removed_kid = $(this).closest('.kid');
      const $all_kids = $('#kids-container .kid');

      $removed_kid.remove();

      reindexKids();
      updateAddButton();

      const $remaining_kids = $('#kids-container .kid');

      if ($remaining_kids.length > 0) {
        const lastKid = $remaining_kids.last()[0];
        lastKid.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      } else {
        $('#add-kid')[0].scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }
      saveFormState();
    });
    restoreFormState();
  </script>
</body>

</html>