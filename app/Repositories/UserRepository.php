<?php

namespace App\Repositories;

use App\Helpers\ArrayHelper;
use App\Models\File;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserRepository
{
    private function createProfileImage(UploadedFile $file, User $user): void
    {
        $filePath = Storage::disk('private')->putFile('user_profile', $file);

        $user->files()->create([
            'extension' => $file->extension(),
            'fileable_type' => User::class,
            'fileable_id' => $user->id,
            'mime_type' => $file->getMimeType(),
            'pages' => null,
            'path' => $filePath,
            'size' => $file->getSize(),
            'visibility' => 'private',
        ]);
    }

    private function manageProfileImage(UploadedFile $file, User $user, bool $remove = false): void
    {
        $oldFile = $user->files()->first();

        if ($remove) {
            $this->removeProfileImage($oldFile);
        } elseif ($user->files->count()) {
            $this->updateProfileImage($file, $oldFile);
        } else {
            $this->createProfileImage($file, $user);
        }
    }

    private function removeProfileImage(?File $oldFile): void
    {
        Storage::disk('private')->delete($oldFile->path);

        $oldFile->delete();
    }

    private function updateProfileImage(UploadedFile $file, ?File $oldFile): void
    {
        $filePath = Storage::disk('private')->putFile('user_profile', $file);
        Storage::disk('private')->delete($oldFile->path);

        $oldFile->update([
            'extension' => $file->extension(),
            'mime_type' => $file->getMimeType(),
            'path' => $filePath,
            'size' => $file->getSize(),
        ]);
    }

    public function update(array $data, User $user): bool
    {
        $data = ArrayHelper::removeEmptyStrings($data);
        unset($data['password']);

        $this->manageProfileImage($data['files'][0], $user, $data['remove_profile_image']);

        return $user->update([
            'name' => $data['name'] ?? $user->name,
            'email' => $data['email'] ?? $user->email,
        ]);
    }
}
