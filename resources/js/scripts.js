/**
 * The alternative of this:
 * onclick="document.location = '/admin/{{ $user->id }}';"
 */
$(".clickable-row").on("click", function () {
    window.location = $(this).data("href");
});

/**
 * Fades away after a brief delay
 */
$('.pop-messages').fadeOut(4200, "linear");


$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})