$('form.ajax-validation').submit(function (e) {
  var form = this;
  if ($(form).attr('validated') !== 'true') {
    e.preventDefault();
    var formData = new FormData(form);

    $.ajax({
      url: ajaxValidation,
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function (data) {
        var parentForm = $(form);
        $(form).attr('validated', false);

        if (data.success) {
          parentForm.find('.error-container').remove();
          $(form).attr('validated', true);
          $(form).find('button[type="submit"]').click();
          return true;
        }

        $.each(data.message, function (key, value) {
          parentForm.find('input[name="' + key + '"]').after('<div class="error-container"><br><div class="alert alert-danger">' + value + '</div></div>')
        });
      }
    });
  }else{
  }
});