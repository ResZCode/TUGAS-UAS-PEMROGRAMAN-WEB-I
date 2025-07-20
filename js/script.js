document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute("href"));
      if (target) {
        target.scrollIntoView({ behavior: "smooth" });
      }
    });
  });

  const forms = document.querySelectorAll("form");
  forms.forEach(form => {
    form.addEventListener("submit", function (e) {
      const requiredFields = form.querySelectorAll("input[required], select[required], textarea[required]");
      let valid = true;

      requiredFields.forEach(field => {
        if (!field.value.trim()) {
          field.style.borderColor = "red";
          valid = false;
        } else {
          field.style.borderColor = "#d1d5db";
        }
      });

      if (!valid) {
        e.preventDefault();
        alert("Mohon lengkapi semua data terlebih dahulu.");
      }
    });
  });

  const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add("fade-in-visible");
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll(".form-section, .dashboard-section, .history-section, .contact-section").forEach(el => {
    observer.observe(el);
  });
});

document.querySelector('.btn-plus').addEventListener('click', () => {
  const input = document.querySelector('input[name="jumlah"]');
  input.value = Number(input.value) + 1;
});

document.querySelector('.btn-minus').addEventListener('click', () => {
  const input = document.querySelector('input[name="jumlah"]');
  if (Number(input.value) > 1) {
    input.value = Number(input.value) - 1;
  }
});