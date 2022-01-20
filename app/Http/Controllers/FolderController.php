<?php

namespace App\Http\Controllers;

use App\Http\Resources\Resource;
use App\Models\Folder;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FolderController extends Controller
{

    public function index(User $user): ResourceCollection
    {
        return new ResourceCollection(Folder::where(['user_id' => $user['id']])->get()->sortBy('parent_id'));
    }

    public function children(Folder $folder): ResourceCollection
    {
        $user_id = request()->only('user_id');

        if($folder['name'] === '$root') {
            return new ResourceCollection(
                $folder
                ->children()
                ->owner($user_id)
                ->get()
            );
        }

        return new ResourceCollection($folder->children()->get());
    }

    public function parent(Folder $folder): Resource
    {
        return new Resource($folder->parent()->get());
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'user_id' => 'required|numeric',
            'parent_id' => 'required|numeric'
        ]);

        $exists = Folder::where([
            'name' => $request->input('name'),
            'parent_id' => $request->input('parent_id')
        ])->get();

        if($exists->count()) {
            return response([
                'message' => 'duplicate',
            ], 419);
        }

        try {
            $folder = Folder::create([
                'name' => $request->input('name'),
                'parent_id' => $request->input('parent_id'),
                'user_id' => $request->input('user_id'),
            ]);

            return response([
                'message' => 'success',
                'folder' => $folder
            ], 200);

        } catch(Exception $e) {

            return response([
                'message' => $e,
            ], 500);
        }
    }

    public function update(Folder $folder)
    {
        $updates = request()->only(['name', 'parent_id']);

        try {

            // Check if new parent folder exists
            if($updates['parent_id'] && !Folder::where([
                    'id' => $updates['parent_id']
                ])->get()->count()) {
                return response([
                    'message' => 'folder_does_not_exist'
                ], 404);
            }

            // Check if new name is a duplicate in current directory
            if($updates['name'] && Folder::where([
                    'parent_id' => !! $updates['parent_id'] ?
                        $updates['parent_id'] :
                        $folder['parent_id'],
                    'name' => $updates['name']
                ])->get()->count()) {
                return response([
                    'message' => 'duplicate'
                ], 419);
            }

            // Attempt update and proceed
            $updated = $folder->update($updates);
            if($updated) {
                return response([
                    'message' => 'success',
                    'folder' => $folder,
                ]);
            }

            return response([
                'message' => 'update_error'
            ], 500);

        } catch(Exception $e) {

            return response([
                'message' => $e
            ], 500);
        }
    }

    public function destroy(Folder $folder)
    {
        try {

            $delete = $folder->delete();
            if($delete) {
                return response([
                    'message' => 'success',
                ], 200);
            }

            return response([
                'message' => 'delete_error',
            ], 500);

        } catch(Exception $e) {

            return response([
                'message' => $e
            ], 500);
        }
    }
}
