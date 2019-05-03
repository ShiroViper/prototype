/**
 * Accordion Selector
 */
$(".list-header").click(function () {
  if ($(this).parent().hasClass("opened")) {
    $(this).parent().removeClass("opened border border-selected rounded");
    $(".list-chevron").toggleClass("rotate");
  } else {
    $(".list-chevron").toggleClass("rotate"); 
    $(".list-group").children().removeClass("opened border border-selected rounded");
    $(this).parent().addClass("opened border border-selected rounded");
  }
});

/**
 * The alternative of this:
 * onclick="document.location = '/admin/{{ $user->id }}';"
 */
$(".clickable-row").on("click", function () {
    window.location.href = $(this).data("href");
});

/**
 * Fades away notifications after a brief delay
 */
$('.pop-messages').fadeOut(4200, "linear");

/**
 * Modal won't show when a button is clicked
 * from the tr modal trigger
 */
$('tr .no-modal').on("click", function (e) {
  e.stopPropagation();
});

/**
 * Reason for loan
 */
$('#reason').change(function () {
  var selected = $('#reason').val();
  // console.log(selected);
  if (selected == '3') {    
    $("#other").show().prop("required", true);
  } else {
    $("#other").hide();
  }
});

/**
 * Password checker
 */
function passwordChecker() {
  var pass = $("#password").val();
  var confirm = $("#cpassword").val();
  // pass != null ? alert('null') : alert('false');
  if (pass == 0) {
    $("#cpassword").val('');
    $("#password-checker").text("");
    $(".member-setup-btn").prop('disabled', true);
  } else if (pass == confirm) {
    // alert("no pass");
    $("#password-checker").text("Passwords match").css("color", "green");
    $(".member-setup-btn").prop('disabled', false);
  } else {
    $("#password-checker").text("Passwords do not match!").css("color", "red");
    $(".member-setup-btn").prop('disabled', true);
  }
}

// $('#reqModal').on('show.bs.modal', function (event) {
//   var button = $(event.relatedTarget);
//   var id = button.data('id');
//   var ca = button.data('ca');
//   var la = button.data('la');
//   var dp = button.data('dp');
//   var desc = button.data('desc');
//   var modal = $(this);
//   modal.find('.loan-id').html(id);
//   modal.find('.loan-ca').html(ca);
//   modal.find('.loan-la').html(la);
//   modal.find('.loan-dp').html(dp);
//   modal.find('.loan-desc').html(desc); 
// });

$('#histReqModal').on('show.bs.modal', function (event) {
  var button = $(event.relatedTarget);
  if ($(button).hasClass('no-modal')) {
    event.stopPropagation();
  }
  var id = button.data('id');
  var cdate = button.data('cdate');
  var mem = button.data('mem');
  var memid = button.data('memid');
  var udate = button.data('udate');
  var amount = button.data('amount');
  var dp = button.data('dp');
  var conf = button.data('conf');
  var paid = button.data('paid');
  var desc = button.data('desc');
  var modal = $(this);
  modal.find('.loan-id').html(id);
  modal.find('.loan-mem').html(mem);
  modal.find('.loan-memid').html(memid);
  modal.find('.loan-cdate').html(cdate);
  modal.find('.loan-udate').html(udate);
  modal.find('.loan-amount').html(amount);
  modal.find('.loan-dp').html(dp);
  modal.find('.loan-conf').html(conf);

 if (paid.indexOf("Ongoing") !== -1) {
   modal.find('.loan-unpaid').show();
   modal.find('.loan-unpaid').html('<i class="fas fa-exclamation-circle"></i> Payment is still ongoing');
   modal.find('.loan-paid').html(paid);
 } else {
  modal.find('.loan-unpaid').hide();
  modal.find('.loan-paid').html(paid);
 }

  modal.find('.loan-desc').html(desc);
});

$(function () { 

  $("#other").hide();
  /**
   * Spinner for calendar
   */
  $('div[role="status"]').toggle();

  /**
   * Tooltip 
   */
  $('[data-toggle="tooltip"]').tooltip();

  /**
   * Password checker
   */
  $("#cpassword").keyup(passwordChecker);

  /**
   * Highlighter
   */
  if (window.location.hash == "#moreInfo") {
    // alert(window.location.hash);
    $('#moreInfo').effect("highlight", {}, 3500);
  }


  // $('#reqModal').on('show.bs.modal', function (e) {
  //   // var url = window.location.href;
  //   // $('.modal-text').html(url);
  //   // $.post('requests', {url: url});
  // });

  // $('#reqModal').on('hide.bs.modal', function (e) {
  //   window.history.back();
  // });
});


// for tooltip
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
});


// for request loan
function loan() {
  var amount = document.getElementById('amount').value;
  var selectBox = document.getElementById("months");
  var month = selectBox.options[selectBox.selectedIndex].value;
 
  var interest = +amount * +0.06;
  var monthly = +interest / +month;
  
  document.getElementById("interest").innerHTML = '<p class="h6">Loan Interest</p>' + '₱ ' + Math.round(interest).toFixed(2);
  document.getElementById("monthly").innerHTML = '<p class="h6">Monthly Payment</p>' + '₱ ' + Math.round(monthly).toFixed(2);
 }

 $('#type').change(function () {
  var selected = $('#type').val();
  // console.log(selected);
  if (selected == '4') {    
    var full_payment = document.getElementById("full_payment").value;
    $("#amount").hide().prop("required", false);
    document.getElementById("amount").value = full_payment;
    $('#amount_label').hide();
  }else if (selected == '5') {    
      var full_payment = document.getElementById("full_remaining_balance").value;
      $("#amount").hide().prop("required", false);
      document.getElementById("amount").value = full_payment;
      $('#amount_label').hide();
  } else {
    $("#amount").show();
    $('#amount_label').show().prop("required", true);;
  }
});