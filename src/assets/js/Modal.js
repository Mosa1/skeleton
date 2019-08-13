var Modal = {
  modal: null,
  yesBtn: null,
  noBtn: null,
  body: null,
  onYes: null,
  onHide: null,
  onCustom: null,
  CustomBtn: null,
  cropModal: null,

  init: function () {
    var me = this;

    this.yesBtn = $('<button>').attr({type: 'button'}).addClass('btn btn-primary').text('Yes');
    this.noBtn = $('<button>').attr({type: 'button'}).addClass('btn btn-default').attr('data-dismiss', 'modal').text('Cancel');
    this.customBtn = $('<button>').attr({type: 'button'}).addClass('btn btn-default').text('Custom');
    this.body = $('<div>').addClass('modal-header').text('Are you sure?');

    this.modal = $('<div>').addClass('modal fade').css({zIndex: 1000000}).attr({
      role: 'dialog',
      'aria-hidden': 'true'
    }).append(
        $('<div>').addClass('modal-dialog').append(
            $('<div>').addClass('modal-content').append(
                this.body
            ).append(
                $('<div>').addClass('modal-footer').append(
                    this.noBtn
                ).append(
                    this.yesBtn
                ).append(
                    this.customBtn
                )
            )
        )
    );

    this.yesBtn.click(function () {
      me.onYes && me.onYes("yes");
    });

    this.customBtn.click(function () {
      me.onCustom && me.onCustom("custom");
    });

    this.modal.on('hidden.bs.modal', function () {
      me.onHide && me.onHide();
    });

    $('body').append(this.modal);

    this.modal.on('show.bs.modal',function(){

      setTimeout(function () {
        $('.modal.show').addClass('large-modal')
      },200)

    });
  },

  show: function (cfg) {
    this.yesBtn.text(cfg.yes || 'Yes');
    this.customBtn.text(cfg.custom || 'Custom');
    this.noBtn.text(cfg.no || 'Cancel');
    this.body.html(cfg.body || 'Are you sure?');

    this.yesBtn.css({display: cfg.withoutYes ? 'none' : 'inline-block'})
    this.noBtn.css({display: cfg.withoutNo ? 'none' : 'inline-block'})
    this.customBtn.css({display: !cfg.custom ? 'none' : 'inline-block'})

    this.onYes = cfg.callback || null;
    this.onCustom = cfg.callback || null;
    this.onHide = cfg.onHide || null;
    this.cropModal = cfg.cropModal || null;
    if(this.cropModal) this.body.css({padding: 0});
    if(this.cropModal) $('.modal-dialog').css({maxWidth: '50%' });

    this.yesBtn.removeClass('btn-default btn-danger btn-info btn-primary btn-success btn-warning').addClass(cfg.yesClass || 'btn-primary')
    this.customBtn.removeClass('btn-default btn-danger btn-info btn-primary btn-success btn-warning').addClass(cfg.customClass || 'btn-primary')

    this.modal.modal({ backdrop: 'static', keyboard: false });
  },

  hide: function () {
    this.modal.modal('hide');
  }
};

$(function () {
  Modal.init();
});