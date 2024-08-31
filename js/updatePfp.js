$(document).ready(function () {


    // Display selected image as preview
    $('#profilePictureInput').on('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#profilePicturePreview').attr('src', e.target.result);
                console.log(e.target.result)
            };
            reader.readAsDataURL(file);
        }
    });
});
