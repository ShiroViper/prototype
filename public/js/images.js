$('#canvas, .img-file-container, .file-name').hide();

// USING THE CAMERA
$('#cameraModal').on('shown.bs.modal').keypress(function (e) {
  if (e.keyCode == 13 || e.keyCode == 32) {
    $('#snap').click();
  }
});

// SELECTING A FILE
function uploadFile(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      $('.img-file-container').show();
      $('#file').attr('src', e.target.result);
      $('#canvas').hide();
    };
    reader.readAsDataURL(input.files[0]);

    $('#upload-file-info').html("<i class='far fa-file mr-1'></i> Change file");
    $('.file-name').html(input.files[0].name);

    // $('.img-file-container').hover(function() {
    //   $('.file-name').show().toggleClass("file-label");
    // }).on("mouse", function() {
    //   $('.file-name').toggleClass("file-label");
    // });

    $('.img-file-container').mouseenter(function() {
      $('.file-name').show().addClass("file-label");
    }).mouseleave(function() {
      $('.file-name').removeClass("file-label").hide();
    });

    $(".img-camera, .img-file").removeClass("unselected-option selected-option");
    $(".img-file").toggleClass("selected-option");
    $(".img-camera").toggleClass("unselected-option");
  }
}