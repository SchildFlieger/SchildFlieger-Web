<?php
session_start();

// Simple function to parse .env file
function loadEnv($path) {
    if (!file_exists($path)) {
        return [];
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $env = [];
    
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parse key=value pairs
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $env[trim($key)] = trim($value);
        }
    }
    
    return $env;
}

// Load .env file
$env = loadEnv(__DIR__ . '/.env');

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enteredPassword = $_POST['password'] ?? '';
    $correctPassword = $env['ACCESS_PASSWORD'] ?? '';
    $livePassword = $env['LIVE_PASSWORD'] ?? '';
    $tomPassword = $env['TOM_PASSWORD'] ?? '';

    if ($enteredPassword === $correctPassword) {
        // Set session variable to indicate successful login
        $_SESSION['logged_in'] = true;
        // Force session write to ensure it's saved before redirect
        session_write_close();
        
        // For standard password, redirect to go area or stored destination
        if (isset($_SESSION['redirect_after_login'])) {
            $redirect_url = $_SESSION['redirect_after_login'];
            unset($_SESSION['redirect_after_login']);
        } else {
            $redirect_url = '/secret/index.php';
        }
        
        header('Location: ' . $redirect_url);
        exit();
    } elseif ($enteredPassword === $livePassword) {
        // Set session variable to indicate successful login
        $_SESSION['logged_in'] = true;
        // Force session write to ensure it's saved before redirect
        session_write_close();
        
        // For live password, always redirect to live area regardless of stored destination
        $redirect_url = '/secret/live/index.php';
        unset($_SESSION['redirect_after_login']);
        
        header('Location: ' . $redirect_url);
        exit();
    } elseif ($enteredPassword === $tomPassword) {
        // Set session variable to indicate successful login
        $_SESSION['logged_in'] = true;
        // Force session write to ensure it's saved before redirect
        session_write_close();
        
        // For Tom's password, always redirect to Tom's area
        $redirect_url = '/secret/Tom/index.php';
        unset($_SESSION['redirect_after_login']);
        
        header('Location: ' . $redirect_url);
        exit();
    } else {
        $error = 'Invalid password. Please try again.';
    }
}
?>
<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SchildFlieger | Secure Login</title>
    <meta
      name="description"
      content="Secure login area for SchildFlieger."
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
      .login-container {
        max-width: 400px;
        margin: 100px auto;
        padding: 30px;
        background-color: var(--bg-card);
        border: 1px solid var(--border-col);
        border-radius: var(--radius);
      }
      
      .form-group {
        margin-bottom: 20px;
      }
      
      label {
        display: block;
        margin-bottom: 8px;
        color: var(--text-main);
        font-family: "Oswald", sans-serif;
        text-transform: uppercase;
        letter-spacing: 1px;
      }
      
      input[type="password"] {
        width: 100%;
        padding: 12px;
        border: 1px solid var(--border-col);
        border-radius: var(--radius);
        background-color: #0a0a0a;
        color: var(--text-main);
        font-family: "Inter", sans-serif;
      }
      
      input[type="password"]:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 10px var(--primary-glow);
      }
      
      .error {
        color: #ff6b6b;
        margin-bottom: 20px;
        padding: 10px;
        border: 1px solid #ff6b6b;
        border-radius: var(--radius);
        background-color: rgba(255, 107, 107, 0.1);
      }
      
      .login-header {
        text-align: center;
        margin-bottom: 30px;
      }
      
      .login-header h2 {
        font-size: 2rem;
        margin-bottom: 10px;
      }
      
      .login-header p {
        color: var(--text-muted);
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
      <!-- Fullscreen Background Video -->
      <video autoplay muted loop playsinline class="background-video">
        <source src="/assets/video/bedwarsvod.mp4" type="video/mp4" />
        Your browser does not support the video tag.
      </video>
      <div class="container hero-content reveal">
        <div class="login-container">
          <div class="login-header">
            <h2>Secure Login</h2>
            <p>Enter your credentials to access the protected area</p>
          </div>
          
          <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
          <?php endif; ?>
          
          <form method="POST" action="">
            <div class="form-group">
              <label for="password">Password:</label>
              <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">
              <i class="fas fa-sign-in-alt"></i> Login
            </button>
          </form>
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