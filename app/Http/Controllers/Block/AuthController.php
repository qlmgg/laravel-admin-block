<?php

namespace App\Http\Controllers\Block;



use App\Models\Config;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    public function login(){

        $butBox = " <a class=\"but\" href=\"javascript:void(0)\" class=\"findpwd fw_b fs26 color_r\">扫码注册</a>";
        $href =  Config::GetKeyValue("BLOCKAPPURL","value");
        if($href){
            $butBox = " <a class=\"but\" href=\"{$href}\" class=\"findpwd fw_b fs26 color_r\">app下载</a>";
        }
        return view("auth.login",['butBox'=>$butBox]);
    }
    public function pwd(Request $request){
        if ($request->post()){
            $inputs = $request->only(['username','code','password1','password2','codekey']);

            $validator = Validator::make($inputs, [
                'username' => 'required|regex:/^1[0-9]{10}$/',
                'code' => 'required',
                'password1' => 'required',
                'password2' => 'required',
                'codekey' => 'required',
            ],[
                'username.required'=>"手机号未填写",
                'username.regex'=>"手机号不合法",
                'code.required'=>'验证码未填写',
                'password1.required'=>'密码未填写',
                'password2.required'=>'确认密码未填写',
                'codekey.required'=>'非法入侵',
            ]);
            $error = $validator->errors()->first();
            if ($error){
                return $this->error($error);
            }
            $cachecode = Cache::get($inputs['codekey']);
            if($cachecode == null){
                return $this->error("验证码已过期");
            }
            if($cachecode != $inputs['code']){
                return $this->error("验证码填写有误");
            }

            $info = User::where("phone",$inputs['username'])->first();

            if($info == null){
                return $this->error("该用户不存在，请进行注册");
            }

            User::where("id",$info->id)->update([
                'password'=>PwdEncrypt($inputs['password1'])
            ]);
            return $this->success("修改成功",route("block.Auth.login"));
        }
        return view("auth.pwd");
    }

    public function register($rcode=0){
        if ($rcode){
            Cookie::queue("register_uid",$rcode);
        }
        return view("auth.register",['rcode'=>$rcode]);
    }

    public function doregister(Request $request){
        $inputs = $request->only(['username', 'rcode','password']);
        $user = User::where("phone",$inputs['username'])->first();
        if($user){
            return $this->error("该用户已经注册");
        }
        $parent_ids =\Vinkla\Hashids\Facades\Hashids::decode($inputs['rcode']);
        if(empty($parent_ids)){
            return $this->error("请进行扫码注册");
        }

        $parent_id =$parent_ids[0] ;
        $parent_user = User::where("id",$parent_id)->first();
        if($parent_user == null){
            return $this->error("请进行扫码注册");
        }
        $validator = Validator::make($inputs, ['username' => 'required|numeric|min:11|max:11']);
        if (!$validator->fails()) {
            return $this->error("手机号填写有误");
        }
        //存储数据  发送短信
        $create = User::create([
            'name'=>$inputs['username'],
            'phone'=>$inputs['username'],
            'password'=>PwdEncrypt($inputs['password']),
            'parent_id'=>$parent_id,
            'email'=>rand()."@zqw.xyz",
            'status'=>0
        ]);

        return $this->success("注册成功",route("block.Auth.login"),['id'=>$create->id]);

    }

    //
    public function dologin(Request $request){
        $inputs = $request->all();
        $validator = Validator::make($inputs, [
            'username' => 'required',
            'password' => 'required'
        ], [
            'required' => '未填写',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors());
        }
        $username = $inputs['username'];
        $password = $inputs['password'];
        $remember = $inputs['remember'];

        $userinfo = User::where("password",md5($password))->where("phone", $username)->first();
        if ($userinfo == null) {
            return $this->error("用户名或密码错误");
        }

        $statusmsg = [
            -1 => '待激活',
            0 => '正常',
            1 => '账号冻结'
        ];

        if($userinfo['status'] != 0){
            return $this->error($statusmsg[$userinfo['status']]);
        }
        User::modelGurd()->login($userinfo,$remember);
        $uid=User::modelGurd()->id();
        return $this->success("登录成功",route("block.home.index"),$uid);
    }
}
