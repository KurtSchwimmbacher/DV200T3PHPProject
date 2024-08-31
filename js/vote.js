$(document).ready(function() {

    $('#createNewPost').on('click', function() {
        window.location.href = 'http://localhost/DV200T3PHPProject/pages/createPost.php';
    });

    $('.vote-btn').on('click', function() {
        var action = $(this).data('action');
        var questionID = $(this).data('question-id');
        var voteCountElement = $('#vote-count-' + questionID);
        var voteBtn = $(this);
        
        $.ajax({
            url: '../functionality/vote.php',
            type: 'POST',
            data: { action: action, questionID: questionID },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Update the vote count in the DOM
                    var currentVotes = parseInt(voteCountElement.text(), 10);
                    var newVoteValue = (action === 'like') ? 1 : -1;
                    voteCountElement.text(currentVotes + newVoteValue);

                    // Disable vote buttons to prevent further voting
                    voteBtn.addClass('disabled');
                    voteBtn.off('click'); // Optional: Remove click event after voting

                    // Optionally, show a message or indicator
                    alert('Thank you for your vote!');
                } else if (response.status === 'alreadyVoted') {
                    alert('You have already voted on this question.');
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
