function showSection(section) {
    const sections = document.querySelectorAll('section');
    sections.forEach(s => s.classList.remove('visible'));
    document.getElementById(section).classList.add('visible');
}

function toggleMenu() {
    const nav = document.querySelector('nav ul');
    nav.classList.toggle('show');
}