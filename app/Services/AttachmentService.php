<?php
declare(strict_types = 1);

namespace ModulePassport\Services;

use Swoolecan\Foundation\Helpers\CommonTool;

class AttachmentService extends AbstractService
{
    public function createPaths($system, $path)
    {
        $driver = $this->getFileDriver($system);
        $files = $driver->listContents($path);
        foreach ($files as $file) {
            if ($file['type'] == 'dir') {
                $this->_createPath($file['basename'], $this->formatPath($path));
                $this->createPaths($system, $file['path']);
            }
        }

        return $files;
    }

    public function createFiles($system)
    {
        $driver = $this->getFileDriver($system);
        $attachmentPath = $this->getRepositoryObj('attachmentPath');
        $paths = $attachmentPath->findWhere(['status' => 0]);
        foreach ($paths as $path) {
            $files = $driver->listContents($path['path_full']);
            foreach ($files as $file) {
                if ($file['type'] != 'file') {
                    continue;
                }
                //$info = $driver->getMetadata($file['path']);
                $file['mime_type'] = $driver->getMimetype($file['path']);
                $this->_createFile($file);
            }
            $path->update(['status' => 1]);
        }
        return true;
    }

    public function createPathByRecord()
    {
        $repository = $this->getRepositoryObj('attachment');
        return ['ok'];
    }

    public function _createFile($file)
    {
        $attachment = $this->getRepositoryObj('attachment');
        $exist = $attachment->findWhere(['filepath' => $file['path']])->first();
        if ($exist && !empty($exist->path_id)) {
            return true;
        }
        $attachmentPath = $this->getRepositoryObj('attachmentPath');
        $path = $file['dirname'];
        $pathId = 0;
        if ($path) {
            $pathInfo = $attachmentPath->findWhere(['path_full' => $file['dirname']])->first();
            $pathId = $pathInfo['id'];
        }
        if ($exist) {
            $exist->update(['path_id' => $pathId]);
            return ;
        }
        
        $data = [
            'filepath' => $file['path'],
            'name' => $file['filename'],
            'path_id' => $pathId,
            'filename' => $file['basename'],
            'extension' => strval($file['extension']),
            'size' => $file['size'],
            'mime_type' => $file['mime_type'],
        ];
        $attachment->create($data);
        return true;
    }

    protected function _createPath($path, $parentPath)
    {
        $model = $this->getModelObj('attachmentPath');
        $parentPath = $this->formatPath($parentPath);
        $parentInfo = $model->where('path_full', $parentPath)->first();
        if (empty($parentInfo) && !empty($parentPath) && $parentPath != '.') {
            $basePath = basename($parentPath);
            $dir = dirname($parentPath);
            $r = $this->_createPath($basePath, $dir);
            $parentInfo = $model->where('path_full', $parentPath)->first();
        }
        $pathFull = $this->formatPath($parentPath) . '/' . $this->formatPath($path);
        $pathFull = $this->formatPath($pathFull);
        $exist = $model->where('path_full', $pathFull)->first();
        if (empty($exist)) {
            $data = [
                'path' => $path,
                'parent_id' => intval($parentInfo['id']),
                'path_full' => $pathFull,
            ];
            $model->create($data);
        }
        return true;
    }

    protected function formatPath($path)
    {
        $path = trim($path, '/');
        $path = ltrim($path, './');
        return $path;
    }

    public function mkdirLocal($system, $path, $config = [])
    {
        $driver = $this->getFileDriver($system);
        $r = $driver->createDir($path, $config);
        return true;
    }

    public function saveFile($system, $path, $file)
    {
        $extension = $file->getClientOriginalExtension();
        $data = [
            'size' => $file->getSize(),
            'name' => str_replace(".{$extension}", '', $file->getClientOriginalName()),
            'filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'system' => $system,
            'extension' => $extension,
        ];
        $driver = $this->getFileDriver($system);
        $driver->createDir($path);
        $newPath = $driver->getAdapter()->applyPathPrefix($path);
        //echo $newPath;
        $fileName = CommonTool::generateUniqueString(16) . ".{$extension}";
        //$file->moveTo($newPath . "/{$fileName}");
        $file->storeAs($path, $fileName, $system);
        $r = $driver->setVisibility($path . "/{$fileName}", 'public');
        $data['filepath'] = ltrim($path . "/{$fileName}", '/');
        return $data;
    }

    public function deleteFiles($system, $files)
    {
        $driver = $this->getFileDriver($system);
        $driver->delete((array) $files);
        return true;
    }

    protected function getFileDriver($system)
    {
        return \Storage::disk($system);
    }
}
