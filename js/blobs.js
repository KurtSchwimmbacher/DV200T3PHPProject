$(document).ready(function() {
    function rotateBlob(blob, angle) {
        $(blob).css({ transform: 'rotate(' + angle + 'deg)' });

        // Increase angle for smooth rotation
        angle += 0.05; // Adjust this value for faster/slower rotation

        // Use requestAnimationFrame for smoother animation
        requestAnimationFrame(function() {
            rotateBlob(blob, angle);
        });
    }

    rotateBlob('#blueBlob', 0);
    rotateBlob('#yellowBlob', 0);
});