jQuery(document).ready(function($) {
    var cropper;
    var $image = $('#wp2023-image-preview');

    $('#wp2023-image-upload').on('change', function(event) {
        // Handle the image upload...
        // Display the image for cropping...

        var files = event.target.files;
        if (files && files.length > 0) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $image.attr('src', e.target.result).show();
                if (cropper) {
                    cropper.destroy();
                }
                cropper = new Cropper($image[0], {
                    aspectRatio: 16 / 9,
                    // other cropper options...
                });
                $('#wp2023-crop-image').show();
            };
            reader.readAsDataURL(files[0]);
        }
    });

    $('#wp2023-crop-image').on('click', function() {
        // Handle the cropping functionality...
        if (cropper) {
            var croppedCanvas = cropper.getCroppedCanvas();
            var canvas = document.getElementById('wp2023-image-canvas');
            var context = canvas.getContext('2d');
            context.clearRect(0, 0, canvas.width, canvas.height);
            context.drawImage(croppedCanvas, 0, 0);
            canvas.style.display = 'block';
            // Further actions can be performed with the cropped image here
        }
    });
});