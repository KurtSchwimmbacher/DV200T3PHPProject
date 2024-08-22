// Expand the reply field when clicked
$(document).ready(function(){
    $('.reply-field').focus(function() {
        $(this).attr('rows', 3); // Expand the text area
        $(this).next().show(); // Show the post button
    });

    $('.reply-field').blur(function() {
        if ($(this).val().trim() === '') {
            $(this).attr('rows', 1); // Collapse the text area if it's empty
            $(this).next().hide(); // Hide the post button if the field is empty
        }
    });
});