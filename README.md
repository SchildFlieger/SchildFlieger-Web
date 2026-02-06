# SchildFlieger Website

Overview of the official portfolio and creator website of SchildFlieger. The site combines content creation, a development portfolio, and community links in a static web presence, extended by a protected PHP area.

## Contents

- Content Creation: Twitch, YouTube main channel, and Uncut/VODs
- Development: GitHub profile, projects, and portfolio
- Community & Clan: HMT Clan and partner links
- Fullscreen Video: separate player for the hero video
- Protected area: PHP scripts under `src/secret/`

## Project Structure

- `src/index.html`: main page
- `src/video-fullscreen.html`: fullscreen video player
- `src/assets/`: CSS, JS, images, video
- `src/secret/`: PHP area including subpages and tools
- `nginx.conf`: example configuration for running with Nginx
- `composer.json`: PHP dependencies (e.g., `vlucas/phpdotenv`)

## Local Preview

Static pages can be opened directly in a browser. PHP content requires a local web server.

```powershell
# Optional: PHP server for the /src folder
php -S localhost:8000 -t src
```

## License

The content of this website is licensed under Creative Commons CC BY-NC-SA 4.0.

- License text: `https://creativecommons.org/licenses/by-nc-sa/4.0/`
- Copyright: SchildFlieger

## Links

- Website: `https://schildflieger.hmt-network.de/`
- GitHub: `https://github.com/SchildFlieger`
- Twitch: `https://twitch.tv/schildflieger`
- YouTube: `https://www.youtube.com/@SchildFlieger`
