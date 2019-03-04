$('form.ajax-validation').submit(function (e) {
  e.preventDefault();
  var form = this;
  var formData = new FormData(form);

  $.ajax({
    url: ajaxValidation,
    type: "POST",
    data: formData,
    contentType: false,
    processData: false,
    success: function (data) {
      var parentForm = $(form);
      parentForm.find('.error-container').remove();

      if (data.success) {
        $(form).attr('validated', true);
        return true;
      }

      $.each(data.message, function (key, value) {
        parentForm.find('input[name="' + key + '"]').after('<div class="error-container"><br><div class="alert alert-danger">' + value + '</div></div>')
      });
    }
  });
});