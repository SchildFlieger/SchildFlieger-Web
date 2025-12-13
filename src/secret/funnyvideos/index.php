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
    <title>SchildFlieger | Funny Videos</title>
    <meta
      name="description"
      content="Funny videos gallery with friends."
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
      /* Blue Theme for Funny Videos */
      :root {
        --bg-dark: #000000; /* Pure black background */
        --bg-card: #1a1a2e; /* Dark blue cards */
        --primary: #4cc9f0; /* Bright blue accent */
        --primary-glow: rgba(76, 201, 240, 0.4); /* Blue glow */
        --accent: #ffffff; /* White text */
        --text-main: #e0e0ff; /* Light blue text */
        --text-muted: #a0a0c0; /* Muted blue text */
        --border-col: #33334d; /* Dark blue border */
      }
      
      body {
        background-color: var(--bg-dark);
        color: var(--text-main);
      }
      
      .card {
        background-color: var(--bg-card);
        border: 1px solid var(--border-col);
      }
      
      .btn-primary {
        background-color: var(--primary);
        color: #000;
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
        color: #000;
        box-shadow: 0 0 20px var(--primary-glow);
      }
      
      header {
        background-color: rgba(0, 0, 0, 0.95);
      }
      
      footer {
        background-color: #000000;
      }
      
      /* Video Gallery Styles */
      .video-gallery {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin: 20px 0;
      }
      
      .video-item {
        background-color: var(--bg-card);
        border: 1px solid var(--border-col);
        border-radius: var(--radius);
        overflow: hidden;
        transition: var(--transition);
      }
      
      .video-item:hover {
        transform: translateY(-5px);
        border-color: var(--primary);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
      }
      
      .video-container {
        position: relative;
        padding-top: 56.25%; /* 16:9 Aspect Ratio */
        overflow: hidden;
      }
      
      .video-container video {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
      
      .video-info {
        padding: 15px;
      }
      
      .video-info h3 {
        margin: 0 0 10px 0;
        color: var(--accent);
        font-size: 1.2rem;
      }
      
      .video-info p {
        margin: 0;
        color: var(--text-muted);
        font-size: 0.9rem;
      }
      
      /* TikTok Mode Styles */
      .tiktok-mode {
        display: flex;
        flex-direction: column;
        align-items: center;
        height: calc(100vh - var(--header-height));
        overflow: hidden;
      }
      
      .tiktok-video-container {
        width: 100%;
        max-width: 500px;
        height: 80%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
      }
      
      .tiktok-video-container video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: var(--radius);
      }
      
      .tiktok-video-info {
        width: 100%;
        max-width: 500px;
        padding: 15px;
        text-align: center;
      }
      
      .tiktok-video-info h3 {
        margin: 0 0 10px 0;
        color: var(--accent);
      }
      
      .tiktok-video-info p {
        margin: 0;
        color: var(--text-muted);
      }
      
      .navigation-buttons {
        display: flex;
        justify-content: space-between;
        width: 100%;
        max-width: 500px;
        padding: 0 15px;
      }
      
      .mode-toggle {
        text-align: center;
        margin: 20px 0;
      }
      
      .hidden {
        display: none;
      }
      
      /* Upload Form Styles */
      .upload-form {
        background-color: var(--bg-card);
        border: 1px solid var(--border-col);
        border-radius: var(--radius);
        padding: 20px;
        margin: 20px 0;
      }
      
      .upload-form h3 {
        color: var(--accent);
        margin-top: 0;
      }
      
      .form-group {
        margin-bottom: 15px;
      }
      
      .form-group label {
        display: block;
        margin-bottom: 5px;
        color: var(--text-main);
      }
      
      .form-group input, .form-group textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid var(--border-col);
        border-radius: var(--radius);
        background-color: #0a0a0a;
        color: var(--text-main);
      }
      
      .form-group textarea {
        min-height: 100px;
        resize: vertical;
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
        <h4>Funny Videos</h4>
        <h1>Enjoy,<br /><span class="highlight">With Friends</span></h1>
        <p class="hero-subtitle">
          A collection of funny moments captured with friends.
        </p>
        
        <!-- Upload Form -->
        <div class="upload-form">
          <h3>Upload New Video</h3>
          <form id="uploadForm">
            <div class="form-group">
              <label for="videoName">Your Name:</label>
              <input type="text" id="videoName" name="videoName" required>
            </div>
            <div class="form-group">
              <label for="videoFile">Select Video:</label>
              <input type="file" id="videoFile" name="videoFile" accept="video/*" required>
            </div>
            <div class="form-group">
              <label for="videoDescription">Description:</label>
              <textarea id="videoDescription" name="videoDescription" placeholder="Describe the funny moment..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-upload"></i> Upload Video
            </button>
          </form>
        </div>
        
        <div class="mode-toggle">
          <button id="toggleMode" class="btn btn-primary">
            <i class="fas fa-exchange-alt"></i> Switch to TikTok Mode
          </button>
        </div>
        
        <!-- Gallery Mode -->
        <div id="galleryMode">
          <div class="video-gallery">
            <!-- Video Item 1 -->
            <div class="video-item">
              <div class="video-container">
                <video controls>
                  <source src="/assets/videos/sample1.mp4" type="video/mp4">
                  Your browser does not support the video tag.
                </video>
              </div>
              <div class="video-info">
                <h3>Max Mustermann</h3>
                <p>Beach volleyball fail</p>
              </div>
            </div>
            
            <!-- Video Item 2 -->
            <div class="video-item">
              <div class="video-container">
                <video controls>
                  <source src="/assets/videos/sample2.mp4" type="video/mp4">
                  Your browser does not support the video tag.
                </video>
              </div>
              <div class="video-info">
                <h3>Erika Musterfrau</h3>
                <p>Camping in the rain</p>
              </div>
            </div>
            
            <!-- Video Item 3 -->
            <div class="video-item">
              <div class="video-container">
                <video controls>
                  <source src="/assets/videos/sample3.mp4" type="video/mp4">
                  Your browser does not support the video tag.
                </video>
              </div>
              <div class="video-info">
                <h3>John Doe</h3>
                <p>Birthday surprise gone wrong</p>
              </div>
            </div>
            
            <!-- Video Item 4 -->
            <div class="video-item">
              <div class="video-container">
                <video controls>
                  <source src="/assets/videos/sample4.mp4" type="video/mp4">
                  Your browser does not support the video tag.
                </video>
              </div>
              <div class="video-info">
                <h3>Jane Smith</h3>
                <p>Road trip navigation</p>
              </div>
            </div>
          </div>
        </div>
        
        <!-- TikTok Mode -->
        <div id="tiktokMode" class="tiktok-mode hidden">
          <div class="tiktok-video-container">
            <video id="tiktokVideo" controls>
              <source src="/assets/videos/sample1.mp4" type="video/mp4">
              Your browser does not support the video tag.
            </video>
          </div>
          <div class="tiktok-video-info">
            <h3 id="tiktokTitle">Max Mustermann</h3>
            <p id="tiktokDescription">Beach volleyball fail</p>
          </div>
          <div class="navigation-buttons">
            <button id="prevVideo" class="btn btn-accent">
              <i class="fas fa-arrow-left"></i> Previous
            </button>
            <button id="nextVideo" class="btn btn-accent">
              Next <i class="fas fa-arrow-right"></i>
            </button>
          </div>
        </div>
        
        <div class="hero-buttons">
          <a href="?logout=true" class="btn btn-accent">
            <i class="fas fa-sign-out-alt"></i> Logout
          </a>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer>
      <div class="container">
        <div class="footer-content">
          <a href="/" class="logo">Schild<span>Flieger</span></a>
          <div class="social-row">
            <a href="https://twitch.tv/schildflieger" class="social-btn">
              <i class="fab fa-twitch"></i>
            </a>
            <a href="https://www.youtube.com/@SchildFlieger" class="social-btn">
              <i class="fab fa-youtube"></i>
            </a>
            <a href="https://github.com/SchildFlieger" class="social-btn">
              <i class="fab fa-github"></i>
            </a>
          </div>
        </div>
        <div class="copyright">
          <a href="https://schildflieger.hmt-network.de/">SchildFlieger Website</a>
          © 2025 by
          <a href="https://schildflieger.hmt-network.de/">SchildFlieger</a> is
          licensed under
          <a href="https://creativecommons.org/licenses/by-nc-sa/4.0/">CC BY-NC-SA 4.0</a>
          <img
            src="https://mirrors.creativecommons.org/presskit/icons/cc.svg"
            alt=""
            style="max-width: 1em; max-height: 1em; margin-left: 0.2em"
          />
          <img
            src="https://mirrors.creativecommons.org/presskit/icons/by.svg"
            alt=""
            style="max-width: 1em; max-height: 1em; margin-left: 0.2em"
          />
          <img
            src="https://mirrors.creativecommons.org/presskit/icons/nc.svg"
            alt=""
            style="max-width: 1em; max-height: 1em; margin-left: 0.2em"
          />
          <img
            src="https://mirrors.creativecommons.org/presskit/icons/sa.svg"
            alt=""
            style="max-width: 1em; max-height: 1em; margin-left: 0.2em"
          />
        </div>
      </div>
    </footer>
    
    <script src="/assets/js/main.js"></script>
    <script>
      // Toggle between gallery and TikTok mode
      const toggleButton = document.getElementById('toggleMode');
      const galleryMode = document.getElementById('galleryMode');
      const tiktokMode = document.getElementById('tiktokMode');
      
      // TikTok mode navigation
      const tiktokVideo = document.getElementById('tiktokVideo');
      const tiktokTitle = document.getElementById('tiktokTitle');
      const tiktokDescription = document.getElementById('tiktokDescription');
      const prevButton = document.getElementById('prevVideo');
      const nextButton = document.getElementById('nextVideo');
      
      // Upload form
      const uploadForm = document.getElementById('uploadForm');
      
      // Video data
      const videos = [
        {
          src: '/assets/videos/sample1.mp4',
          title: 'Max Mustermann',
          description: 'Beach volleyball fail'
        },
        {
          src: '/assets/videos/sample2.mp4',
          title: 'Erika Musterfrau',
          description: 'Camping in the rain'
        },
        {
          src: '/assets/videos/sample3.mp4',
          title: 'John Doe',
          description: 'Birthday surprise gone wrong'
        },
        {
          src: '/assets/videos/sample4.mp4',
          title: 'Jane Smith',
          description: 'Road trip navigation'
        }
      ];
      
      let currentVideoIndex = 0;
      
      // Toggle view mode
      toggleButton.addEventListener('click', function() {
        if (galleryMode.classList.contains('hidden')) {
          // Switch to gallery mode
          galleryMode.classList.remove('hidden');
          tiktokMode.classList.add('hidden');
          toggleButton.innerHTML = '<i class="fas fa-exchange-alt"></i> Switch to TikTok Mode';
        } else {
          // Switch to TikTok mode
          galleryMode.classList.add('hidden');
          tiktokMode.classList.remove('hidden');
          toggleButton.innerHTML = '<i class="fas fa-th-large"></i> Switch to Gallery Mode';
          updateTiktokVideo();
        }
      });
      
      // Update TikTok video
      function updateTiktokVideo() {
        const video = videos[currentVideoIndex];
        tiktokVideo.src = video.src;
        tiktokTitle.textContent = video.title;
        tiktokDescription.textContent = video.description;
        tiktokVideo.load();
      }
      
      // Navigation buttons
      prevButton.addEventListener('click', function() {
        currentVideoIndex = (currentVideoIndex - 1 + videos.length) % videos.length;
        updateTiktokVideo();
      });
      
      nextButton.addEventListener('click', function() {
        currentVideoIndex = (currentVideoIndex + 1) % videos.length;
        updateTiktokVideo();
      });
      
      // Handle form submission (frontend only for demo)
      uploadForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const videoName = document.getElementById('videoName').value;
        const videoFile = document.getElementById('videoFile').files[0];
        const videoDescription = document.getElementById('videoDescription').value;
        
        if (videoFile) {
          // In a real implementation, you would send this to a server
          alert(`Video uploaded successfully!\nName: ${videoName}\nDescription: ${videoDescription}\nFile: ${videoFile.name}`);
          
          // Reset form
          uploadForm.reset();
        } else {
          alert('Please select a video file to upload.');
        }
      });
    </script>
  </body>
</html>