const colorTheme = localStorage.getItem("color-theme");

const pageElement = document.documentElement;

if (colorTheme) {
    // Set current class theme
    colorTheme == "dark" ? pageElement.classList.add("dark") : pageElement.classList.remove("dark");
}
else {
    if (window.matchMedia) {
        // Set current class theme
        window.matchMedia('(prefers-color-scheme: dark)') ? pageElement.classList.add("dark") : pageElement.classList.remove("dark");
    }
}