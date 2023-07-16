var videoCards = document.getElementsByClassName("video-card");

for (var i = 0; i < videoCards.length; i++) {
    videoCards[i].addEventListener("click", function () {
        var videoId = this.querySelector("#recording_id").value;

        var newTab = window.open("../src/watch.php?recording_id=" + videoId);
        if (!newTab || newTab.closed || typeof newTab.closed == "undefined") {
            window.location.href = "../src/watch.php?recording_id=" + videoId;
        }
    });
}