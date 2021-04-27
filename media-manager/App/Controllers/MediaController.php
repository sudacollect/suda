<?php

namespace Gtd\MediaManager\App\Controllers;

use App\Http\Controllers\Controller;
use Jhofm\FlysystemIterator\Plugin\IteratorPlugin;
use Gtd\MediaManager\App\Controllers\Modules\Lock;
use Gtd\MediaManager\App\Controllers\Modules\Move;
use Gtd\MediaManager\App\Controllers\Modules\Utils;
use Gtd\MediaManager\App\Controllers\Modules\Delete;
use Gtd\MediaManager\App\Controllers\Modules\Rename;
use Gtd\MediaManager\App\Controllers\Modules\Upload;
use Gtd\MediaManager\App\Controllers\Modules\Download;
use Gtd\MediaManager\App\Controllers\Modules\NewFolder;
use Gtd\MediaManager\App\Controllers\Modules\GetContent;
use Gtd\MediaManager\App\Controllers\Modules\Visibility;
use Gtd\MediaManager\App\Controllers\Modules\GlobalSearch;

use Gtd\Suda\Http\Controllers\Admin\DashboardController;

class MediaController extends DashboardController
{
    use Utils,
        GetContent,
        Delete,
        Download,
        Lock,
        Move,
        Rename,
        Upload,
        NewFolder,
        Visibility,
        GlobalSearch;

    protected $baseUrl;
    protected $db;
    protected $fileChars;
    protected $fileSystem;
    protected $folderChars;
    protected $ignoreFiles;
    protected $LMF;
    protected $GFI;
    protected $sanitizedText;
    protected $storageDisk;
    protected $storageDiskInfo;
    protected $unallowedMimes;

    public function __construct()
    {
        parent::__construct();
        $config = app('config')->get('mediaManager');

        $this->fileSystem           = $config['storage_disk'];
        $this->ignoreFiles          = $config['ignore_files'];
        $this->fileChars            = $config['allowed_fileNames_chars'];
        $this->folderChars          = $config['allowed_folderNames_chars'];
        $this->sanitizedText        = $config['sanitized_text'];
        $this->unallowedMimes       = $config['unallowed_mimes'];
        $this->LMF                  = $config['last_modified_format'];
        $this->GFI                  = $config['get_folder_info']   ?? true;
        $this->paginationAmount     = $config['pagination_amount'] ?? 50;

        $this->storageDisk     = app('filesystem')->disk($this->fileSystem);
        $this->storageDiskInfo = app('config')->get("filesystems.disks.{$this->fileSystem}");
        $this->baseUrl         = $this->storageDisk->url('/');
        $this->db              = app('db')
                                    ->connection($config['database_connection'])
                                    ->table($config['table_locked']);

        $this->storageDisk->addPlugin(new IteratorPlugin());
    }

    /**
     * main view.
     *
     * @return [type] [description]
     */
    public function index()
    {
        return view('MediaManager::media');
    }
}
