// Анимация появления секций при прокрутке
const hero = document.querySelector('.hero');
hero.classList.add('appear')

window.addEventListener('scroll', () => {
  const sections = document.querySelectorAll('.services, .approach, .documentation, .contacts');

  sections.forEach(section => {
    const sectionTop = section.getBoundingClientRect().top;
    const windowHeight = window.innerHeight;

    if (sectionTop < windowHeight * 0.8) {
      section.classList.add('appear');
    }
  });
});

// Обработка клика на гамбургер-меню
const menuToggle = document.querySelector('.menu-toggle');
const navMenu = document.querySelector('.nav-menu');

function toggleMenu() {
  navMenu.classList.toggle('show');
}

menuToggle.addEventListener('click', toggleMenu);

// Закрытие меню при клике вне его
document.addEventListener('click', (event) => {
  const isClickInsideMenu = menuToggle.parentElement.contains(event.target);

  if (!isClickInsideMenu && navMenu.classList.contains('show')) {
    navMenu.addEventListener('animationend', () => {
      navMenu.classList.remove('show');
    }, { once: true });

    navMenu.classList.remove('show');
  }
});