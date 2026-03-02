$(document).ready(function () {
  let $form = $('form'); // ID anpassen

  $form.find(':input[name]').each(function () {
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

  $('.contact-form').submit(function (e) {
    e.preventDefault(); // zuerst stoppen!

    let formData = $(this).serialize(); // sendet alle Formfelder

    $.post('/ajax/sendmail_handler.php', formData, function (res) {
      console.log(res);
    }, 'json')
      .fail(function (data) {
        alert("error");
        console.error(data);
      })

  });
});
