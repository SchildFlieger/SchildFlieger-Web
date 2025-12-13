# Video Storage Layout

This document describes the layout for storing videos in the SchildFlieger website.

## Directory Structure

```
src/
├── assets/
│   ├── videos/
│   │   ├── sample1.mp4
│   │   ├── sample2.mp4
│   │   ├── sample3.mp4
│   │   └── sample4.mp4
│   └── ...
└── ...

secret/
├── funnyvideos/
│   └── index.php
└── ...
```

## Video Storage Location

All videos for the funny videos section are stored in:
`src/assets/videos/`

## Supported Video Formats

The website supports the following video formats:

- MP4 (.mp4)
- WebM (.webm)
- Ogg (.ogg)

## Naming Convention

Videos should be named descriptively:

- Use lowercase letters and numbers
- Separate words with underscores
- Example: `beach_volleyball_fail.mp4`

## Adding New Videos

To add new videos to the gallery:

1. Upload the video file to `src/assets/videos/`
2. Update the video list in `src/secret/funnyvideos/index.php`:
   - Add a new entry in the `videos` JavaScript array
   - Add a new video item in the gallery mode section

## Video Metadata

Each video should have the following metadata:

- Title/Name: The name of the person in the video
- Description: A brief description of the funny moment
- File: The actual video file

## Example Entry

```html
<!-- Gallery Mode -->
<div class="video-item">
  <div class="video-container">
    <video controls>
      <source src="/assets/videos/your_video.mp4" type="video/mp4" />
      Your browser does not support the video tag.
    </video>
  </div>
  <div class="video-info">
    <h3>Person Name</h3>
    <p>Description of the funny moment</p>
  </div>
</div>

<!-- JavaScript Array -->
const videos = [ // ... existing videos { src: '/assets/videos/your_video.mp4',
title: 'Person Name', description: 'Description of the funny moment' } ];
```

## File Size Recommendations

- Keep videos under 50MB for optimal loading times
- Use appropriate compression to balance quality and file size
- Recommended resolution: 1080p or 720p

## Access Control

Videos in the `assets/videos/` directory are publicly accessible. For truly private videos, additional server-side protection would be needed.
