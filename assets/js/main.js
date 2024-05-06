// Funkcje zarządzające zegarem
function displayClock() {
  const currentDate = new Date();
  const options = {
    weekday: 'long', year: 'numeric', month: 'short',
    day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit'
  };
  const language = document.documentElement.getAttribute('data-lang');
  const formattedDate = currentDate.toLocaleString(language, options);

  document.getElementById('clock').textContent = formattedDate;
  setTimeout(displayClock, 1000);
}

// Funkcje zarządzające motywami
function saveTheme(theme) {
  document.cookie = "theme=" + theme + ";path=/;SameSite=None;Secure";
}

function loadTheme() {
  const themeCookie = document.cookie.split('; ').find(row => row.startsWith('theme='));
  let theme = themeCookie ? themeCookie.split('=')[1] : 'light';
  document.documentElement.setAttribute("data-bs-theme", theme);
  updateThemeIcon(theme);
}

function updateThemeIcon(theme) {
  const themeToggle = document.getElementById("themeToggle");
  if (themeToggle) {
    themeToggle.classList.toggle("bi-brightness-high-fill", theme !== "dark");
    themeToggle.classList.toggle("bi-moon-fill", theme === "dark");
  }
}

function setupThemeToggle() {
  const themeToggle = document.getElementById("themeToggle");
  themeToggle?.addEventListener("click", function () {
    const currentTheme = document.documentElement.getAttribute("data-bs-theme");
    const newTheme = currentTheme === "dark" ? "light" : "dark";
    document.documentElement.setAttribute("data-bs-theme", newTheme);
    saveTheme(newTheme);
    updateThemeIcon(newTheme);
  });
}

// Funkcje filtrujące i walidujące tłumaczenia
function setupSearchFilter() {
  const searchInput = document.getElementById('searchInput');
  if (searchInput) {
    const translationRows = document.querySelectorAll('.translation-row');
    searchInput.addEventListener('keyup', function() {
      const searchValue = this.value.toLowerCase();
      translationRows.forEach(row => {
        const keyText = row.querySelector('strong').textContent.toLowerCase();
        const valueText = row.querySelector('p').textContent.toLowerCase();
        row.style.display = keyText.includes(searchValue) || valueText.includes(searchValue) ? '' : 'none';
      });
    });
  }
}

function setupTranslationTextarea() {
  const translationTextareas = document.querySelectorAll('.form-control');
  translationTextareas.forEach(textarea => {
    textarea.addEventListener('input', function() {
      const closestTranslationRow = textarea.closest('.translation-row');
      if (closestTranslationRow) {
        const originalText = closestTranslationRow.querySelector('p').textContent;
        const translatedText = textarea.value;
        const originalSpecialChars = extractSpecialCharacters(originalText);
        const translatedSpecialChars = extractSpecialCharacters(translatedText);
        textarea.style.borderColor = originalSpecialChars === translatedSpecialChars ? '' : 'red';
        textarea.style.background = originalSpecialChars === translatedSpecialChars ? '' : '#ffcccc';
      }
    });
  });

  function extractSpecialCharacters(str) {
    return str.replace(/[\w\s]|[\u00C0-\u017F]/g, '').split('').join('');
  }
}

// Funkcje zarządzające modalami do sprawdzania duplikatów
function setupCheckDuplicatesModal() {
  var checkDuplicatesModal = document.getElementById('checkDuplicatesModal');
  var deleteDuplicatesBtn = document.getElementById('deleteDuplicatesBtn');

  checkDuplicatesModal?.addEventListener('show.bs.modal', function () {
    fetch('check_duplicates.php')
    .then(response => response.json())
    .then(data => {
      if (data.status === 'success' && data.duplicates.length > 0) {
        let content = data.duplicates.map(d => `<p>${d.klucz} - ${d.tlumaczenie} (${translations.countLabel}: ${d.count})</p>`).join('');
        checkDuplicatesModal.querySelector('.modal-body').innerHTML = content;
      } else {
        checkDuplicatesModal.querySelector('.modal-body').innerHTML = 'No duplicates found';
      }
    })
    .catch(error => {
      console.error('Connection error', error);
      checkDuplicatesModal.querySelector('.modal-body').innerHTML = 'Error loading data';
    });
  });

  deleteDuplicatesBtn?.addEventListener('click', function() {
    fetch('delete_duplicates.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
      if (data.status === 'success') {
        alert('Duplicates removed');
        var bootstrapModal = bootstrap.Modal.getInstance(checkDuplicatesModal);
        bootstrapModal.hide();
        window.location.reload();
      } else {
        alert('Error: ' + data.message);
      }
    })
    .catch(error => {
      alert('Connection error');
      console.error(error);
    });
  });
}

// Funkcja do przenoszenia danych z formularza w stopce i wysyłania głównego formularza
function setupFooterFormSubmit() {
    const saveButton = document.querySelector('.footer button[type="submit"]');
    const mainForm = document.querySelector('main form');

    if (saveButton && mainForm) { // Sprawdź, czy obiekty istnieją
        saveButton.addEventListener('click', function(event) {
            event.preventDefault();

            const filenameInput = document.querySelector('.footer input[name="filename"]');
            if (filenameInput) { // Dodatkowe sprawdzenie, czy pole filenameInput istnieje
                const filenameField = document.createElement('input');
                filenameField.type = 'hidden';
                filenameField.name = 'filename';
                filenameField.value = filenameInput.value;

                mainForm.appendChild(filenameField);
                mainForm.submit();
            }
        });
    }
}

// Inicjalizacja funkcji zaraz po załadowaniu strony
window.onload = function() {
  displayClock();
  loadTheme();
};

// Inicjalizacja funkcji zaraz po pełnym załadowaniu struktury DOM
document.addEventListener("DOMContentLoaded", function () {
  setupThemeToggle();
  setupSearchFilter();
  setupTranslationTextarea();
  setupCheckDuplicatesModal();
  setupFooterFormSubmit();
});

// Zarządzanie bocznym paskiem (sidebar)
let sidebarToggle = document.querySelector("#sidebar-toggle");
if (!sidebarToggle) {
    sidebarToggle = document.createElement("button"); // Tworzy nowy element, jeśli nie został znaleziony
    sidebarToggle.id = "sidebar-toggle";
    document.body.appendChild(sidebarToggle); // Dodaje do DOM, jeśli nie istniał
}
const sidebar = document.querySelector("#sidebar");
const mainContent = document.querySelector(".main");

sidebarToggle.addEventListener("click", function() {
    sidebar.classList.toggle("collapsed");
    mainContent.classList.toggle("collapsed");
});

//Full Screen Mode
function toggleFullScreen() {
  const fsIcon = document.getElementById('fullscreenToggle');
  if (!document.fullscreenElement) {
    document.documentElement.requestFullscreen().then(() => {
      fsIcon.classList.remove('bi-arrows-fullscreen');
      fsIcon.classList.add('bi-fullscreen-exit');
    });
  } else {
    if (document.exitFullscreen) {
      document.exitFullscreen().then(() => {
        fsIcon.classList.remove('bi-fullscreen-exit');
        fsIcon.classList.add('bi-arrows-fullscreen');
      });
    }
  }
}