document.addEventListener("DOMContentLoaded", () => {
  // --- Mobile Navigation Logic ---
  const hamburger = document.querySelector(".hamburger");
  const navLinks = document.querySelector(".nav-links");
  const links = document.querySelectorAll(".nav-links li");

  hamburger.addEventListener("click", () => {
    // Toggle Nav
    navLinks.classList.toggle("nav-active");

    // Hamburger Animation Toggle (Icon change)
    const icon = hamburger.querySelector("i");
    if (navLinks.classList.contains("nav-active")) {
      icon.classList.remove("fa-bars");
      icon.classList.add("fa-times");
    } else {
      icon.classList.remove("fa-times");
      icon.classList.add("fa-bars");
    }
  });

  // Close mobile menu when clicking a link
  links.forEach((link) => {
    link.addEventListener("click", () => {
      navLinks.classList.remove("nav-active");
      const icon = hamburger.querySelector("i");
      icon.classList.remove("fa-times");
      icon.classList.add("fa-bars");
    });
  });

  // --- Scroll Reveal Animation ---
  const revealElements = document.querySelectorAll(".reveal");

  const revealOnScroll = () => {
    const windowHeight = window.innerHeight;
    const elementVisible = 150; // Distance from bottom

    revealElements.forEach((element) => {
      const elementTop = element.getBoundingClientRect().top;
      if (elementTop < windowHeight - elementVisible) {
        element.classList.add("active");
      }
    });
  };

  // Trigger once on load
  revealOnScroll();
  // Trigger on scroll
  window.addEventListener("scroll", revealOnScroll);

  // --- Sticky Header Shadow & Border Color Change ---
  const header = document.querySelector("header");
  window.addEventListener("scroll", () => {
    if (window.scrollY > 50) {
      header.style.boxShadow = "0 2px 20px rgba(255,136,0,0.15)";
      header.style.borderBottomColor = "var(--primary)";
    } else {
      header.style.boxShadow = "none";
      header.style.borderBottomColor = "rgba(255,255,255,0.05)";
    }
  });
});
