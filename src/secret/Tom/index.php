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
    // Store the intended destination in session
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
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
    <title>SchildFlieger | Tom's Video Collection</title>
    <meta
      name="description"
      content="Exclusive video collection for Tom."
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
      /* Tom's Video Theme - Modern Dark with Electric Blue Accents */
      :root {
        --bg-dark: #0a0a12; /* Deep space black */
        --bg-card: #161625; /* Rich dark blue-gray cards */
        --primary: #00f3ff; /* Electric blue */
        --primary-glow: rgba(0, 243, 255, 0.4); /* Electric blue glow */
        --accent: #ff00c8; /* Neon pink */
        --accent-glow: rgba(255, 0, 200, 0.4); /* Neon pink glow */
        --text-main: #f0f0f0; /* Light text */
        --text-muted: #a0a0b0; /* Muted blue-gray text */
        --border-col: #2a2a4a; /* Dark blue border */
        --gradient-start: #0f0c29;
        --gradient-middle: #302b63;
        --gradient-end: #24243e;
      }
      
      body {
        background: linear-gradient(135deg, var(--gradient-start), var(--gradient-middle), var(--gradient-end));
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
        color: var(--text-main);
        min-height: 100vh;
        margin: 0;
        padding: 0;
      }
      
      @keyframes gradientBG {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
      }
      
      .hero {
        background: radial-gradient(
          ellipse at center,
          rgba(0, 243, 255, 0.1) 0%,
          transparent 70%
        );
        position: relative;
        overflow: hidden;
      }
      
      .hero::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: 
          radial-gradient(circle at 10% 20%, rgba(0, 243, 255, 0.1) 0%, transparent 20%),
          radial-gradient(circle at 90% 80%, rgba(255, 0, 200, 0.1) 0%, transparent 20%);
        pointer-events: none;
      }
      
      .card {
        background-color: var(--bg-card);
        border: 1px solid var(--border-col);
        border-radius: 12px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        backdrop-filter: blur(10px);
        position: relative;
        overflow: hidden;
      }
      
      .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
        border-color: var(--primary);
      }
      
      .card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), var(--accent));
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s ease;
      }
      
      .card:hover::before {
        transform: scaleX(1);
      }
      
      .btn-primary {
        background: linear-gradient(90deg, var(--primary), var(--accent));
        color: #000;
        border: none;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        font-weight: 600;
        letter-spacing: 1px;
      }
      
      .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px var(--primary-glow);
      }
      
      .btn-primary::after {
        content: "";
        position: absolute;
        top: -50%;
        left: -60%;
        width: 20px;
        height: 200%;
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(30deg);
        transition: all 0.6s;
      }
      
      .btn-primary:hover::after {
        left: 120%;
      }
      
      .btn-accent {
        background-color: transparent;
        border: 2px solid var(--primary);
        color: var(--primary);
        transition: all 0.3s ease;
      }
      
      .btn-accent:hover {
        background: linear-gradient(90deg, var(--primary), var(--accent));
        color: #000;
        box-shadow: 0 0 20px var(--primary-glow);
        border-color: transparent;
      }
      
      header {
        background-color: rgba(10, 10, 18, 0.95);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid var(--border-col);
      }
      
      footer {
        background-color: rgba(10, 10, 18, 0.95);
        backdrop-filter: blur(10px);
        border-top: 1px solid var(--border-col);
      }
      
      /* Video Icon Styling */
      .video-icon {
        color: var(--primary);
        text-align: center;
        margin-bottom: 15px;
      }
      
      .video-icon i {
        font-size: 2.5rem;
        background: linear-gradient(90deg, var(--primary), var(--accent));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
      }
      
      /* Video Player Styles */
      .video-container {
        position: relative;
        width: 100%;
        height: 0;
        padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 20px;
        background-color: #000;
      }
      
      .video-container iframe,
      .video-container video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
      }
      
      .video-title {
        font-family: "Oswald", sans-serif;
        font-size: 1.4rem;
        margin: 15px 0 10px;
        color: var(--text-main);
      }
      
      .video-description {
        color: var(--text-muted);
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 15px;
      }
      
      .video-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.85rem;
        color: var(--text-muted);
        border-top: 1px solid var(--border-col);
        padding-top: 15px;
        margin-top: 15px;
      }
      
      .play-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: rgba(0, 243, 255, 0.1);
        border: 1px solid var(--primary);
        color: var(--primary);
        border-radius: 30px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
      }
      
      .play-button:hover {
        background: rgba(0, 243, 255, 0.2);
        box-shadow: 0 0 15px var(--primary-glow);
      }
      
      .section-title {
        position: relative;
        padding-bottom: 15px;
      }
      
      .section-title::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: 0;
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, var(--primary), var(--accent));
        border-radius: 2px;
      }
      
      /* Glowing effect for important elements */
      .glow {
        animation: glow 2s ease-in-out infinite alternate;
      }
      
      @keyframes glow {
        from {
          filter: drop-shadow(0 0 5px var(--primary-glow));
        }
        to {
          filter: drop-shadow(0 0 15px var(--primary-glow));
        }
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
            <li><a href="/">Home</a></li>
          </ul>
        </nav>

        <div class="hamburger">
          <i class="fas fa-bars"></i>
        </div>
      </div>
    </header>

    <!-- Hero Section -->
    <section id="hero" class="hero">
      <div class="container hero-content reveal">
        <h4>Tom's Exclusive Collection</h4>
        <h1>Welcome,<br /><span class="highlight glow">Tom</span></h1>
        <p class="hero-subtitle">
          Enjoy your private collection of videos and memories.
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
        <h2 class="section-title">Video Collection</h2>
        <p style="color: var(--text-muted)">
          Your personal collection of videos and special moments.
        </p>

        <div class="grid-3">
          <!-- Video 1 -->
          <div class="card reveal">
            <div class="video-icon">
              <i class="fas fa-video"></i>
            </div>
            <h3 class="video-title">Amena Tom</h3>
            <p class="video-description">
              A special moment captured with Amena.
            </p>
            <div class="video-container">
              <video controls>
                <source src="/secret/Videos Passwort/Amena Tom.mp4" type="video/mp4">
                Your browser does not support the video tag.
              </video>
            </div>
            <div class="video-meta">
              <span><i class="fas fa-file-video"></i> MP4</span>
              <span><i class="fas fa-clock"></i> 294 KB</span>
            </div>
          </div>

          <!-- Image 1 -->
          <div class="card reveal">
            <div class="video-icon">
              <i class="fas fa-image"></i>
            </div>
            <h3 class="video-title">Bild Theo</h3>
            <p class="video-description">
              A memorable photo with Theo.
            </p>
            <div class="video-container">
              <img src="/secret/Videos Passwort/Bild Theo.jpeg" alt="Bild Theo" style="width: 100%; height: auto; border-radius: 8px;">
            </div>
            <div class="video-meta">
              <span><i class="fas fa-file-image"></i> JPEG</span>
              <span><i class="fas fa-clock"></i> 96 KB</span>
            </div>
          </div>

          <!-- Image 2 -->
          <div class="card reveal">
            <div class="video-icon">
              <i class="fas fa-image"></i>
            </div>
            <h3 class="video-title">Bild Tom</h3>
            <p class="video-description">
              A special picture of Tom.
            </p>
            <div class="video-container">
              <img src="/secret/Videos Passwort/Bild Tom.jpeg" alt="Bild Tom" style="width: 100%; height: auto; border-radius: 8px;">
            </div>
            <div class="video-meta">
              <span><i class="fas fa-file-image"></i> JPEG</span>
              <span><i class="fas fa-clock"></i> 264 KB</span>
            </div>
          </div>

          <!-- Video 2 -->
          <div class="card reveal">
            <div class="video-icon">
              <i class="fas fa-video"></i>
            </div>
            <h3 class="video-title">Franzosen Tom</h3>
            <p class="video-description">
              An exciting adventure with the French friends.
            </p>
            <div class="video-container">
              <video controls>
                <source src="/secret/Videos Passwort/Franzosen Tom.mp4" type="video/mp4">
                Your browser does not support the video tag.
              </video>
            </div>
            <div class="video-meta">
              <span><i class="fas fa-file-video"></i> MP4</span>
              <span><i class="fas fa-clock"></i> 1.3 MB</span>
            </div>
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