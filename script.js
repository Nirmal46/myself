window.addEventListener("load", function() {
    setTimeout(() => {
        const loadingScreen = document.querySelector(".loading-screen");
        const content = document.getElementById("content");

        loadingScreen.style.opacity = "0";
        setTimeout(() => {
            loadingScreen.style.display = "none";
            content.style.display = "block";
        }, 500); // Wait for the fade-out transition
    }, 2000); // Display loading screen for 2 seconds
});
