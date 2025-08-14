document.querySelectorAll(".filter-accordion__header").forEach((header) => {
  header.addEventListener("click", () => {
    header.classList.toggle("is-open");
    header.nextElementSibling.classList.toggle("is-open");
  });
});

document.querySelectorAll(".filter-accordion__second").forEach((sec) => {
  const panel = sec.querySelector(".filter-accordion__second-panel");
  sec.addEventListener("click", () => {
    if (!panel) return; // если панели нет – выходим
    sec.classList.toggle("is-open"); // чтобы поворачивать стрелку
    panel.classList.toggle("is-open"); // открываем/закрываем содержимое
  });
});
