<?php

namespace app\utils\controller;

use think\Controller;
use think\Session;
use think\Request;
use app\common\model\Picture;
use think\Image;

/**
 * 通用上传接口
 * Class Upload
 * @package app\api\controller
 */
class Upload extends Controller {

    protected function _initialize() {
        parent::_initialize();
        if (!Session::has('admin_id')) {
            $result = [
                'error' => 1,
                'message' => '未登录'
            ];

            return json($result);
        }
    }

    /**
     * 通用图片上传接口
     * @return \think\response\Json
     */
    public function uploadPicture() {
        $img_width = Request::instance()->param('img_width');
        $img_height = Request::instance()->param('img_height');
        $img_id = Request::instance()->param('img_id');
        $name = Request::instance()->param('name');
        $config = [
            'size' => 2097152,
            'ext' => 'jpg,gif,png,bmp,jpeg'
        ];
        $file = $this->request->file($name);
        if (!$file->validate($config)) {
            return json([
                'error' => 1,
                'message' => $file->getError()
            ]);
        }
        $pictrue = new Picture();
        $filearray = $_FILES[$name];
        $img = $pictrue->checkImg($filearray);
        if (!empty($img)) {
            $result=[
                'error' => 0,
                'cover_id' => $img['id'],
                'url' => $img['path']
            ];
             if ($img_id != "undefined") {
                    $result['img_id'] = $img_id;
                }
            return json($result);
        }
        $image = Image::open($file);
        if ($img_width == "undefined" || $img_height == "undefined") {
            $filearray['width'] = $image->width();
            $filearray['height'] = $image->height();
        } else {
            $filearray['width'] = $img_width;
            $filearray['height'] = $img_height;
        }
        $upload_path = str_replace('\\', '/', ROOT_PATH . 'public/uploads/picture');
        $save_path = '/uploads/picture/';
        $info = $file->rule('date')->move($upload_path);
        if ($info) {
            $path = str_replace('\\', '/', $save_path . $info->getSaveName());
            $img = $pictrue->initImg($path, '', $filearray);
            if ($img) {
                $path = $img['path'];
                //按照尺寸修改图片
                $image->thumb($img['width'], $img['height'], Image::THUMB_FILLED)->save(str_replace('\\', '/', ROOT_PATH . 'public') . $path);
                $result = [
                    'error' => 0,
                    'cover_id' => $img['id'],
                    'url' => $path
                ];
                if ($img_id != "undefined") {
                    $result['img_id'] = $img_id;
                }
            } else {
                $result = [
                    'error' => 1,
                    'message' => $pictrue->getError()
                ];
            }
        } else {
            $result = [
                'error' => 1,
                'message' => $file->getError()
            ];
        }

        return json($result);
    }

}
