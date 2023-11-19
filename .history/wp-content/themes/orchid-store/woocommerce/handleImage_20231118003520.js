jQuery(document).ready(function($) {
    var cropper;
    var $image = $('#wp2023-image-preview');

    $('#wp2023-custom-image').on('change', function(event) {
        var files = event.target.files;
        if (files && files.length > 0) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $image.attr('src', e.target.result).show();
                if (cropper) {
                    cropper.destroy();
                }
                cropper = new Cropper($image[0], {
                    aspectRatio: 1, // You can change this based on selected size
                    // other cropper options...
                });
                $('#wp2023-apply-crop').show();
            };
            reader.readAsDataURL(files[0]);
        }
    });

    $('#wp2023-standard-sizes').on('change', function() {
        var selectedSize = $(this).val();
        // Adjust cropper aspect ratio based on selected size
        if (cropper) {
            var aspectRatio = calculateAspectRatio(selectedSize);
            cropper.setAspectRatio(aspectRatio);
        }
    });

    function calculateAspectRatio(size) {
        // Logic to calculate aspect ratio based on standard sizes
    }

    $('#wp2023-apply-crop').on('click', function() {
        // Code to handle the cropped image
    });
});