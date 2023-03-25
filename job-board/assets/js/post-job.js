(function ($) {
  $(".mua-post-job").on("click", function (e) {
    e.preventDefault();
    $("#job-modal").fadeIn("fast");
  });

  $("#job-modal").on("click", function (e) {
    if (e.target == this) {
      $(this).fadeOut("fast");
    }
    e.stopPropagation();
  });

  //disable keyboard
  $(".hasDatepicker").keypress(function (e) {
    return false;
  });
  $(".hasDatepicker").keydown(function (e) {
    return false;
  });
})(jQuery);
