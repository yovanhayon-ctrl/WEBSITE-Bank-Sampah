// BERANDA SPECIFIC JS
document.addEventListener("DOMContentLoaded", function () {
  // Counter animation
  const counters = document.querySelectorAll(".stat-number[data-target]");
  const animateCounters = () => {
    counters.forEach((counter) => {
      const target = +counter.getAttribute("data-target");
      const count = +counter.innerText;
      const increment = target / 200;
      if (count < target) {
        counter.innerText = Math.ceil(count + increment);
        setTimeout(animateCounters, 10);
      } else {
        counter.innerText = target + (target === 2.5 ? ".0" : "");
      }
    });
  };

  // Scroll animations
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = "1";
        entry.target.style.transform = "translateY(0)";
        if (entry.target.closest(".hero")) animateCounters();
      }
    });
  });

  document.querySelectorAll(".feature-card").forEach((el) => {
    el.style.opacity = "0";
    el.style.transform = "translateY(30px)";
    el.style.transition = "all 0.6s ease";
    observer.observe(el);
  });
});
