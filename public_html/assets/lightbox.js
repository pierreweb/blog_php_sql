/* document.addEventListener("DOMContentLoaded", () => {
  const images = document.querySelectorAll(".article_content img");
  images.forEach((img) => {
    img.classList.add("lightbox-img");
    img.style.cursor = "zoom-in";

    img.addEventListener("click", () => {
      const full = document.createElement("div");
      full.classList.add("lightbox-overlay");
      full.innerHTML = `<img src="${img.src}" class="lightbox-full" />`;
      document.body.appendChild(full);

      full.addEventListener("click", () => {
        full.remove();
      });
    });
  });
}); */

/* document.querySelectorAll("img.postimg, .lightbox-img").forEach((img) => {
  img.addEventListener("click", function () {
    const overlay = document.getElementById("lightbox-overlay");
    const overlayImg = overlay.querySelector("img");
    overlayImg.src = this.src;
    overlay.style.display = "flex";
  });
}); */
// function openLightbox(src) {
// const overlay = document.createElement("div");
// const overlay = document.getElementById("lightbox-overlay");

/*   overlay.id = "lightbox-overlay";

  overlay.id = "lightbox-overlay";
  overlay.style.position = "fixed";
  overlay.style.top = 0;
  overlay.style.left = 0;
  overlay.style.width = "100%";
  overlay.style.height = "100%";
  overlay.style.background = "rgba(0, 0, 0, 0.85)";
  overlay.style.display = "flex";
  overlay.style.alignItems = "center";
  overlay.style.justifyContent = "center";
  overlay.style.zIndex = 9999; */

// const img = document.createElement("img");
// const img = document.getElementById("lightbox-img");
// img.src = src;
// img.id = "lightbox-full";
// img.style.maxWidth = "90%";
// img.style.maxHeight = "90%";
// img.style.boxShadow = "0 0 20px white";

// overlay.appendChild(img);

//   overlay.addEventListener("click", () => {
//     document.body.removeChild(overlay);
//   });

//   document.body.appendChild(overlay);
//   // alert("openLightbox");
// }

function openLightbox(src) {
  const overlay = document.getElementById("lightbox-overlay");
  const img = document.getElementById("lightbox-img");

  img.src = src;
  overlay.style.display = "flex"; // Affiche le lightbox (avec flex)

  // (Pas besoin de .appendChild ni de .addEventListener ici)
}
