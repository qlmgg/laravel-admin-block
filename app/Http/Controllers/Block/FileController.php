<?php

namespace App\Http\Controllers\Block;


use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use TheSeer\Tokenizer\Exception;


class FileController extends BaseController
{

    //
    public function upload(Request $request)
    {
        try {
            $name = $request->get("name");
            $info = $this->StorageAdminUpload($name);
            return $this->success("上传成功", "", $info);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    private function StorageAdminUpload($namepath)
    {
        $file = \Illuminate\Support\Facades\Request::file("file");
        $folder = $namepath . "/" . date('Ymd');
        if (!Storage::disk('admin')->exists($folder)) {
            Storage::makeDirectory($folder);
        }
        $servicepath = "/upload/" . $folder;
        if($file){
            if ($file->isValid()) {
                $filename = md5(microtime()) . '.' . $file->getClientOriginalExtension();
                Storage::disk("admin")->put($folder . '/' . $filename, file_get_contents($file->getRealPath()));

                $servicefileinfo = [
                    'name' => $namepath,
                    'type' => $file->getClientMimeType(),
                    'originalname' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'serverpath' => $servicepath,
                    'servername' => $filename
                ];
                File::insert($servicefileinfo);
                $servicefileinfo['pathname'] = $folder . '/' . $filename;
                $servicefileinfo['url'] = $this->blockService->FullImage($folder . '/' . $filename);
                return $servicefileinfo;
            } else {
                throw  new  Exception("上传失败");
            }
        }else{
            throw  new  Exception("选择文件");
        }
    }
}
