function toggleBlocks(showId, hideId) {
    var showBlocks = document.getElementById(showId);
    var hideBlocks = document.getElementById(hideId);

    if (showBlocks.style.display === 'none' || showBlocks.style.display === '') {
        showBlocks.style.display = 'flex';
        hideBlocks.style.display = 'none';
    } else {
        showBlocks.style.display = 'none';
    }
}
