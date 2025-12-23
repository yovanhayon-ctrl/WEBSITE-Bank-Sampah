// TENTANG PAGE SPECIFIC JS
document.addEventListener("DOMContentLoaded", function () {
  // Staggered animations untuk feature items
  const featureItems = document.querySelectorAll(".feature-item");
  featureItems.forEach((item, index) => {
    item.style.opacity = "0";
    item.style.transform = "translateY(30px)";

    setTimeout(() => {
      item.style.transition =
        "all 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275)";
      item.style.opacity = "1";
      item.style.transform = "translateY(0)";
    }, index * 200);
  });

  // Team card hover effects
  document.querySelectorAll(".team-card").forEach((card, index) => {
    card.addEventListener("mouseenter", function () {
      this.style.transform = "translateY(-15px) scale(1.02)";
    });

    card.addEventListener("mouseleave", function () {
      this.style.transform = "translateY(0) scale(1)";
    });

    // Staggered entrance
    setTimeout(() => {
      card.style.opacity = "0";
      card.style.transform = "translateY(50px)";
      card.style.transition = "all 0.8s ease";

      setTimeout(() => {
        card.style.opacity = "1";
        card.style.transform = "translateY(0)";
      }, 100);
    }, index * 150 + 800);
  });

  // Parallax effect untuk recycle animation
  window.addEventListener("scroll", () => {
    const scrolled = window.pageYOffset;
    const recycleIcon = document.querySelector(".recycle-icon");
    if (recycleIcon) {
      const rate = scrolled * -0.5;
      recycleIcon.style.transform = `translateY(${rate}px) rotate(${
        scrolled * 0.1
      }deg)`;
    }
  });

  // Scroll-triggered animations
  const observerOptions = {
    threshold: 0.1,
    rootMargin: "0px 0px -50px 0px",
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = "1";
        entry.target.style.transform = "translateY(0)";
      }
    });
  }, observerOptions);

  // Observe sections
  document.querySelectorAll(".about-section > div > *").forEach((el) => {
    el.style.opacity = "0";
    el.style.transform = "translateY(40px)";
    el.style.transition = "all 0.8s ease";
    observer.observe(el);
  });
});
