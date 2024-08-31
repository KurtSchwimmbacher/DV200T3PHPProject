$(document).ready(function() {
    function fetchResults() {
        $.ajax({
            url: '../pages/feed.php', // Adjust this to your actual PHP script URL
            method: 'GET',
            data: $('#filter-form').serialize(),
            success: function(response) {
                // Update the content based on the new results
                $('#results').html($(response).find('#results').html());
            }
        });
    }

    // Listen for changes on input, select, and textarea elements
    $('#filter-form').on('change keyup', 'input, select, textarea', function() {
        fetchResults();
    });

    // Initialize fetchResults on page load to show default results
    fetchResults();
});
