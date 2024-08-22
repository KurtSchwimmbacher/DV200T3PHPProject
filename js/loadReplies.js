// loadReplies.js
$(document).ready(function() {
    $('.reply-count').on('click', function() {
        var questionID = $(this).data('question-id');
        var repliesContainer = $('#replies-' + questionID);

        if (repliesContainer.is(':visible')) {
            repliesContainer.hide();
        } else {
            fetchReplies(questionID, repliesContainer);
        }
    });
});

function fetchReplies(questionID, container) {
    $.ajax({
        url: '../includes/getReplies.php',
        type: 'GET',
        data: { questionID: questionID },
        dataType: 'json',
        success: function(data) {
            if (data.length > 0) {
                console.log(data)
                var repliesHtml = data.map(function(reply) {
                    return `
                        <div class="reply">
                            <p><strong>User ${reply.UserID}:</strong> ${reply.AnswerContent}</p>
                            ${reply.answerImg ? `<img src="../uploads/${reply.AnswerImg}" alt="Reply Image">` : ''}
                        </div>
                    `;
                }).join('');
                container.html(repliesHtml);
            } else {
                container.html('<p>No replies yet.</p>');
            }
            container.show();
        },
        error: function(xhr, status, error) {
            console.error('Error fetching replies:', error);
        }
    });
}
