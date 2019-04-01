var tags = [
    "ActionScript",
    "AppleScript",
    "Asp",
    "BASIC",
    "java",
    "zion",
    "c",
    "duhhh",
    "ambot"
];

$('#memName, #colName').autocomplete({
    // source: members,
    source: typeof members === 'undefined' ? collectors : members,
    select: function ( event, ui ) {
        event.preventDefault();
        $("input#memName").val(ui.item.label);
        $("input#memID").val(ui.item.value);
        $("input#colName").val(ui.item.label);
        $("input#colID").val(ui.item.value);
    },
    focus: function(event, ui) {
        event.preventDefault();
        $("#memName").val(ui.item.label);
        $("#colName").val(ui.item.label);
    },
    // minLength: 1,
    // create: function () {
    //     $(this).data('ui-autocomplete')._renderItem = function (ul, item) {
    //         return $('<li>')
    //             // .append("<a>(" + item.value + ') ' + item.label + "</a></li>")
    //             .append("<a>"+ item.label + "</a>")
    //             .appendTo(ul);
    //     };
    //     $("#memID").val(item.value);
    // },
});

$(function () {
    $('input#memName, input#colName').keyup(function (e) {
        if (e.key === "Escape") {
            $(this).val("");
            // alert('keyup');
        }
    });
    // $('input#id, input#amount').keyup(function () {
    //     if ($('input#id').val() != '' && $('input#amount').val() != '') {
    //         $(':input.autocomplete-btn').prop('disabled', false);
    //     } else {
    //         $(':input.autocomplete-btn').prop('disabled', true);
    //     }
    // });
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