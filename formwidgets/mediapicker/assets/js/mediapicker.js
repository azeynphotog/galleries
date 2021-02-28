+function ($) { "use strict";
    var Base = $.oc.foundation.base,
        BaseProto = Base.prototype

    var MediaPicker = function (element, options) {
        this.$el = $(element)
        this.options = options || {}

        $.oc.foundation.controlUtils.markDisposable(element)
        Base.call(this)
        this.init()
    }

    MediaPicker.prototype = Object.create(BaseProto)
    MediaPicker.prototype.constructor = MediaPicker

    MediaPicker.prototype.init = function() {
        this.$el.on('click', '.file-add-button', this.proxy(this.onClickFindButton))
        this.$el.on('click', '.file-remove-button', this.proxy(this.onClickRemoveButton))

        this.$fileTemplate = $('[data-file-template]', this.$el).first().html()
    }

    MediaPicker.prototype.dispose = function() {
        this.$el.off('click', '.find-button', this.proxy(this.onClickFindButton))
        this.$el.off('click', '.find-remove-button', this.proxy(this.onClickRemoveButton))
        this.$el.off('dispose-control', this.proxy(this.dispose))
        this.$el.removeData('oc.mediaPicker')

        this.$fileTemplate = null
        this.$el = null

        // In some cases options could contain callbacks,
        // so it's better to clean them up too.
        this.options = null

        BaseProto.dispose.call(this)
    }

    MediaPicker.prototype.onClickRemoveButton = function(event) {
        $(event.target).parents('tr').remove()
    }

    MediaPicker.prototype.onClickFindButton = function() {
        var self = this

        new $.oc.mediaManager.popup({
            alias: 'ocmediamanager',
            onInsert: function(items) {
                if (!items.length) {
                    alert('Please select image(s) to insert.')
                    return
                }

                for (var i=0, len=items.length; i<len; i++) {
                    var newFile = $(self.$fileTemplate).clone()
                    newFile.find('[data-file-path]').val(items[i].path)
                    newFile.find('[data-file-name]').text(items[i].title)
                    newFile.on('click', '.file-remove-button', self.proxy(self.onClickRemoveButton))
                    newFile.insertAfter($('[data-file-item]', self.$el).get(-1))
                }

                this.hide()
            }
        })
    }

    MediaPicker.DEFAULTS = {
    }

    // PLUGIN DEFINITION
    // ============================

    var old = $.fn.mediaPicker

    $.fn.mediaPicker = function(option) {
        var args = arguments;

        return this.each(function() {
            var $this   = $(this)
            var data    = $this.data('oc.mediaPicker')
            var options = $.extend({}, MediaPicker.DEFAULTS, $this.data(), typeof option == 'object' && option)
            if (!data) $this.data('oc.mediaPicker', (data = new MediaPicker(this, options)))
            if (typeof option == 'string') data[option].apply(data, args)
        })
    }

    $.fn.mediaPicker.Constructor = MediaPicker

    $.fn.mediaPicker.noConflict = function() {
        $.fn.mediaPicker = old
        return this
    }

    $(document).render(function() {
        $('[data-control="mediapicker"]').mediaPicker()
    })

}(window.jQuery);
