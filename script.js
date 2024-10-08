// Функция для открытия модального окна
function openModal() {
  document.getElementById("application-modal").style.display = "block";
}

// Функция для закрытия модального окна
function closeModal() {
  document.getElementById("application-modal").style.display = "none";
}

// Получаем элементы
const modal = document.getElementById("application-modal");
const btn = document.querySelector(".btn"); // Кнопка "Оставить заявку"
const closeModalBtn = document.querySelector(".close-modal"); // Кнопка закрытия модального окна

// Открываем модальное окно при клике на кнопку
btn.addEventListener('click', openModal);

// Закрываем модальное окно при клике на крестик
closeModalBtn.addEventListener('click', closeModal);

// Закрываем модальное окно при клике вне области контента
window.addEventListener('click', (event) => {
  if (event.target == modal) {
    closeModal();
  }
});

// --- Анимация появления секций ---

const sections = document.querySelectorAll('.hero, .services, .approach, .contacts');
const body = document.body;

function checkVisibility() {
  sections.forEach(section => {
    const sectionTop = section.getBoundingClientRect().top;
    const windowHeight = window.innerHeight;

    if (sectionTop < windowHeight * 0.8) {
      section.classList.add('appear');
    }
  });
}

body.addEventListener('scroll', checkVisibility);
checkVisibility(); // Проверяем видимость при загрузке страницы

// --- Обработка клика на гамбургер-меню ---
const hamburgerMenu = document.querySelector('.hamburger-menu');
const mobileMenu = document.querySelector('.mobile-menu');

hamburgerMenu.addEventListener('click', () => {
  mobileMenu.classList.toggle('active');
});

// Закрытие меню при клике вне его
document.addEventListener('click', (event) => {
  const isClickInsideMenu = hamburgerMenu.contains(event.target) || mobileMenu.contains(event.target); 

  if (!isClickInsideMenu && mobileMenu.classList.contains('active')) {
    mobileMenu.classList.remove('active');
  }
});

// --- Обработка отправки формы ---

const form = document.getElementById('application-form');
const submitButton = document.getElementById("submitButton")
const formStatus = document.createElement('div'); // Создаем элемент для отображения статуса
form.parentNode.insertBefore(formStatus, form.nextSibling); // Добавляем его после формы

submitButton.addEventListener('click', function(event) {
  event.preventDefault(); // Предотвращаем отправку формы по умолчанию

  // Проверка на заполненность полей
  let isValid = true;
  const requiredFields = form.querySelectorAll('[required]');
  requiredFields.forEach(field => {
    if (!field.value) {
      field.classList.add('invalid'); // Добавляем класс для визуального выделения
      isValid = false;
    } else {
      field.classList.remove('invalid');
    }
  });

  if (!isValid) {
    formStatus.textContent = 'Пожалуйста, заполните все обязательные поля.';
    formStatus.style.fontSize = "14px";
    formStatus.style.color = 'firebrick';
    return; // Прекращаем отправку, если есть незаполненные поля
  }
  // Получаем кнопку "Отправить"
  const submitButton = document.querySelector('.btn'); 

  // Изменяем текст кнопки на "Отправка..."
  submitButton.textContent = 'Отправка...';
  submitButton.disabled = true; // Делаем кнопку неактивной

  fetch(form.action, {
    method: 'POST',
    body: new FormData(form)
  })
  .then(response => {
    if (!response.ok) {
      throw new Error('Ошибка отправки формы'); 
    }
    return response.text(); // Или response.json() если сервер возвращает JSON
  })
  .then(data => {
    // Обрабатываем успешный ответ сервера
    console.log(data); 
    formStatus.textContent = 'Заявка успешно отправлена!';
    formStatus.style.color = 'green';
    form.reset(); // Очищаем форму
    closeModal(); // Закрываем модальное окно (если нужно)
  })
  .catch(error => {
    // Обрабатываем ошибки
    console.error('Ошибка:', error);
    formStatus.textContent = 'Произошла ошибка при отправке заявки. Пожалуйста, попробуйте позже.';
    formStatus.style.color = 'red';
  })
  .finally(() => {
    // Восстанавливаем текст кнопки и делаем ее активной
    submitButton.textContent = 'Отправить';
    submitButton.disabled = false;
  });
});