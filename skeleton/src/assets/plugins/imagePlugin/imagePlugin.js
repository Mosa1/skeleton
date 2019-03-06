(function ($) {
  $.fn.imagePlugin = function (cfg) {
    var self = this;
    var el = $(this);
    var cropData = {detail: {x: 300, y: 200, width: 300, height: 400}};
    var parentEl = el.parent();
    var cropDataInput;
    var previewEls;
    var realInputName = el.attr('for');
    var cropperImg;
    var realInput = $('input[name="' + realInputName + '"]');
    var cfg = $.extend({
      maxCount: 1,
      mimeTypes: ['png', 'jpg', 'jpeg', 'svg'],
      crop: false,
      required: false,
    }, cfg);

    var crop = cfg.crop && cfg.maxCount < 2;

    if (crop) {
      loadCss('../vendor/betterfly/plugins/imagePlugin/cropper.min.css');
      loadScript(['../vendor/betterfly/plugins/imagePlugin/cropper.js']);
      parentEl.append('<input type="hidden" name="' + el.attr('for') + '_cropdata">');
      self.cropDataInput = parentEl.find("input[name='" + el.attr('for') + "_cropdata']");
    }

    function setCropper() {
      self.cropperImg = $('.cropper-img');
      self.cropData = self.cropData ? self.cropData : {detail: {x: '', y: '', width: '', height: ''}};
      self.cropperImg.cropper({
        zoomable: false,
        draggable: false,
        data: {
          x: self.cropData.detail.x,
          y: self.cropData.detail.y,
          width: self.cropData.detail.width,
          height: self.cropData.detail.height
        },
        crop: function (data) {
          self.cropData = data;
        }
      })
    }

    this.setPreviewElements = function () {
      previewEls = el.parent().find('.file-preview');
    };

    this.addValidation = function () {

      loadScript(['../vendor/betterfly/js/jquery.validate.min.js'], loaded);

      function loaded() {
        loadScript(['../vendor/betterfly/js/additional-methods.min.js']);

        var inputName = el.attr('name');
        var mimeTypes = cfg.mimeTypes.join(',');
        var rules = {required: cfg.required, accept: mimeTypes};
        rules.messages = {'accept': 'File Types Must Be ["' + mimeTypes + '"]'};
        el.parents('form').addClass('ajax-validation').validate({
          onfocusout: false,
          submitHandler: function (form) {
            self.validateForm(form, function (validated) {
              if (!validated) return;
              $('main').addClass('loading-mask');

              var formData = new FormData(form);
              formData.delete('_method');
              if (formData.get(inputName).size < 1) {
                form.submit();
                return false;
              }
              $.ajax({
                url: filesRoute,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                  $('main').removeClass('loading-mask');
                  if (response.success) {
                    var inputValue = self.generateInputValue(response.files);
                    realInput.val(inputValue);
                    form.submit();
                  }
                }
              });
            });
          },

          errorPlacement: function (error, element) {
            element.parent().append(error.addClass('validation-error'));
          }
        });

        el.rules('add', rules);
      }
    };

    this.validateForm = function (form, handle) {
      var formData = new FormData(form);
      formData.delete('_method');

      $.ajax({
        url: ajaxValidation,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
          $(form).attr('validated', false);
          $(form).find('.error-container').remove();

          if (response.success) {
            handle(true);
          } else {
            $.each(response.message, function (key, value) {
              $(form).find('input[name="' + key + '"]').after('<div class="error-container"><br><div class="alert alert-danger">' + value + '</div></div>')
            });

            handle(false);
          }
        }
      });

    };

    this.generateInputValue = function (responseFiles) {
      if (cfg.maxCount < 1)
        return responseFiles.length > 1 ? responseFiles[0] : responseFiles;

      var value = realInput.val() !== 'none' ? JSON.parse(realInput.val()) : false;

      if (value) {
        $.merge(value, responseFiles)
      }else{
        value = responseFiles;
      }

      return JSON.stringify(value);
    };

    this.initilizeEvents = function () {
      this.setPreviewElements();
      this.retriggerEvents();
      this.modifyInput();
      this.addValidation();
      el.change(self.drawPreview)
    };

    this.modifyInput = function () {
      parentEl.append("<input type='hidden' name='" + el.attr('name') + "_cfg' value='" + JSON.stringify(cfg) + "'>");
      if (cfg.maxCount > 1) {
        el.attr('name', el.attr('name') + '[]');
        el.attr('multiple', true);
      }
    };

    this.retriggerEvents = function () {
      previewEls.unbind('click');
      this.setPreviewElements();
      previewEls.click(self.openPopup);
    };

    this.openPopup = function () {
      var $this = $(this);

      Modal.show({
        body: '<img class="cropper-img" src="' + $this.data('src') + '" width="100%">',
        yes: 'Save',
        customClass: 'btn-danger',
        cropModal: crop,
        no: 'Close',
        custom: crop ? 'Reset Crop' : '',
        withoutYes: !crop,
        callback: function (btn) {
          Modal.hide();
          if (btn === 'yes') {
            var base64code = self.cropperImg.cropper('getCroppedCanvas').toDataURL();
            $this.attr('src', base64code);
            var cropData = {
              x: Math.round(self.cropData.detail.x),
              y: Math.round(self.cropData.detail.y),
              width: Math.round(self.cropData.detail.width),
              height: Math.round(self.cropData.detail.height)
            };
            self.cropDataInput.val(JSON.stringify(cropData));
          } else if (btn === 'custom') {

            $this.attr('src', $this.data('src'));
            self.cropDataInput.val('');
          }
        }
      });

      if (crop) {
        setTimeout(function () {
          setCropper();
        }, 500)
      }
    };

    this.drawPreview = function () {
      parentEl.find('.new-file').remove();
      parentEl.find('.validation-error').remove();
      $(this).blur().focus();

      if (this.files) {
        var filesLength = this.files.length;

        if (filesLength > cfg.maxCount) {
          $(this).val('');
          alert('You Can Choose Only ' + cfg.maxCount + ' Photos');
          return false;
        }

        for (var i = 0; i < filesLength; i++) {
          var reader = new FileReader();
          reader.onload = function (e) {
            previewTag = $('<img>').addClass('new-file file-preview').attr('src', e.target.result).attr('height', '150').data('src', e.target.result);
            parentEl.append(
                previewTag
            );
          };

          reader.readAsDataURL(this.files[i]);
        }
      }
      setTimeout(function () {
        self.retriggerEvents();
      }, 100)
    };

    this.initilizeEvents();

    return this;
  };
})(jQuery);