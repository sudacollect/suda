<?php

namespace Gtd\MediaManager\App\Controllers\Modules;

use Illuminate\Http\Request;
use Gtd\MediaManager\App\Events\MediaFileOpsNotifications;
use Gtd\Suda\Models\Media;
use Gtd\Suda\Models\Mediatable;
use Gtd\Suda\Http\Controllers\Media\MediaController;

trait Delete
{
    /**
     * delete files/folders.
     *
     * @param Request $request [description]
     *
     * @return [type] [description]
     */
    public function deleteItem(Request $request)
    {
        $path        = $request->path;
        $result      = [];
        $toBroadCast = [];

        foreach ($request->deleted_files as $one) {
            $name      = $one['name'];
            $type      = $one['type'];
            $item_path = $one['storage_path'];
            $defaults  = [
                'name' => $name,
                'path' => $item_path,
            ];
            
            $del = $type == 'folder'
                    ? $this->storageDisk->deleteDirectory($item_path)
                    : $this->storageDisk->delete($item_path);

            if ($del) {
                $result[]      = array_merge($defaults, ['success' => true]);
                $toBroadCast[] = $defaults;

                //删除信息
                $media = Media::where(['path'=>'public/'.$item_path])->first();
                if($media)
                {
                    (new MediaController)->doRemove($media->id);
                }
                

                // fire event
                event('MMFileDeleted', [
                    'file_path' => $item_path,
                    'is_folder' => $type == 'folder',
                ]);
            } else {
                $result[] = array_merge($defaults, [
                    'success' => false,
                    'message' => trans('MediaManager::messages.error.deleting_file'),
                ]);
            }
        }

        // broadcast
        broadcast(new MediaFileOpsNotifications([
            'op'    => 'delete',
            'items' => $toBroadCast,
            'path'  => $path,
        ]))->toOthers();

        return response()->json($result);
    }
}
