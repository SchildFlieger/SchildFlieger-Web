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

  // --- Fullscreen Video Functionality ---
  // Note: The fullscreen video is handled via the link to video-fullscreen.html

  // --- Twitch Stream Status ---
  const twitchStatusElement = document.getElementById("twitch-status");

  // Function to update Twitch status display
  function updateTwitchStatus(isLive, viewerCount = 0) {
    if (twitchStatusElement) {
      twitchStatusElement.classList.remove(
        "status-loading",
        "status-live",
        "status-offline"
      );

      if (isLive) {
        twitchStatusElement.textContent = `LIVE${
          viewerCount ? " | " + viewerCount + " Zuschauer" : ""
        }`;
        twitchStatusElement.classList.add("status-live");
      } else {
        twitchStatusElement.textContent = "Offline";
        twitchStatusElement.classList.add("status-offline");
      }
    }
  }

  // Fetch Twitch stream status
  async function fetchTwitchStatus() {
    try {
      // Using a free service to check Twitch status without API key
      // In a production environment, you would use the official Twitch API
      const response = await fetch(
        "https://decapi.me/twitch/status/schildflieger"
      );

      if (response.ok) {
        const data = await response.text();
        // DecAPI returns "Username is offline" or "Username is streaming: Title (Game) for HH:MM:SS with N viewers"
        const isLive = !data.includes("is offline");

        if (isLive) {
          // Extract viewer count if available
          const viewerMatch = data.match(/with ([0-9,]+) viewers/);
          const viewers = viewerMatch
            ? parseInt(viewerMatch[1].replace(",", ""))
            : 0;
          updateTwitchStatus(true, viewers);
        } else {
          updateTwitchStatus(false);
        }
      } else {
        throw new Error("Failed to fetch status");
      }
    } catch (error) {
      console.error("Error fetching Twitch status:", error);
      // Fallback to offline status
      updateTwitchStatus(false);
    }
  }

  // Initial fetch
  if (twitchStatusElement) {
    fetchTwitchStatus();
    // Update every 5 minutes
    setInterval(fetchTwitchStatus, 5 * 60 * 1000);
  }
});
