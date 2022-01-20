<?php

namespace Database\Factories;

use App\Models\Folder;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

class FolderFactory extends Factory
{

    private function folder($user_id) {

        $folders = User::find($user_id)->folders->map->only('id')->flatten()->toArray();

        if(sizeof($folders) == 0) {
            return Folder::find(0);
        }

        $id = $folders[array_rand($folders)];
        return Folder::find($id);
    }

    public function definition()
    {
        $users = User::all()->map->only('id')->flatten()->toArray();
        $id = $users[array_rand($users)];
        $user = User::find($id);

        return [
            "user_id" => $user,
            "parent_id" => $this->folder($id),
            "name" => $this->faker->unique()->word()
        ];
    }
}
