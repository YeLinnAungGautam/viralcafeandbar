<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\Video;

trait MediaUploadingTrait
{
    public function storeMedia(Request $request)
    {
        // Validates file size
        if (request()->has('size')) {
            $this->validate(request(), [
                'file' => 'max:' . request()->input('size') * 1024,
            ]);
        }

        $path = storage_path('tmp/uploads');

        try {
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }
        } catch (\Exception $e) {
        }

        $file = $request->file('files');

        $name = uniqid() . '_' . trim($file->getClientOriginalName());

        $file->move($path, $name);

        return response()->json([
            'name'          => $name,
            'original_name' => $file->getClientOriginalName(),
        ]);
    }

    public function deleteMedia($slug, Request $request)
    {
        $media = Media::where('file_name', $slug);
        $video = Video::where('video_link', $slug);
        $videoThumbnail =  Video::where('image_link', $slug);
        if ($request->is_action) {
            if ($media) {
                $media->delete();
            }
            if ($video) {
                $video->delete();
            }
            if ($videoThumbnail) {
                $videoThumbnail->update([
                    'image_link' => null
                ]);
            }
        }

        Storage::disk('tmp')->delete('uploads/' . $slug);
    }
}
