cfgByInput = {};

(function ($) {
  $.fn.filePlugin = function (options) {
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
    }, options);

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
      previewEls = el.parent().find('.filetype-image');
    };

    this.setRequired = function () {
      if (cfg.required && realInput.val() === 'none')
        this.required = true;
      else
        this.required = false;

      this.addValidation()
    };

    this.addValidation = function () {

      loadScript(['../vendor/betterfly/js/jquery.validate.min.js'], loaded);

      function loaded() {
        loadScript(['../vendor/betterfly/js/additional-methods.min.js']);
        var mimeTypes = cfg.mimeTypes.join(',');
        var rules = {required: self.required, accept: mimeTypes};
        cfgByInput[el.attr('id')] = cfg;

        rules.messages = {'accept': 'File Types Must Be ["' + mimeTypes + '"]'};
        el.parents('form').addClass('ajax-validation').validate({
          onfocusout: false,
          submitHandler: function (form) {
            self.validateForm(form, function (validated) {
              if (!validated) return;
              $('main').addClass('loading-mask');

              var formData = new FormData(form);
              formData.delete('_method');
              if (formData.get(realInputName).size < 1) {
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
                    $.each(response.files, function (inputId, files) {
                      var input = $('input[name="' + $('#' + inputId).attr('for') + '"]');
                      var cfg = cfgByInput[inputId];
                      var inputValue = self.generateInputValue(input, files, cfg);
                      input.val(inputValue);
                    });
                    form.submit();
                  } else {
                    $('<br><button class="btn btn-square btn-block btn-danger response-error">' + response.message + '</button>').insertAfter(el.parents('form'))
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
              $(form).find('[name="' + key + '"]').after('<div class="error-container"><br><div class="alert alert-danger">' + value + '</div></div>')
            });

            handle(false);
          }
        },
        error: function (request, status, error) {
          $('<br><button class="btn btn-square btn-block btn-danger response-error">' + request.responseJSON.message + '</button>').insertAfter(el.parents('form'))
          self.bindCopyEvent('.response-error');
        }

      });

    };
    this.bindCopyEvent = function(selector){
      $(selector).unbind('click').dblclick(function(){
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(this).html()).select();
        document.execCommand("copy");
        $temp.remove();
        $(".copied").addClass('visible');
        setTimeout(function () {
          $(".copied").removeClass('visible');
        },4000)
      })
    };

    this.generateInputValue = function (input, responseFiles, cfg) {
      if (cfg.maxCount < 2)
        return responseFiles.length > 1 ? responseFiles[0] : responseFiles;

      var value = input.val() === 'none' || !input.val() ? false : JSON.parse(input.val());

      if (value) {
        $.merge(value, responseFiles)
      } else {
        value = responseFiles;
      }

      return JSON.stringify(value);
    };


    this.initilizeEvents = function () {
      this.modifyPreview();
      this.setPreviewElements();
      this.retriggerEvents();
      this.modifyInput();
      // this.addValidation();
      this.triggerRemoveEvenet();
      this.setRequired();
      el.change(self.drawPreview)
    };

    this.modifyPreview = function () {
      parentEl.find('.filetype-file').attr('src', '../vendor/betterfly/img/document_icon.png')
    };

    this.triggerRemoveEvenet = function () {
      parentEl.find('.remove-image').click(function () {
        if (cfg.maxCount < 2) {
          var value = cfg.required ? 'none' : null;
          realInput.val(value);
          $(this).parent('.preview-container').remove();
        } else {
          var imageSrc = $(this).parent().find('img').attr('src');
          var inputValue = JSON.parse(realInput.val());
          for (var i = 0; i < inputValue.length; i++) {
            if (inputValue[i] === imageSrc)
              inputValue.splice(i, 1);
          }

          inputValue = !inputValue.length ? null : JSON.stringify(inputValue);
          realInput.val(inputValue);
          $(this).parent('.preview-container').remove();
        }

        self.setRequired();
      })
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
        if (filesLength > cfg.maxCount || parentEl.find('.file-preview').length >= cfg.maxCount) {
          $(this).val('');
          alert('You Can Choose Only ' + cfg.maxCount + ' Files');
          return false;
        }

        for (var i = 0; i < filesLength; i++) {
          var f = this.files[i];
          if (!f.type.match('image.*')) {
            previewTag = $('<img>').addClass('new-file file-preview').attr('src', '../vendor/betterfly/img/document_icon.png').attr('height', '120');
            parentEl.append(
                previewTag
            );
          } else {
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
      }
      setTimeout(function () {
        self.retriggerEvents();
      }, 100)
    };

    this.initilizeEvents();

    return this;
  };
})(jQuery);