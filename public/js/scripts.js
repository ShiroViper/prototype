/**
 * The alternative of this:
 * onclick="document.location = '/admin/{{ $user->id }}';"
 */
$(".clickable-row").on("click", function () {
    window.location.href = $(this).data("href");
});

/**
 * Fades away after a brief delay
 */
$('.pop-messages').fadeOut(4200, "linear");

/**
 * Modal won't show when a button is clicked
 * from the tr modal trigger
 */
$('tr .no-modal').on("click", function (e) {
  e.stopPropagation();
});

$('#reqModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget);
  var id = button.data('id');
  var ca = button.data('ca');
  var la = button.data('la');
  var dp = button.data('dp');
  var desc = button.data('desc');
  var modal = $(this);
  modal.find('.loan-id').html(id);
  modal.find('.loan-ca').html(ca);
  modal.find('.loan-la').html(la);
  modal.find('.loan-dp').html(dp);
  modal.find('.loan-desc').html(desc); 
});

$('#histReqModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget);
  if ($(button).hasClass('no-modal')) {
    event.stopPropagation();
  }
  var id = button.data('id');
  var ca = button.data('ca');
  var cf = button.data('cf');
  var la = button.data('la');
  var dp = button.data('dp');
  var ap = button.data('ap');
  var desc = button.data('desc');
  var modal = $(this);
  modal.find('.loan-id').html(id);
  modal.find('.loan-ca').html(ca);
  modal.find('.loan-cf').html(cf);
  modal.find('.loan-la').html(la);
  modal.find('.loan-dp').html(dp);
  modal.find('.loan-ap').html(ap);
  modal.find('.loan-desc').html(desc);
});

$(function () {
  $('[data-toggle="tooltip"]').tooltip();

  // $('#reqModal').on('show.bs.modal', function (e) {
  //   // var url = window.location.href;
  //   // $('.modal-text').html(url);
  //   // $.post('requests', {url: url});
  // });

  // $('#reqModal').on('hide.bs.modal', function (e) {
  //   window.history.back();
  // });
});
