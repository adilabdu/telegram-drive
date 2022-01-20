<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\Folder;
use Illuminate\Database\Eloquent\Factories\Factory;

class FileFactory extends Factory
{

    public function definition(): array
    {
        $folders = Folder::all()->map->only('id')->flatten()->toArray();
        $folder_id = $folders[array_rand($folders)];

        $type = array_rand([0, 1, 2]);
        $file_type = [File::PHOTO, File::TEXT, File::VIDEO][$type];
        $extension = [
            ".jpg",
            [".docx", ".pdf", ".xlsx"][array_rand([0,1,2])],
            [".mp4", ".mkv", ".mpeg"][array_rand([0,1,2])]
        ][$type];

        return [
            "folder_id" => $folder_id,
            "name" => $this->faker->word() . $extension,
            "type" => $file_type,
            "size" => $this->faker->numberBetween(20000, 5000000)
        ];
    }
}
