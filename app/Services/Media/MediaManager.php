<?php

namespace App\Services\Media;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Optional;
use Spatie\MediaLibrary\HasMedia;

class MediaManager
{
    /**
     * An upload wins over a delete, because a form replacing the file sends both.
     *
     * @param UploadedFile|array<UploadedFile>|Optional|null $files
     * @param bool|array<int>|Optional|null $delete `true` clears the collection, a list of media ids removes only those.
     */
    public function sync(HasMedia $model, string $collection, mixed $files, mixed $delete = null): void
    {
        $uploads = $this->uploads($files);

        if ($uploads !== []) {
            $this->add($model, $collection, $uploads);

            return;
        }

        $this->remove($model, $collection, $delete);
    }

    /**
     * @param array<UploadedFile> $uploads
     */
    public function add(HasMedia $model, string $collection, array $uploads): void
    {
        foreach ($uploads as $upload) {
            $model->addMedia($upload)->preservingOriginal()->toMediaCollection($collection);
        }
    }

    public function remove(HasMedia $model, string $collection, mixed $delete): void
    {
        if ($delete instanceof Optional || !$delete) {
            return;
        }

        if (is_array($delete)) {
            $model->getMedia($collection)
                ->whereIn('id', $delete)
                ->each(fn($media) => $media->delete());

            return;
        }

        $model->clearMediaCollection($collection);
    }

    /**
     * @return array<UploadedFile>
     */
    private function uploads(mixed $files): array
    {
        if ($files instanceof UploadedFile) {
            return [$files];
        }

        return is_array($files)
            ? array_values(array_filter($files, fn($file) => $file instanceof UploadedFile))
            : [];
    }
}
