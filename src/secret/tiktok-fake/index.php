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
        padding-bottom: 177.78%; /* 9:16 Aspect Ratio (16/9 = 1.7778) */
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
        object-fit: cover;
      }
      
      .video-container img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
        object-fit: contain;
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
      
      /* TikTok Mode Styles */
      .tiktok-toggle {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--accent));
        color: white;
        border: none;
        font-size: 24px;
        cursor: pointer;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
      }
      
      .tiktok-toggle:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 25px rgba(0, 243, 255, 0.5);
      }
      
      .tiktok-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: var(--bg-dark);
        z-index: 999;
        display: none;
        overflow: hidden;
      }
      
      .tiktok-media-container {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
      }
      
      .tiktok-media {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: opacity 0.3s ease;
      }
      
      .tiktok-video {
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: opacity 0.3s ease;
      }
      
      .tiktok-media-container {
        transition: transform 0.3s ease;
      }
      
      /* Animation for swipe feedback */
      .swipe-up {
        animation: swipeUp 0.5s ease;
      }
      
      .swipe-down {
        animation: swipeDown 0.5s ease;
      }
      
      @keyframes swipeUp {
        0% { transform: translateY(0); }
        50% { transform: translateY(-50px); }
        100% { transform: translateY(0); }
      }
      
      @keyframes swipeDown {
        0% { transform: translateY(0); }
        50% { transform: translateY(50px); }
        100% { transform: translateY(0); }
      }
      
      .tiktok-controls {
        position: absolute;
        bottom: 30px;
        left: 0;
        right: 0;
        display: flex;
        justify-content: center;
        gap: 20px;
        z-index: 1001;
      }
      
      .tiktok-control-btn {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: none;
        font-size: 20px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
      }
      
      .tiktok-control-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
      }
      
      .tiktok-like-btn {
        position: absolute;
        right: 30px;
        bottom: 100px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: none;
        font-size: 24px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
      }
      
      .tiktok-like-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
      }
      
      .tiktok-like-btn.liked {
        color: #ff0000;
        animation: pulse 0.5s ease;
      }
      
      @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
      }
      
      .tiktok-like-count {
        position: absolute;
        right: 35px;
        bottom: 170px;
        background: rgba(0, 0, 0, 0.5);
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 16px;
        backdrop-filter: blur(10px);
      }
      
      .tiktok-close-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        border: none;
        font-size: 20px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        z-index: 1002;
      }
      
      .tiktok-close-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
      }
      
      /* Grid View Like Buttons */
      .like-button {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid var(--border-col);
        border-radius: 20px;
        color: var(--text-muted);
        padding: 8px 15px;
        margin: 10px auto;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: "Inter", sans-serif;
      }
      
      .like-button:hover {
        background: rgba(255, 255, 255, 0.2);
        color: var(--primary);
        border-color: var(--primary);
      }
      
      .like-button.liked {
        color: #ff0000;
      }
      
      .like-count {
        font-size: 0.9rem;
      }
      
      /* Like Animation */
      @keyframes likePulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.3); }
        100% { transform: scale(1); }
      }
      
      .like-button.liked-animation {
        animation: likePulse 0.5s ease;
        color: #ff0000;
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
            <button class="like-button" data-filename="Amena Tom.mp4">
              <i class="fas fa-heart"></i> <span class="like-count">0</span>
            </button>
            <div class="video-meta">
              <span><i class="fas fa-file-video"></i> MP4</span>
              <span><i class="fas fa-clock"></i> 295 KB</span>
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
            <button class="like-button" data-filename="Bild Theo.jpeg">
              <i class="fas fa-heart"></i> <span class="like-count">0</span>
            </button>
            <div class="video-meta">
              <span><i class="fas fa-file-image"></i> JPEG</span>
              <span><i class="fas fa-clock"></i> 99 KB</span>
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
            <button class="like-button" data-filename="Bild Tom.jpeg">
              <i class="fas fa-heart"></i> <span class="like-count">0</span>
            </button>
            <div class="video-meta">
              <span><i class="fas fa-file-image"></i> JPEG</span>
              <span><i class="fas fa-clock"></i> 265 KB</span>
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
            <button class="like-button" data-filename="Franzosen Tom.mp4">
              <i class="fas fa-heart"></i> <span class="like-count">0</span>
            </button>
            <div class="video-meta">
              <span><i class="fas fa-file-video"></i> MP4</span>
              <span><i class="fas fa-clock"></i> 1.3 MB</span>
            </div>
          </div>

          <!-- Image 3 -->
          <div class="card reveal">
            <div class="video-icon">
              <i class="fas fa-image"></i>
            </div>
            <h3 class="video-title">Bild Theo guffi</h3>
            <p class="video-description">
              A funny moment with Theo.
            </p>
            <div class="video-container">
              <img src="/secret/Videos Passwort/Bild Theo guffi.jpeg" alt="Bild Theo guffi" style="width: 100%; height: auto; border-radius: 8px;">
            </div>
            <button class="like-button" data-filename="Bild Theo guffi.jpeg">
              <i class="fas fa-heart"></i> <span class="like-count">0</span>
            </button>
            <div class="video-meta">
              <span><i class="fas fa-file-image"></i> JPEG</span>
              <span><i class="fas fa-clock"></i> 34 KB</span>
            </div>
          </div>

          <!-- Image 4 -->
          <div class="card reveal">
            <div class="video-icon">
              <i class="fas fa-image"></i>
            </div>
            <h3 class="video-title">Bild Theo Random</h3>
            <p class="video-description">
              Random moment with Theo.
            </p>
            <div class="video-container">
              <img src="/secret/Videos Passwort/Bild Theo Random.jpeg" alt="Bild Theo Random" style="width: 100%; height: auto; border-radius: 8px;">
            </div>
            <button class="like-button" data-filename="Bild Theo Random.jpeg">
              <i class="fas fa-heart"></i> <span class="like-count">0</span>
            </button>
            <div class="video-meta">
              <span><i class="fas fa-file-image"></i> JPEG</span>
              <span><i class="fas fa-clock"></i> 231 KB</span>
            </div>
          </div>

          <!-- Image 5 -->
          <div class="card reveal">
            <div class="video-icon">
              <i class="fas fa-image"></i>
            </div>
            <h3 class="video-title">Bild Theo Schlafen</h3>
            <p class="video-description">
              Theo sleeping.
            </p>
            <div class="video-container">
              <img src="/secret/Videos Passwort/Bild Theo Schlafen.jpeg" alt="Bild Theo Schlafen" style="width: 100%; height: auto; border-radius: 8px;">
            </div>
            <button class="like-button" data-filename="Bild Theo Schlafen.jpeg">
              <i class="fas fa-heart"></i> <span class="like-count">0</span>
            </button>
            <div class="video-meta">
              <span><i class="fas fa-file-image"></i> JPEG</span>
              <span><i class="fas fa-clock"></i> 139 KB</span>
            </div>
          </div>

          <!-- Image 6 -->
          <div class="card reveal">
            <div class="video-icon">
              <i class="fas fa-image"></i>
            </div>
            <h3 class="video-title">Bild Tom klein</h3>
            <p class="video-description">
              Small picture of Tom.
            </p>
            <div class="video-container">
              <img src="/secret/Videos Passwort/Bild Tom klein.jpeg" alt="Bild Tom klein" style="width: 100%; height: auto; border-radius: 8px;">
            </div>
            <button class="like-button" data-filename="Bild Tom klein.jpeg">
              <i class="fas fa-heart"></i> <span class="like-count">0</span>
            </button>
            <div class="video-meta">
              <span><i class="fas fa-file-image"></i> JPEG</span>
              <span><i class="fas fa-clock"></i> 109 KB</span>
            </div>
          </div>

          <!-- Image 7 -->
          <div class="card reveal">
            <div class="video-icon">
              <i class="fas fa-image"></i>
            </div>
            <h3 class="video-title">Erik</h3>
            <p class="video-description">
              Picture with Erik.
            </p>
            <div class="video-container">
              <img src="/secret/Videos Passwort/Erik.jpeg" alt="Erik" style="width: 100%; height: auto; border-radius: 8px;">
            </div>
            <button class="like-button" data-filename="Erik.jpeg">
              <i class="fas fa-heart"></i> <span class="like-count">0</span>
            </button>
            <div class="video-meta">
              <span><i class="fas fa-file-image"></i> JPEG</span>
              <span><i class="fas fa-clock"></i> 116 KB</span>
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
    <!-- TikTok Mode Toggle Button -->
    <button id="tiktokToggle" class="tiktok-toggle" title="TikTok Mode">
      <i class="fas fa-mobile-alt"></i>
    </button>
    
    <!-- TikTok Mode Container -->
    <div id="tiktokContainer" class="tiktok-container">
      <button id="tiktokClose" class="tiktok-close-btn">
        <i class="fas fa-times"></i>
      </button>
      <div id="tiktokMediaContainer" class="tiktok-media-container"></div>
      <div class="tiktok-controls">
        <button id="tiktokPrev" class="tiktok-control-btn">
          <i class="fas fa-arrow-up"></i>
        </button>
        <button id="tiktokNext" class="tiktok-control-btn">
          <i class="fas fa-arrow-down"></i>
        </button>
      </div>
      <button id="tiktokLike" class="tiktok-like-btn">
        <i class="fas fa-heart"></i>
      </button>
      <div id="tiktokLikeCount" class="tiktok-like-count">0</div>
    </div>
    
    <script src="/assets/js/main.js"></script>
    <script>
      // Combined TikTok Mode and Grid View Like Functionality
      // Since main.js already handles DOMContentLoaded, we can execute immediately
      // but we'll still wrap in a function to ensure DOM is ready
      function initializeLikeFunctionality() {
        // Media files array for TikTok mode
        const mediaFiles = [
          { filename: 'Amena Tom.mp4', type: 'video' },
          { filename: 'Bild Theo guffi.jpeg', type: 'image' },
          { filename: 'Bild Theo Random.jpeg', type: 'image' },
          { filename: 'Bild Theo Schlafen.jpeg', type: 'image' },
          { filename: 'Bild Theo.jpeg', type: 'image' },
          { filename: 'Bild Tom klein.jpeg', type: 'image' },
          { filename: 'Bild Tom.jpeg', type: 'image' },
          { filename: 'Erik.jpeg', type: 'image' },
          { filename: 'Franzosen Tom.mp4', type: 'video' }
        ];
        
        // DOM Elements for TikTok mode
        const tiktokToggle = document.getElementById('tiktokToggle');
        const tiktokContainer = document.getElementById('tiktokContainer');
        const tiktokClose = document.getElementById('tiktokClose');
        const tiktokMediaContainer = document.getElementById('tiktokMediaContainer');
        const tiktokPrev = document.getElementById('tiktokPrev');
        const tiktokNext = document.getElementById('tiktokNext');
        const tiktokLike = document.getElementById('tiktokLike');
        const tiktokLikeCount = document.getElementById('tiktokLikeCount');
        
        // Get all like buttons in grid view
        const likeButtons = document.querySelectorAll('.like-button');
        
        // State variables
        let currentIndex = 0;
        let likes = {};
        
        // Initialize likes from server (shared between TikTok mode and grid view)
        fetch('/secret/tiktok-fake/get_likes.php')
          .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
              throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
          })
          .then(data => {
            console.log('Likes data received:', data);
            if (data.success) {
              likes = data.likes;
              updateAllLikeCounts();
              updateTikTokLikeCount();
              // Also update TikTok like button state for the first media
              if (mediaFiles.length > 0) {
                updateTikTokLikeButtonState(mediaFiles[0].filename);
              }
            } else {
              console.error('Error in likes data:', data.error);
              // Fallback: initialize with empty likes
              likes = {};
              updateAllLikeCounts();
              updateTikTokLikeCount();
            }
          })
          .catch(error => {
            console.error('Error loading likes:', error);
            // Fallback: initialize with empty likes
            likes = {};
            updateAllLikeCounts();
            updateTikTokLikeCount();
          });
        
        // TikTok Mode Event Listeners
        
        // Toggle TikTok mode
        if (tiktokToggle) {
          tiktokToggle.addEventListener('click', function() {
            tiktokContainer.style.display = 'block';
            // Prevent body scrolling when in TikTok mode
            document.body.style.overflow = 'hidden';
            loadMedia(currentIndex);
          });
        }
        
        // Close TikTok mode
        if (tiktokClose) {
          tiktokClose.addEventListener('click', function() {
            tiktokContainer.style.display = 'none';
            // Restore body scrolling
            document.body.style.overflow = '';
            // Pause any playing video
            const video = tiktokMediaContainer.querySelector('video');
            if (video) {
              video.pause();
            }
            // Reset TikTok like button state
            if (tiktokLike) {
              tiktokLike.classList.remove('liked', 'liked-animation');
            }
          });
        }
        
        // Navigate to previous media
        if (tiktokPrev) {
          tiktokPrev.addEventListener('click', function() {
            currentIndex = (currentIndex - 1 + mediaFiles.length) % mediaFiles.length;
            loadMedia(currentIndex);
          });
        }
        
        // Navigate to next media
        if (tiktokNext) {
          tiktokNext.addEventListener('click', function() {
            currentIndex = (currentIndex + 1) % mediaFiles.length;
            loadMedia(currentIndex);
          });
        }
        
        // Like current media in TikTok mode
        if (tiktokLike) {
          tiktokLike.addEventListener('click', function() {
            likeMediaTikTok(currentIndex);
          });
        }
        
        // Attach all event listeners for TikTok mode
        // We'll attach this after loading media since we need to attach to the actual media element
        function attachEventListeners() {
          // Remove any existing double-click handlers to prevent duplicates
          const existingVideos = tiktokMediaContainer.querySelectorAll('video');
          const existingImgs = tiktokMediaContainer.querySelectorAll('img');
          
          // Remove existing event listeners
          tiktokMediaContainer.removeEventListener('touchstart', handleTouchStart);
          tiktokMediaContainer.removeEventListener('touchmove', handleTouchMove);
          tiktokMediaContainer.removeEventListener('touchend', handleTouchEnd);
          tiktokMediaContainer.removeEventListener('mousedown', handleMouseDown);
          tiktokMediaContainer.removeEventListener('mousemove', handleMouseMove);
          tiktokMediaContainer.removeEventListener('mouseup', handleMouseUp);
          tiktokMediaContainer.removeEventListener('mouseleave', handleMouseUp);
          tiktokMediaContainer.removeEventListener('wheel', handleWheel);
          
          existingVideos.forEach(video => {
            video.removeEventListener('dblclick', handleDoubleClick);
            video.addEventListener('dblclick', handleDoubleClick);
          });
          
          existingImgs.forEach(img => {
            img.removeEventListener('dblclick', handleDoubleClick);
            img.addEventListener('dblclick', handleDoubleClick);
          });
          
          // Add touch and mouse event listeners for swipe/scroll navigation
          tiktokMediaContainer.addEventListener('touchstart', handleTouchStart);
          tiktokMediaContainer.addEventListener('touchmove', handleTouchMove, { passive: false });
          tiktokMediaContainer.addEventListener('touchend', handleTouchEnd);
          tiktokMediaContainer.addEventListener('mousedown', handleMouseDown);
          tiktokMediaContainer.addEventListener('mousemove', handleMouseMove);
          tiktokMediaContainer.addEventListener('mouseup', handleMouseUp);
          tiktokMediaContainer.addEventListener('mouseleave', handleMouseUp);
          tiktokMediaContainer.addEventListener('wheel', handleWheel, { passive: false });
        }
        
        // Handler function for double-click events
        function handleDoubleClick(e) {
          e.preventDefault();
          // Add visual feedback for double-click
          if (tiktokLike) {
            tiktokLike.classList.add('liked-animation');
            setTimeout(() => {
              tiktokLike.classList.remove('liked-animation');
            }, 500);
          }
          likeMediaTikTok(currentIndex);
        }
        
        // Variables for swipe detection
        let startY = 0;
        let endY = 0;
        const SWIPE_THRESHOLD = 30; // Minimum distance for swipe (lower for more sensitivity)
        
        // Touch event handlers for swipe navigation
        function handleTouchStart(e) {
          startY = e.touches[0].clientY;
        }
        
        function handleTouchMove(e) {
          // Prevent default behavior to avoid scrolling the page
          e.preventDefault();
        }
        
        function handleTouchEnd(e) {
          endY = e.changedTouches[0].clientY;
          handleSwipe();
        }
        
        // Mouse event handlers for scroll navigation
        let startMouseY = 0;
        
        function handleMouseDown(e) {
          startMouseY = e.clientY;
        }
        
        function handleMouseMove(e) {
          // Only handle mouse move if mouse is down
          if (startMouseY !== 0) {
            e.preventDefault();
          }
        }
        
        function handleMouseUp(e) {
          if (startMouseY !== 0) {
            endY = e.clientY;
            handleSwipe();
            startMouseY = 0; // Reset
          }
        }
        
        function handleWheel(e) {
          // Prevent default scrolling behavior
          e.preventDefault();
          
          // Determine swipe direction based on wheel delta
          if (e.deltaY > 0) {
            // Scroll down - next item
            currentIndex = (currentIndex + 1) % mediaFiles.length;
            // Add visual feedback
            tiktokMediaContainer.classList.add('swipe-up');
            setTimeout(() => {
              tiktokMediaContainer.classList.remove('swipe-up');
            }, 500);
            loadMedia(currentIndex);
          } else if (e.deltaY < 0) {
            // Scroll up - previous item
            currentIndex = (currentIndex - 1 + mediaFiles.length) % mediaFiles.length;
            // Add visual feedback
            tiktokMediaContainer.classList.add('swipe-down');
            setTimeout(() => {
              tiktokMediaContainer.classList.remove('swipe-down');
            }, 500);
            loadMedia(currentIndex);
          }
        }
        
        // Handle swipe gesture
        function handleSwipe() {
          const deltaY = startY - endY;
          
          // Check if swipe distance exceeds threshold
          if (Math.abs(deltaY) > SWIPE_THRESHOLD) {
            if (deltaY > 0) {
              // Swipe up - next item
              currentIndex = (currentIndex + 1) % mediaFiles.length;
              // Add visual feedback
              tiktokMediaContainer.classList.add('swipe-up');
              setTimeout(() => {
                tiktokMediaContainer.classList.remove('swipe-up');
              }, 500);
              loadMedia(currentIndex);
            } else {
              // Swipe down - previous item
              currentIndex = (currentIndex - 1 + mediaFiles.length) % mediaFiles.length;
              // Add visual feedback
              tiktokMediaContainer.classList.add('swipe-down');
              setTimeout(() => {
                tiktokMediaContainer.classList.remove('swipe-down');
              }, 500);
              loadMedia(currentIndex);
            }
          }
        }
        
        // Grid View Event Listeners
        
        // Add event listeners to all like buttons
        likeButtons.forEach(button => {
          button.addEventListener('click', function() {
            const filename = this.getAttribute('data-filename');
            likeMediaGrid(filename, this);
          });
        });
        
        // TikTok Mode Functions
        
        // Load media into container
        function loadMedia(index) {
          const media = mediaFiles[index];
          if (tiktokMediaContainer) {
            // Add a subtle animation effect when switching media
            tiktokMediaContainer.style.opacity = '0';
            
            setTimeout(() => {
              tiktokMediaContainer.innerHTML = '';
              
              if (media.type === 'video') {
                const video = document.createElement('video');
                video.className = 'tiktok-video';
                video.controls = true;
                video.autoplay = true;
                video.loop = true;
                
                const source = document.createElement('source');
                source.src = '/secret/Videos Passwort/' + media.filename;
                source.type = 'video/mp4';
                
                video.appendChild(source);
                tiktokMediaContainer.appendChild(video);
              } else {
                const img = document.createElement('img');
                img.className = 'tiktok-media';
                img.src = '/secret/Videos Passwort/' + media.filename;
                img.alt = media.filename;
                tiktokMediaContainer.appendChild(img);
              }
              
              // Fade in the new media
              tiktokMediaContainer.style.opacity = '1';
              
              updateTikTokLikeCount();
              // Update TikTok like button state based on current like count
              updateTikTokLikeButtonState(media.filename);
              // Attach all event listeners to the newly loaded media
              attachEventListeners();
            }, 150); // Short delay for fade effect
          }
        }
        
        // Update like count display in TikTok mode
        function updateTikTokLikeCount() {
          if (tiktokLikeCount) {
            const media = mediaFiles[currentIndex];
            const count = likes[media.filename] || 0;
            tiktokLikeCount.textContent = count;
          }
        }
        
        // Update grid view buttons with new like count
        function updateGridViewButtons(filename, likeCount) {
          // Find all buttons for this filename
          const buttons = document.querySelectorAll(`.like-button[data-filename="${filename}"]`);
          buttons.forEach(button => {
            const likeCountElement = button.querySelector('.like-count');
            if (likeCountElement) {
              likeCountElement.textContent = likeCount;
            }
            // Add liked class if count > 0
            if (likeCount > 0) {
              button.classList.add('liked');
            }
          });
        }
        
        // Update TikTok like button state based on current like count
        function updateTikTokLikeButtonState(filename) {
          if (tiktokLike) {
            const likeCount = likes[filename] || 0;
            if (likeCount > 0) {
              tiktokLike.classList.add('liked');
            } else {
              tiktokLike.classList.remove('liked');
            }
          }
        }
        
        // Like current media in TikTok mode
        function likeMediaTikTok(index) {
          const media = mediaFiles[index];
          console.log('TikTok mode - Liking media:', media.filename);
          
          // Send like to server
          const likeUrl = '/secret/tiktok-fake/like_handler.php';
          console.log('TikTok mode - Sending like request to:', likeUrl);
          fetch(likeUrl, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({ filename: media.filename })
          })
          .then(response => {
            console.log('TikTok mode - Like response status:', response.status);
            if (!response.ok) {
              throw new Error(`HTTP error! status: ${response.status} - ${response.statusText}`);
            }
            return response.json();
          })
          .then(data => {
            console.log('TikTok mode - Like response data:', data);
            if (data.success) {
              likes[media.filename] = data.like_count;
              updateTikTokLikeCount();
              
              // Visual feedback with animation
              if (tiktokLike) {
                // Ensure any existing animation is stopped
                tiktokLike.classList.remove('liked', 'liked-animation');
                // Trigger reflow to restart animation
                void tiktokLike.offsetWidth;
                // Add classes for visual feedback
                tiktokLike.classList.add('liked');
                tiktokLike.classList.add('liked-animation');
                setTimeout(() => {
                  tiktokLike.classList.remove('liked-animation');
                }, 1000);
                // Also remove the liked class after a longer time to allow for unliking
                setTimeout(() => {
                  tiktokLike.classList.remove('liked');
                }, 3000);
              }
              
              // Also update grid view buttons if they exist on the page
              updateGridViewButtons(media.filename, data.like_count);
            } else {
              console.error('TikTok mode - Error in like response:', data.error);
              // Show error to user
              alert('Failed to like media. Server error: ' + data.error + '. Please try again.');
            }
          })
          .catch(error => {
            console.error('TikTok mode - Error liking media:', error);
            // Show detailed error to user
            alert('Failed to like media. Error: ' + error.message + '. Please try again. Note: Your likes may not be saved if there is a database connection issue.');
            
            // Fallback: update UI locally
            const currentCount = likes[media.filename] || 0;
            likes[media.filename] = currentCount + 1;
            updateTikTokLikeCount();
            
            // Visual feedback with animation
            if (tiktokLike) {
              // Ensure any existing animation is stopped
              tiktokLike.classList.remove('liked', 'liked-animation');
              // Trigger reflow to restart animation
              void tiktokLike.offsetWidth;
              // Add classes for visual feedback
              tiktokLike.classList.add('liked');
              tiktokLike.classList.add('liked-animation');
              setTimeout(() => {
                tiktokLike.classList.remove('liked-animation');
              }, 1000);
              // Also remove the liked class after a longer time to allow for unliking
              setTimeout(() => {
                tiktokLike.classList.remove('liked');
              }, 3000);
            }
            
            // Also update grid view buttons if they exist on the page
            updateGridViewButtons(media.filename, likes[media.filename]);
          });
        }
        
        // Grid View Functions
        
        // Update all like counts on page load
        function updateAllLikeCounts() {
          likeButtons.forEach(button => {
            const filename = button.getAttribute('data-filename');
            const likeCountElement = button.querySelector('.like-count');
            const count = likes[filename] || 0;
            if (likeCountElement) {
              likeCountElement.textContent = count;
            }
            
            // Update button state if liked
            if (count > 0) {
              button.classList.add('liked');
            }
          });
        }
        
        // Like media function for grid view
        function likeMediaGrid(filename, button) {
          console.log('Grid view - Liking media:', filename);
          
          // Send like to server
          const likeUrl = '/secret/tiktok-fake/like_handler.php';
          console.log('Grid view - Sending like request to:', likeUrl);
          fetch(likeUrl, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({ filename: filename })
          })
          .then(response => {
            console.log('Grid view - Like response status:', response.status);
            if (!response.ok) {
              throw new Error(`HTTP error! status: ${response.status} - ${response.statusText}`);
            }
            return response.json();
          })
          .then(data => {
            console.log('Grid view - Like response data:', data);
            if (data.success) {
              likes[filename] = data.like_count;
              
              // Update button UI
              const likeCountElement = button.querySelector('.like-count');
              if (likeCountElement) {
                likeCountElement.textContent = data.like_count;
              }
              button.classList.add('liked');
              
              // Add animation class
              button.classList.add('liked-animation');
              setTimeout(() => {
                button.classList.remove('liked-animation');
              }, 1000);
              
              // Also update TikTok mode if it's active and showing the same media
              if (tiktokLikeCount && tiktokContainer && tiktokContainer.style.display !== 'none') {
                const tiktokVideo = document.querySelector('#tiktokMediaContainer video');
                const tiktokImg = document.querySelector('#tiktokMediaContainer img');
                
                if ((tiktokVideo && tiktokVideo.src && tiktokVideo.src.includes(filename)) || 
                    (tiktokImg && tiktokImg.src && tiktokImg.src.includes(filename))) {
                  tiktokLikeCount.textContent = data.like_count;
                  // Also update the TikTok like button visual state
                  if (tiktokLike) {
                    tiktokLike.classList.add('liked');
                    // Add animation
                    tiktokLike.classList.remove('liked-animation');
                    void tiktokLike.offsetWidth;
                    tiktokLike.classList.add('liked-animation');
                    setTimeout(() => {
                      tiktokLike.classList.remove('liked-animation');
                    }, 1000);
                  }
                }
              }
            } else {
              console.error('Grid view - Error in like response:', data.error);
              // Show error to user
              alert('Failed to like media. Server error: ' + data.error + '. Please try again.');
            }
          })
          .catch(error => {
            console.error('Grid view - Error liking media:', error);
            // Show detailed error to user
            alert('Failed to like media. Error: ' + error.message + '. Please try again. Note: Your likes may not be saved if there is a database connection issue.');
            
            // Fallback: update UI locally
            const currentCount = likes[filename] || 0;
            likes[filename] = currentCount + 1;
            
            // Update button UI
            const likeCountElement = button.querySelector('.like-count');
            if (likeCountElement) {
              likeCountElement.textContent = likes[filename];
            }
            button.classList.add('liked');
            
            // Add animation class
            button.classList.add('liked-animation');
            setTimeout(() => {
              button.classList.remove('liked-animation');
            }, 1000);
            
            // Also update TikTok mode if it's active and showing the same media
            if (tiktokLikeCount && tiktokContainer && tiktokContainer.style.display !== 'none') {
              const tiktokVideo = document.querySelector('#tiktokMediaContainer video');
              const tiktokImg = document.querySelector('#tiktokMediaContainer img');
              
              if ((tiktokVideo && tiktokVideo.src && tiktokVideo.src.includes(filename)) || 
                  (tiktokImg && tiktokImg.src && tiktokImg.src.includes(filename))) {
                tiktokLikeCount.textContent = likes[filename];
                // Also update the TikTok like button visual state
                if (tiktokLike) {
                  tiktokLike.classList.add('liked');
                  // Add animation
                  tiktokLike.classList.remove('liked-animation');
                  void tiktokLike.offsetWidth;
                  tiktokLike.classList.add('liked-animation');
                  setTimeout(() => {
                    tiktokLike.classList.remove('liked-animation');
                  }, 1000);
                }
              }
            }
          });
        }
      }
      
      // Execute the initialization function when DOM is ready
      if (document.readyState === 'loading') {
        // DOM is still loading, wait for it to be ready
        document.addEventListener('DOMContentLoaded', initializeLikeFunctionality);
      } else {
        // DOM is already ready, execute immediately
        initializeLikeFunctionality();
      }
      
      // Test function to check if like handler is accessible
      function testLikeHandler() {
        console.log('Testing like handler accessibility...');
        fetch('/secret/tiktok-fake/like_handler.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ filename: 'test.mp4' })
        })
        .then(response => {
          console.log('Test response status:', response.status);
          return response.json();
        })
        .then(data => {
          console.log('Test response data:', data);
        })
        .catch(error => {
          console.error('Test error:', error);
        });
      }
      
      // Uncomment the line below to run the test
      // testLikeHandler();
      
      // Test function to check if get_likes is accessible
      function testGetLikes() {
        console.log('Testing get_likes accessibility...');
        fetch('/secret/tiktok-fake/get_likes.php')
        .then(response => {
          console.log('Get likes response status:', response.status);
          return response.json();
        })
        .then(data => {
          console.log('Get likes response data:', data);
        })
        .catch(error => {
          console.error('Get likes error:', error);
        });
      }
      
      // Uncomment the line below to run the test
      // testGetLikes();
    </script>
  </body>
</html>