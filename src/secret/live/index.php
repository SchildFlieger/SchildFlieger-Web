<?php
session_start();

// Handle logout
if (isset($_GET['logout']) && $_GET['logout'] === 'true') {
    // Unset all session variables
    $_SESSION = array();
    
    // Destroy the session
    session_destroy();
    
    // Redirect to login page
    header('Location: /');
    exit();
}

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Redirect to login page if not authenticated
    header('Location: /secret/index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SchildFlieger | Live Area</title>
    <meta
      name="description"
      content="Live streaming area for SchildFlieger."
    />
    <link rel="stylesheet" href="/assets/css/main.css" />
    <!-- Externe Icons (FontAwesome) -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />

    <!-- Google Fonts: 'Oswald' für Überschriften (Gaming-Look), 'Inter' für Text -->
    <link
      href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=Oswald:wght@500;700&display=swap"
      rel="stylesheet"
    />
    
    <style>
      /* Black Theme for Live Area */
      :root {
        --bg-dark: #000000; /* Pure black background */
        --bg-card: #1a1a1a; /* Dark gray cards */
        --primary: #9933ff; /* Purple accent */
        --primary-glow: rgba(153, 51, 255, 0.4); /* Purple glow */
        --accent: #ffffff; /* White text */
        --text-main: #e0e0e0; /* Light gray text */
        --text-muted: #a0a0a0; /* Muted gray text */
        --border-col: #333333; /* Dark gray border */
      }
      
      body {
        background-color: var(--bg-dark);
        color: var(--text-main);
      }
      
      .hero {
        background: radial-gradient(
          circle at top center,
          rgba(153, 51, 255, 0.1),
          transparent 60%
        );
      }
      
      .card {
        background-color: var(--bg-card);
        border: 1px solid var(--border-col);
      }
      
      .btn-primary {
        background-color: var(--primary);
        color: #fff;
        border-color: var(--primary);
      }
      
      .btn-primary:hover {
        background-color: transparent;
        color: var(--primary);
        box-shadow: 0 0 20px var(--primary-glow);
      }
      
      .btn-accent {
        background-color: transparent;
        border-color: var(--primary);
        color: var(--primary);
      }
      
      .btn-accent:hover {
        background-color: var(--primary);
        color: #fff;
        box-shadow: 0 0 20px var(--primary-glow);
      }
      
      header {
        background-color: rgba(0, 0, 0, 0.95);
      }
      
      footer {
        background-color: #000000;
      }
    </style>
  </head>
  <body>
    <!-- Header / Navigation -->
    <header>
      <div class="container nav-container">
        <!-- Logo Text im Stil des Logos -->
        <a href="/" class="logo">Schild<span>Flieger</span></a>

        <nav>
          <ul class="nav-links">
            <li><a href="/secret/live/index.php">Home</a></li>
          </ul>
        </nav>

        <div class="hamburger">
          <i class="fas fa-bars"></i>
        </div>
      </div>
    </header>

    <!-- Hero Section -->
    <section id="hero" class="hero">
      <!-- Fullscreen Background Video -->
      <video autoplay muted loop playsinline class="background-video">
        <source src="/assets/video/bedwarsvod.mp4" type="video/mp4" />
        Your browser does not support the video tag.
      </video>
      <div class="container hero-content reveal">
        <h4>Live Streaming Area</h4>
        <h1>Welcome,<br /><span class="highlight">Live Viewer</span></h1>
        <p class="hero-subtitle">
          You have successfully accessed the exclusive live streaming area.
        </p>
        <div class="hero-buttons">
          <a href="?logout=true" class="btn btn-accent"
            ><i class="fas fa-sign-out-alt"></i> Logout</a
          >
        </div>
      </div>
    </section>

    <!-- Content Section -->
    <section id="content" class="section-padding">
      <div class="container">
        <h2 class="section-title">Live Content</h2>
        <p style="color: var(--text-muted)">
          Exclusive content for verified viewers.
        </p>

        <div class="grid-3">
          <!-- Live Stream Card -->
          <div class="card reveal">
            <div class="card-icon code-icon">
              <i class="fas fa-video"></i>
            </div>
            <h3>Current Live Stream</h3>
            <p>
              Access to the ongoing live stream with exclusive content.
            </p>
            <a href="#" class="card-link"
              >Watch Now <i class="fas fa-arrow-right"></i
            ></a>
          </div>

          <!-- Chat Card -->
          <div class="card reveal">
            <div class="card-icon code-icon">
              <i class="fas fa-comments"></i>
            </div>
            <h3>Exclusive Chat</h3>
            <p>
              Join the private chat room for live stream viewers.
            </p>
            <a href="#" class="card-link"
              >Join Chat <i class="fas fa-arrow-right"></i
            ></a>
          </div>

          <!-- VODs Card -->
          <div class="card reveal">
            <div class="card-icon code-icon">
              <i class="fas fa-history"></i>
            </div>
            <h3>Past Broadcasts</h3>
            <p>
              Access to archived live streams and special events.
            </p>
            <a href="#" class="card-link"
              >View Archive <i class="fas fa-arrow-right"></i
            ></a>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer>
      <div class="container">
        <div class="footer-content">
          <a href="/" class="logo">Schild<span>Flieger</span></a>
          <div class="social-row">
            <a href="https://twitch.tv/schildflieger" class="social-btn"
              ><i class="fab fa-twitch"></i
            ></a>
            <a href="https://www.youtube.com/@SchildFlieger" class="social-btn"
              ><i class="fab fa-youtube"></i
            ></a>
            <a href="https://github.com/SchildFlieger" class="social-btn"
              ><i class="fab fa-github"></i
            ></a>
          </div>
        </div>
        <div class="copyright">
          <a href="https://schildflieger.hmt-network.de/"
            >SchildFlieger Website</a
          >
          © 2025 by
          <a href="https://schildflieger.hmt-network.de/">SchildFlieger</a> is
          licensed under
          <a href="https://creativecommons.org/licenses/by-nc-sa/4.0/"
            >CC BY-NC-SA 4.0</a
          ><img
            src="https://mirrors.creativecommons.org/presskit/icons/cc.svg"
            alt=""
            style="max-width: 1em; max-height: 1em; margin-left: 0.2em"
          /><img
            src="https://mirrors.creativecommons.org/presskit/icons/by.svg"
            alt=""
            style="max-width: 1em; max-height: 1em; margin-left: 0.2em"
          /><img
            src="https://mirrors.creativecommons.org/presskit/icons/nc.svg"
            alt=""
            style="max-width: 1em; max-height: 1em; margin-left: 0.2em"
          /><img
            src="https://mirrors.creativecommons.org/presskit/icons/sa.svg"
            alt=""
            style="max-width: 1em; max-height: 1em; margin-left: 0.2em"
          />
        </div>
      </div>
    </footer>
    <script src="/assets/js/main.js"></script>
  </body>
</html>