<?php
namespace app\utils\controller;

use think\Controller;
use org\UeditorUpload;


/**
 * Ueditor编辑器统一上传接口
 * Class Ueditor
 * @package app\api\controller
 */
class Ueditor extends Controller
{
    protected $config;
    private $auto_config;
    protected $action;
    private $extList;//用户定义的可传文件,不指定为默认的配置值
    private $size;//用户指定的文件大小,不指定为默认的配置值
    private $time;//最大时间长度
    protected function _initialize()
    {
        parent::_initialize();

        $this->config = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents(ROOT_PATH . 'public/static/js/ueditor/config.json')), true);
        $this->auto_config =json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents(ROOT_PATH . 'public/html/uconfig.json')), true);
        $this->action = $this->request->get('action');
        $this->extList = $this->request->get('ext/a');
        $this->extList == "undefined"&&  $this->extList = [];
        $this->size = $this->request->get('maxSize');
        $this->size == "undefined" && $this->size = 0;
         $this->time = $this->request->get('time');
        $this->time == "undefined" && $this->time = 0;
    }

    /**
     * Ueditor编辑器统一上传接口
     * @return string|\think\response\Json
     */
    public function index()
    {
        switch ($this->action) {
            case 'config':
                $result = $this->config;
                break;

            /* 上传图片 */
            case 'uploadimage':
                /* 上传涂鸦 */
            case 'uploadscrawl':
                /* 上传视频 */
            case 'uploadvideo':
                /* 上传文件 */
            case 'uploadfile':
                $result = $this->upload();
                break;

            /* 列出图片 */
            case 'listimage':
                $result = $this->getDbList();
                break;
            /* 列出文件 */
            case 'listfile':
                $result = $this->getDbList();
                break;

            /* 抓取远程文件 */
            case 'catchimage':
                $result = $this->crawler();
                break;

            default:
                $result = [
                    'state' => '请求地址出错'
                ];
                break;
        }

        /* 输出结果 */
        if (isset($_GET["callback"])) {
            if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                return htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
            } else {
                return json([
                    'state' => 'callback参数不合法'
                ]);
            }
        } else {
            return json($result);
        }
    }

    /**
     * 上传附件和上传视频
     * @return array
     */
    private function upload()
    {
        /* 上传配置 */
        $base64 = "upload";
        switch ($this->action) {
            case 'uploadimage':
                $param     = [
                    "pathFormat" => $this->config['imagePathFormat'],
                    "maxSize"    => empty($this->size)?$this->auto_config['imageMaxSize']:$this->size,
                    "time"    => $this->time,
                    "allowFiles" =>  empty($this->extList)?$this->auto_config['imageAllowFiles']:$this->extList
                ];
                $fieldName = $this->config['imageFieldName'];
                break;
            case 'uploadscrawl':
                $param     = [
                    "pathFormat" => $this->config['scrawlPathFormat'],
                    "maxSize"    => $this->config['scrawlMaxSize'],
                    "time"    => $this->time,
                    "oriName"    => "scrawl.png"
                ];
                $fieldName = $this->config['scrawlFieldName'];
                $base64    = "base64";
                break;
            case 'uploadvideo':
                $param     = [
                    "pathFormat" => $this->config['videoPathFormat'],
                    "maxSize"    => $this->config['videoMaxSize'],
                    "time"    => $this->time,
                    "allowFiles" => $this->config['videoAllowFiles']
                ];
                $fieldName = $this->config['videoFieldName'];
                break;
            case 'uploadfile':
            default:
                $param     = [
                    "pathFormat" => $this->config['filePathFormat'],
                    "maxSize"    => empty($this->size)?$this->auto_config['fileMaxSize']:$this->size,
                    "time"    => $this->time,
                    "allowFiles" =>  empty($this->extList)?$this->auto_config['fileAllowFiles']:$this->extList
                ];
                $fieldName = $this->config['fileFieldName'];
                break;
        }
        $param['action'] = $this->action;
        /* 生成上传实例对象并完成上传 */
        $up = new UeditorUpload($fieldName, $param, $base64);

        /**
         * 得到上传文件所对应的各个参数,数组结构
         * array(
         *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
         *     "url" => "",            //返回的地址
         *     "title" => "",          //新文件名
         *     "original" => "",       //原始文件名
         *     "type" => ""            //文件类型
         *     "size" => "",           //文件大小
         * )
         */

        /* 返回数据 */

        return $up->getFileInfo();
    }

    /**
     * 获取已上传的文件列表
     * 只获取编辑器上传的图片,和文件
     * @return array
     */
    private function getList()
    {
        /* 判断类型 */
        switch ($this->action) {
            /* 列出文件 */
            case 'listfile':
                $allowFiles = $this->config['fileManagerAllowFiles'];
                $listSize   = $this->config['fileManagerListSize'];
                $path       = $this->config['fileManagerListPath'];
                break;
            /* 列出图片 */
            case 'listimage':
            default:
                $allowFiles = $this->config['imageManagerAllowFiles'];
                $listSize   = $this->config['imageManagerListSize'];
                $path       = $this->config['imageManagerListPath'];
        }
        $allowFiles = substr(str_replace(".", "|", join("", $allowFiles)), 1);

        /* 获取参数 */
        $size  = isset($_GET['size']) ? htmlspecialchars($_GET['size']) : $listSize;
        $start = isset($_GET['start']) ? htmlspecialchars($_GET['start']) : 0;
        $end   = $start + $size;

        /* 获取文件列表 */
        $path  = $_SERVER['DOCUMENT_ROOT'] . (substr($path, 0, 1) == "/" ? "" : "/") . $path;
        $files = $this->getFiles($path, $allowFiles);
        if (!count($files)) {
            return [
                "state" => "no match file",
                "list"  => [],
                "start" => $start,
                "total" => count($files)
            ];
        }

        /* 获取指定范围的列表 */
        $len = count($files);
        for ($i = min($end, $len) - 1, $list = []; $i < $len && $i >= 0 && $i >= $start; $i--) {
            $list[] = $files[$i];
        }

        //倒序
        //for ($i = $end, $list = array(); $i < $len && $i < $end; $i++){
        //    $list[] = $files[$i];
        //}

        /* 返回数据 */
        $result = [
            "state" => "SUCCESS",
            "list"  => $list,
            "start" => $start,
            "total" => count($files)
        ];

        return $result;
    }
    
    /**
     * 获取数据库中的图片,限制大小
     */
    public function getDbList(){
          /* 判断类型 */
        switch ($this->action) {
            /* 列出文件 */
                case 'listfile':
                    $model = model('File');
                    $map = [];
                    if(!empty($this->extList)){
                        $map['ext'] = ['in',$this->extList];
                    }
                    if(!empty($this->size)){
                        $map['size'] = ['elt',$this->size];
                    }
                    $list = $model->where($map)->order('id desc')->field('id as cover_id,path as url,url as url_text,name as original,ext')->select();   
                break;
             /* 列出图片 */
                case 'listimage':
                default:
                    $model = model('Picture');
                    $img_width = $this->request->get('img_width');
                    $img_height = $this->request->get('img_height');
                    $map = [];
                    if(!empty($img_width)&&!empty($img_height)&&$img_width!="undefined"&&$img_height!="undefined"){
                        $map['width'] = $img_width;
                        $map['height'] = $img_height;
                    }
                    if(!empty($this->extList)){
                        $map['ext'] = ['in',$this->extList];
                    }
                    if(!empty($this->size)){
                        $map['size'] = ['elt',$this->size];
                    }
                    $list = $model->where($map)->order('id desc')->field('id as cover_id,path as url,url as url_text')->select();
        }
                    /* 返回数据 */
                    $result = [
                        "state" => "SUCCESS",
                        "list"  => $list,
                        "start" => 0,
                        "total" => count($list)
                    ];

                    return $result;
        
    }

    /**
     * 抓取远程图片
     * @return array
     */
    private function crawler()
    {
        /* 上传配置 */
        $config    = [
            "pathFormat" => $this->config['catcherPathFormat'],
            "maxSize"    => $this->config['catcherMaxSize'],
            "allowFiles" => $this->config['catcherAllowFiles'],
            "oriName"    => "remote.png"
        ];
        $fieldName = $this->config['catcherFieldName'];

        /* 抓取远程图片 */
        $list = [];
        if (isset($_POST[$fieldName])) {
            $source = $_POST[$fieldName];
        } else {
            $source = $_GET[$fieldName];
        }
        foreach ($source as $imgUrl) {
            $item = new UeditorUpload($imgUrl, $config, "remote");
            $info = $item->getFileInfo();
            array_push($list, [
                "state"    => $info["state"],
                "url"      => $info["url"],
                "size"     => $info["size"],
                "title"    => htmlspecialchars($info["title"]),
                "original" => htmlspecialchars($info["original"]),
                "source"   => htmlspecialchars($imgUrl)
            ]);
        }

        /* 返回抓取数据 */

        return [
            'state' => count($list) ? 'SUCCESS' : 'ERROR',
            'list'  => $list
        ];
    }

    /**
     * 遍历获取目录下的指定类型的文件
     * @param string $path
     * @param string $allowFiles
     * @param array  $files
     * @return array|null
     */
    private function getFiles($path, $allowFiles, &$files = [])
    {
        if (!is_dir($path)) return null;
        if (substr($path, strlen($path) - 1) != '/') $path .= '/';
        $handle = opendir($path);
        while (false !== ($file = readdir($handle))) {
            if ($file != '.' && $file != '..') {
                $path2 = $path . $file;
                if (is_dir($path2)) {
                    $this->getFiles($path2, $allowFiles, $files);
                } else {
                    if (preg_match("/\.(" . $allowFiles . ")$/i", $file)) {
                        $files[] = [
                            'url'   => substr($path2, strlen($_SERVER['DOCUMENT_ROOT'])),
                            'mtime' => filemtime($path2)
                        ];
                    }
                }
            }
        }

        return $files;
    }
}