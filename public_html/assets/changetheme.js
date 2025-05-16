const themes = [
  "/assets/style.css",
  "/assets/style1.css",
  "/assets/style2.css",
  "/assets/style3.css",
  "/assets/style4.css",
  "/assets/style5.css",
];
const themeSounds = [
  "/sounds/sword1.mp3",
  // "/sounds/roar.mp3",
  // "/sounds/magic.mp3",
  // "/sounds/lightning.mp3",
  // "/sounds/arrow.mp3",
  // "/sounds/shield.mp3",
];

let currentThemeIndex = parseInt(localStorage.getItem("themeIndex")) || 0;

// Préparation du son
// const swordSound = new Audio("/sounds/sword1.mp3"); // Chemin à adapter si besoin

function applyTheme(index) {
  const themeLink = document.getElementById("theme-style");
  if (!themeLink) return;

  // Lecture du son
  // swordSound.currentTime = 0;
  // swordSound.play();
  //const withSound = 1;
  const withSound = localStorage.getItem("withSound") === "true";

  if (withSound) {
    const sound = new Audio(themeSounds[index]);
    sound.play().catch((err) => console.warn("Audio bloqué :", err));
  }

  // Transition visuelle
  document.body.classList.add("fade-out");
  setTimeout(() => {
    const timestamp = new Date().getTime();
    const themeHref = `${themes[index]}?v=${timestamp}`;
    themeLink.href = themeHref;

    // Enregistre le thème
    currentThemeIndex = index;
    localStorage.setItem("themeIndex", index);
    localStorage.setItem("currentTheme", themeHref); // 🔥 ici

    setTimeout(() => {
      document.body.classList.remove("fade-out");
    }, 100);
  }, 100);
}

function changeTheme() {
  currentThemeIndex = (currentThemeIndex + 1) % themes.length;
  applyTheme(currentThemeIndex);
}

window.changeTheme = changeTheme;

document
  .getElementById("change-theme-btn")
  .addEventListener("click", changeTheme);

// Appliquer le thème au chargement
applyTheme(currentThemeIndex);
