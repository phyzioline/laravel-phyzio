<?php

namespace App\Services\Feed;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class VideoProcessingService
{
    /**
     * Maximum video file size in MB
     */
    const MAX_VIDEO_SIZE = 100;

    /**
     * Allowed video formats
     */
    const ALLOWED_FORMATS = ['mp4', 'webm', 'mov', 'avi'];

    /**
     * Process and store uploaded video
     *
     * @param UploadedFile $video
     * @return array ['video_url' => string, 'video_thumbnail' => string, 'video_duration' => int]
     * @throws \Exception
     */
    public function processVideo(UploadedFile $video)
    {
        // Validate video
        $this->validateVideo($video);

        // Store video
        $videoPath = $video->store('videos', 'public');

        // Generate thumbnail (placeholder - requires FFmpeg for real implementation)
        $thumbnailPath = $this->generateThumbnail($videoPath);

        // Get duration (placeholder - requires getID3 or FFmpeg)
        $duration = $this->getVideoDuration($videoPath);

        return [
            'video_url' => $videoPath,
            'video_thumbnail' => $thumbnailPath,
            'video_duration' => $duration,
            'video_provider' => 'upload'
        ];
    }

    /**
     * Validate uploaded video
     *
     * @param UploadedFile $video
     * @throws \Exception
     */
    protected function validateVideo(UploadedFile $video)
    {
        // Check file size
        if ($video->getSize() > self::MAX_VIDEO_SIZE * 1024 * 1024) {
            throw new \Exception("Video file size must be less than " . self::MAX_VIDEO_SIZE . "MB");
        }

        // Check format
        $extension = strtolower($video->getClientOriginalExtension());
        if (!in_array($extension, self::ALLOWED_FORMATS)) {
            throw new \Exception("Video format must be one of: " . implode(', ', self::ALLOWED_FORMATS));
        }
    }

    /**
     * Generate video thumbnail
     * 
     * Note: This is a placeholder. For production, use FFmpeg:
     * ffmpeg -i video.mp4 -ss 00:00:01.000 -vframes 1 thumbnail.jpg
     *
     * @param string $videoPath
     * @return string
     */
    protected function generateThumbnail($videoPath)
    {
        // For now, create a placeholder thumbnail
        // In production, use FFmpeg or a cloud service like Cloudinary
        
        $thumbnailName = 'thumbnails/' . pathinfo($videoPath, PATHINFO_FILENAME) . '.jpg';
        
        // Create a simple placeholder image
        $placeholderPath = public_path('web/assets/images/video-placeholder.jpg');
        if (file_exists($placeholderPath)) {
            Storage::disk('public')->put($thumbnailName, file_get_contents($placeholderPath));
        } else {
            // Create a basic colored rectangle as placeholder
            $img = Image::canvas(640, 360, '#02767F');
            $img->text('▶️', 320, 180, function($font) {
                $font->size(100);
                $font->align('center');
                $font->valign('middle');
                $font->color('#ffffff');
            });
            Storage::disk('public')->put($thumbnailName, $img->encode('jpg', 80));
        }

        return $thumbnailName;
    }

    /**
     * Get video duration in seconds
     * 
     * Note: This is a placeholder. For production, use getID3 library or FFmpeg:
     * ffprobe -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 video.mp4
     *
     * @param string $videoPath
     * @return int
     */
    protected function getVideoDuration($videoPath)
    {
        // Placeholder - return 0
        // In production, use getID3 or FFmpeg to get actual duration
        return 0;
    }

    /**
     * Process YouTube or Vimeo URL
     *
     * @param string $url
     * @return array
     */
    public function processExternalVideo($url)
    {
        if (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $url, $matches) ||
            preg_match('/youtu\.be\/([^\&\?\/]+)/', $url, $matches)) {
            // YouTube video
            $videoId = $matches[1];
            return [
                'video_url' => "https://www.youtube.com/embed/{$videoId}",
                'video_thumbnail' => "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg",
                'video_duration' => 0,
                'video_provider' => 'youtube'
            ];
        }

        if (preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
            // Vimeo video
            $videoId = $matches[1];
            return [
                'video_url' => "https://player.vimeo.com/video/{$videoId}",
                'video_thumbnail' => null, // Vimeo API needed for thumbnail
                'video_duration' => 0,
                'video_provider' => 'vimeo'
            ];
        }

        throw new \Exception('Invalid video URL. Supported: YouTube, Vimeo');
    }

    /**
     * Delete video files
     *
     * @param string $videoPath
     * @param string $thumbnailPath
     */
    public function deleteVideo($videoPath, $thumbnailPath = null)
    {
        if ($videoPath && Storage::disk('public')->exists($videoPath)) {
            Storage::disk('public')->delete($videoPath);
        }

        if ($thumbnailPath && Storage::disk('public')->exists($thumbnailPath)) {
            Storage::disk('public')->delete($thumbnailPath);
        }
    }
}
