// assets/reload.js

let lastVersion = null;

function checkVersion() {
    fetch('/version.txt', { cache: 'no-store' })
        .then(response => response.text())
        .then(version => {
            if (lastVersion && version.trim() !== lastVersion.trim()) {
                console.log('Version changed, reloading...');
                window.location.reload();
            }
            lastVersion = version;
        })
        .catch(err => {
            console.error('Erreur lors du chargement de version.txt:', err);
        });
}

// Vérifie toutes les 2 secondes
setInterval(checkVersion, 2000);
