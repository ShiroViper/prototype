// var tags = [
//     "ActionScript",
//     "AppleScript",
//     "Asp",
//     "BASIC",
//     "java",
//     "zion",
//     "c",
//     "duhhh",
//     "ambot"
// ];

$('#id').autocomplete({
    source: members,
    minLength: 1,
    create: function () {
        $(this).data('ui-autocomplete')._renderItem = function (ul, item) {
            return $('<li>')
                .append("<a>(" + item.value + ') ' + item.label + "</a></li>")
                .appendTo(ul);
        };
    },
    change: function ( event, ui ) {
        if (!ui.item) {
            $(this).val("");
        }
    }
});

$(function () {
    $('input#id').keyup(function (e) {
        if (e.key === "Escape") {
            $(this).val("");
        }
    });
    $('input#id, input#amount').keyup(function () {
        if ($('input#id').val() != '' && $('input#amount').val() != '') {
            $(':input.autocomplete-btn').prop('disabled', false);
        } else {
            $(':input.autocomplete-btn').prop('disabled', true);
        }
    });
});

// $("input#test").autocomplete({
//   source: members,
//   focus: function( event, ui ) {
//     $( "input#test" ).val( ui.item.label );
//     return false;
//   },
//   select: function (event, ui) {
//     $("input#test").val(ui.item.label); // display the selected text
//     return false;
//     // $("#txtAllowSearchID").val(ui.item.value); // save selected id to hidden input
//   }
// }).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
//   return $( "<li>" )
//     .append( "<div>" + item.label + "<br>" + item.value + "</div>" )
//     .appendTo( ul );
// };


// $("input#c_id").autocomplete({
//   source: collector
// });

// var collector = [];
// var members = [];