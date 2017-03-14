
	var win = $(window);
	var T_float = ['<div class="c-float-popWrap msgMode hide">', '<div class="c-float-modePop">', '<div class="warnMsg hide"></div>', '<div class="content"></div>', '<div class="doBtn hide">', '<button class="ok">确定</button>', '<button class="cancel">取消</button>', '</div>', '</div>', '</div>'].join('');

	var E_float = $(T_float);
	var E_floatMsg = E_float.find('.warnMsg');
	var E_floatContent = E_float.find('.content');
	var E_floatOk = E_float.find('.doBtn .ok');
	var E_floatCancel = E_float.find('.doBtn .cancel');

	var initDom = false;
	var domContainer = 'body';
	var flashTimeoutId;

	function ModePop(options) {
		this._options = $.extend({
				mode: 'msg',
				text: '网页提示'
			},
			options || {});

		this._init();
	}

	$.extend(ModePop.prototype, {
		_init: function() {

			var that = this,
				opt = that._options,
				mode = opt.mode,
				text = opt.text,
				content = opt.content,
				callback = opt.callback,
				background = opt.background;
			// set mode
			var classTxt = E_float.attr('class');
			classTxt = classTxt.replace(/(msg|alert|confirm)Mode/i, mode + 'Mode');
			E_float.attr('class', classTxt);

			// set background
			background && E_float.css('background', background);

			// set text & content
			text && E_floatMsg.html(text);
			content && E_floatContent.html(content);

			E_floatOk.off('tap').on('tap', function(e) {
				callback.call(that, e, true);
			});
			E_floatCancel.off('tap').on('tap', function(e) {
				callback.call(that, e, false);
			});

			if (!initDom) {
				initDom = true;
				$(domContainer).append(E_float);
				win.on('resize', function() {
					setTimeout(function() {
							that._pos();
						},
						200);
				});
			}
		},

		_pos: function() {
			var that = this,
				doc = document,
				docEl = doc.documentElement,
				body = doc.body,
				top, left, cW, cH, eW, eH;

			if (!that.isHide()) {
//				top = body.scrollTop;
//				left = body.scrollLeft;
                top = 0;
                left = 0;
				cW = docEl.clientWidth;
				cH = docEl.clientHeight;
				eW = E_float.width();
				eH = E_float.height();

				E_float.css({
					top: top + (cH - eH) / 2,
					left: left + (cW - eW) / 2
				});
			}
		},

		isShow: function() {
			return E_float.hasClass('show');
		},

		isHide: function() {
			return E_float.hasClass('hide');
		},

		_cbShow: function() {
			var that = this,
				opt = that._options,
				onShow = opt.onShow;

			E_float.addClass('show');
			// 特殊处理
			var overlayEle = $('.pop-main-wrap');
			if (overlayEle.size() > 0) {
				overlayEle.removeClass('hide');
			} else {
				$(domContainer).append('<div class="pop-main-wrap"></div>');
			}
			onShow && onShow.call(that);
		},

		show: function() {
			var that = this;

			if (flashTimeoutId) {
				clearTimeout(flashTimeoutId);
				flashTimeoutId = undefined;
			}

			if (!that.isShow()) {
				E_float.css('opacity', '0').removeClass('hide');
				that._pos();

				E_float.animate({
						'opacity': '1'
					},
					200, 'linear',
					function() {
						that._cbShow();
					});
			} else {
				that._cbShow();
			}
		},

		_cbHide: function() {
			var that = this,
				opt = that._options,
				onHide = opt.onHide;

			E_float.addClass('hide');
			$('.pop-main-wrap').addClass('hide');
			onHide && onHide.call(that);
		},

		hide: function() {
			var that = this;

			if (!that.isHide()) {
				E_float.css('opacity', 1).removeClass('show');

				E_float.animate({
						'opacity': 0
					},
					200, 'linear',
					function() {
						that._cbHide();
					});
			} else {
				that._cbHide();
			}
		},

		flash: function(timeout) {
			var that = this,
				opt = that._options;
			opt.onShow = function() {
				flashTimeoutId = setTimeout(function() {
						if (flashTimeoutId) {
							that.hide();
						}
					},
					timeout);
			}
			that.show();
		}
	});

	window.Notification = new function() {

		this.simple = function(text, bg, timeout) {
			if (arguments.length == 2) {
				if (typeof arguments[1] == 'number') {
					timeout = arguments[1];
					bg = undefined;
				}
			}

			var pop = new ModePop({
				mode: 'msg',
				text: text,
				background: bg
			});

			pop.flash(timeout || 500);
			return pop;
		}

		this.msg = function(text, options) {
			return new ModePop($.extend({
					mode: 'msg',
					text: text
				},
				options || {}));
		}

		this.alert = function(text, callback, options) {
			return new ModePop($.extend({
					mode: 'alert',
					text: text,
					callback: callback
				},
				options || {}));
		}

		this.confirm = function(text, content, callback, options) {
			return new ModePop($.extend({
					mode: 'confirm',
					text: text,
					content: content,
					callback: callback,
				},
				options || {}));
		}

		this.pop = function(options) {
			return new ModePop(options);
		}
	};


