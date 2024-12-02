// js/scripts.js

// Clock Script
function updateClock() {
    var now = new Date();
    var hours = now.getHours().toString().padStart(2, '0');
    var minutes = now.getMinutes().toString().padStart(2, '0');
    var seconds = now.getSeconds().toString().padStart(2, '0');
    document.getElementById('clock').textContent = hours + ':' + minutes + ':' + seconds;
}
setInterval(updateClock, 1000);
updateClock();

// Dark Mode Toggle
document.getElementById('darkModeToggle').addEventListener('click', function() {
    document.body.classList.toggle('dark-mode');
    var icon = this.querySelector('i');
    if (document.body.classList.contains('dark-mode')) {
        icon.classList.remove('fa-moon');
        icon.classList.add('fa-sun');
        this.textContent = ' Light Mode';
        this.prepend(icon);
        localStorage.setItem('darkMode', 'enabled');
    } else {
        icon.classList.remove('fa-sun');
        icon.classList.add('fa-moon');
        this.textContent = ' Dark Mode';
        this.prepend(icon);
        localStorage.setItem('darkMode', 'disabled');
    }
});

// Save and load dark mode preference
(function() {
    if (localStorage.getItem('darkMode') === 'enabled') {
        document.body.classList.add('dark-mode');
        var toggleBtn = document.getElementById('darkModeToggle');
        var icon = toggleBtn.querySelector('i');
        icon.classList.remove('fa-moon');
        icon.classList.add('fa-sun');
        toggleBtn.textContent = ' Light Mode';
        toggleBtn.prepend(icon);
    }
})();


// js/scripts.js

// ... Skrip sebelumnya ...

// Handle Galeri Modal
$(document).ready(function(){
    $('.galeri-section img').on('click', function(){
        var src = $(this).attr('src');
        $('#modalGambar').attr('src', src);
        $('#galeriModal').modal('show');
    });
});
