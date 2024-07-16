// script.js

window.addEventListener('scroll', () => {
    const sections = document.querySelectorAll('.hero, .services, .approach, .documentation, .contacts');

    sections.forEach(section => {
        const sectionTop = section.getBoundingClientRect().top;
        const windowHeight = window.innerHeight;

        if (sectionTop < windowHeight * 0.8) {
            section.classList.add('appear');
        }
    });
});


const toggleInfoButtons = document.querySelectorAll(".toggle-info");

toggleInfoButtons.forEach(button => {
  button.addEventListener("click", (event) => {
    event.preventDefault(); 
    const additionalInfo = button.previousElementSibling; 
    additionalInfo.classList.toggle("show"); 

    button.textContent = additionalInfo.classList.contains("show")
      ? "Скрыть"
      : "Подробнее";
  });
});