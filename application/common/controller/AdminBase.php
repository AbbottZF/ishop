<?php
namespace app\common\controller;
use org\Auth;
use think\Controller;
use think\Db;
use think\Session;
use app\common\model\ActionLog;
use app\utils\service\ConfigService;

class AdminBase extends Controller{
    
    protected function _initialize() {
        parent::_initialize();
        //加载系统配置
        ConfigService::config();
        if(config('admin_theme')=="微信主题"){
            $this->getWeMenu();
        }else{
        $this->getMenu();
        }
        $this->checkAuth();
        //输出trace
        if (config('app_trace')) {
            trace('服务器端口:' . $_SERVER['SERVER_PORT'], 'user');
            trace('服务器环境:' . $_SERVER['SERVER_SOFTWARE'], 'user');
            trace('PHP版本:' . PHP_VERSION, 'user');
            $version = Db::query('SELECT VERSION() AS ver');
            trace('MySQL版本:' . $version[0]['ver'], 'user');
            trace('最大上传限度:' . ini_get('upload_max_filesize'), 'user');
        }
    }

    /**
     * 权限检查
     * @return bool
     */
    protected function checkAuth() {

        if (!Session::has('admin_id')) {
            $this->redirect('admin/login/index');
        }
        $admin_id = Session::get('admin_id');
        if (Session::get('is_admin_'.$admin_id)==1)
            return true;
        $module = $this->request->module();
        $controller = $this->request->controller();
        $action = $this->request->action();
        //优先过滤魔法方法
        if (strpos($action, "_") === 0) {
            return true;
        }
        // 排除权限
        $not_check = json_decode(strtolower(json_encode(config('allow_list'))), true);
        if (!in_array(strtolower($module . '/' . $controller . '/' . $action), $not_check)) {
            $auth = new Auth();
            if (!$auth->check($module . '/' . $controller . '/' . $action, $admin_id) && Session::get('is_admin_'.$admin_id)!=1) {
                $this->error('没有权限');
            }
        }
    }
    
    /**
     * 获取侧边栏菜单
     */
    protected function getMenu() {
        $menu = [];
        $admin_id = Session::get('admin_id');
        $auth = new Auth();
        $auth_rule_list = Db::name('auth_rule')->where('status', 1)->where('type',1)->order(['sort' => 'DESC', 'id' => 'ASC'])->select();
        foreach ($auth_rule_list as $value) {
            if ($auth->check($value['name'], $admin_id) || Session::get('is_admin_'.$admin_id)==1) {
                $menu[] = $value;
            }
        }
        $menu = !empty($menu) ? array2tree($menu) : [];
        tree_sort($menu);
        $this->assign('menu', $menu);
    }
    /**
     * 获取微信主题导航
     */
    protected function getWeMenu(){
        $menu = [];
        $mainMenu = [];
        $admin_id = Session::get('admin_id');
        $auth = new Auth();
        $auth_rule_list = Db::name('auth_rule')->where('status', 1)->where('type',1)->order(['sort' => 'DESC', 'id' => 'ASC'])->select();
        foreach ($auth_rule_list as $value) {
            if ($auth->check($value['name'], $admin_id) || Session::get('is_admin_'.$admin_id)==1) {
                $menu[] = $value;
                if($value['pid']==0&&!empty($value['tag'])){
                    $mainMenu[$value['tag']]['title'] = $value['tag'];
                    if(!isset($mainMenu[$value['tag']]['name'])||empty($mainMenu[$value['tag']]['name'])){
                        $name = Db::name('auth_rule')->where('status', 1)->where('type',1)->where('pid',$value['id'])->order(['sort' => 'DESC'])->value('name');
                        $mainMenu[$value['tag']]['name'] = empty($name)?$value['name']:$name;
                    }
                }
            }
        }
        $this->assign('mainMenu',$mainMenu);
        $return = sub_heigh_light($menu);
        $this->assign('currentMenu',$return['currentMenu']);
        $this->assign('currentTag',$return['tag']);
        $menu = !empty($menu) ? array2tree($menu) : [];
        tree_sort($menu);
        $menu = array_filter($menu, function($v) use ($return) {
            return $v['tag'] == $return['tag'];
        }); 
        $this->assign('menu', $menu);
        //获取主菜单
        return $menu;
    }
    
    //添加日志
    public function addLog($title="",$remakr=""){
        //if(session('admin_id')!=1){
        (new ActionLog())->addLog(1,$title,$remakr);
        //}
    }
    

    /**
     * 获取表单查询条件
     * name 命名为字段名称/条件
     * 条件为 [eq] [neq] [like] [in] [not in] [gt] [lt] [egt] [elt]
     */
    protected function _where() {
        //接收参数
        $data = $this->request->get();
        $_where = [];
        if (empty($data))
            return $_where;
        foreach ($data as $key_condition => $val) {
            $keyArr = explode('/', $key_condition);
            $key = current($keyArr);
            $condition = "eq";
            $this->assign($key, $val);
            if(count($keyArr)>2){
               $key = end($keyArr).".$key";
               $condition = $keyArr[1];
            }else{
            count($keyArr) > 1 && $condition = end($keyArr);
            }
            if(!empty($val)){
            switch ($condition) {
                case 'eq':
                    $_where[$key] = ['eq', $val];
                    break;
                case 'neq':
                      $_where[$key] =['neq', $val];
                    break;
                case 'like':
                      $_where[$key] = ['like', "%$val%"];
                    break;
                case 'in':
                      $_where[$key] = ['in', (array) $val];
                    break;
                case 'not in':
                      $_where[$key] = ['not in', (array) $val];
                    break;
                case 'gt':
                      $_where[$key] = ['gt', $val];
                    break;
                case 'egt':
                      $_where[$key] = ['egt', $val];
                    break;
                case 'lt':
                      $_where[$key] = ['lt', $val];
                    break;
                case 'elt':
                      $_where[$key] = ['elt', $val];
                    break;
            }
          }
        }
        unset($_where['page']);
        return $_where;
    }
}