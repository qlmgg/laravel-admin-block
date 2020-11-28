!function ($, iScroll) {
  $.ender({
    iScroll: function (options) {
      return new iScroll(this[0], options)
    }
  }, true)
}(ender, require('public/block/plugins/iscroll/iscroll').iScroll)
