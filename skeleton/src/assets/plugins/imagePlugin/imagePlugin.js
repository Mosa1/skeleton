(function ($) {
  $.fn.imagePlugin = function (cfg) {
    var self = this;
    var el = $(this);
    var cropData = {detail: {x: 300, y: 200, width: 300, height: 400}};
    var parentEl = el.parent();
    var cropDataInput;
    var previewEls;
    var cropperImg;
    var cfg = $.extend({
      maxCount: 1,
      types: ['png', 'jpg', 'jpeg', 'svg'],
      crop: true,
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
        var types = cfg.types.join(',');
        var rules = {required: cfg.required, accept: types};
        rules.messages = {'accept': 'File Types Must Be ["' + types + '"]'};

        el.parents('form').validate({
          onfocusout: false,
          submitHandler: function (form) {
            setTimeout(function () {
              if ($(form).attr('validated') === 'false') return false;
              var formData = new FormData(form);
              $.ajax({
                url: filesRoute,
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                  if(response.success) form.submit();
                }
              });
            }, 200);

          },
          errorPlacement: function (error, element) {
            element.parent().append(error.addClass('validation-error'));
          }
        });

        el.rules('add', rules);
      }
    };

    this.initilizeEvents = function () {
      this.setPreviewElements();
      this.retriggerEvents();
      this.modifyInput();
      this.addValidation();
      el.change(self.drawPreview)
    };

    this.modifyInput = function () {
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