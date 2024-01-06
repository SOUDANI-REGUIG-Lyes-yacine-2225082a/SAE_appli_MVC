
function showText(card) {
    var back = card.querySelector('.flip-card-back');
    back.style.display = 'block';
}
function toggleBlocks(showId, hideId) {
    var showBlocks = document.getElementById(showId);
    var hideBlocks = document.getElementById(hideId);

    if (showBlocks.style.display === 'none') {
        showBlocks.style.display = 'flex';
        hideBlocks.style.display = 'none';
    } else {
        showBlocks.style.display = 'none';
    }
}