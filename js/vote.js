// vote.js
$(document).ready(function() {

    $('#createNewPost').on('click',function(){
        window.location.href = 'http://localhost/DV200T3PHPProject/pages/createPost.php';
    })

    $('.vote-btn').on('click', function() {
        var action = $(this).data('action');
        var questionID = $(this).data('question-id');
        var voteCountElement = $('#vote-count-' + questionID);
        
        $.ajax({
            url: '../includes/vote.php',
            type: 'POST',
            data: { action: action, questionID: questionID },
            dataType: 'json',
            success: function(response) {
                console.log(response)
                if (response.status === 'success') {
                    // Update the vote count in the DOM
                    var currentVotes = parseInt(voteCountElement.text(), 10);
                    var newVoteValue = (action === 'like') ? 1 : -1;
                    voteCountElement.text(currentVotes + newVoteValue);
                } else {
                    console.error('Error:', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });
    });
});
