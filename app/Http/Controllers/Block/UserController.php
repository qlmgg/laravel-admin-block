<?php

namespace App\Http\Controllers\Block;

use App\Models\Block\Notice;
use App\Models\Block\Recording;
use App\Models\Block\RecordingPriceLog;
use App\Models\User;
use App\Models\IdentityReview;
use App\Models\UserSign;
use App\Models\UserTree;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class UserController extends BaseController
{

    //
    public function index()
    {
        $user = $this->user->user();
        $shenfen = $this->identity();


        //所有的卡片总价格
        $recordingPriceLog = RecordingPriceLog::where("uid", $user->id)->get();
        $leijishouyi = array_sum(array_column($recordingPriceLog->toArray(), 'much'));

        $qiandao = UserSign::where([
            'uid' => $user->id,
            'ymd' => Carbon::now()->format("Ymd")
        ])->count();

        $info = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone
            ],
            'usdt' => [
                'href' => route("block.credit.history", ['credit' => 'credit1']),
                'value' => $user->credit1,
            ],
            'jifen' => [
                'href' => route("block.credit.history", ['credit' => 'credit2']),
                'value' => $user->credit2,
            ],
            'dtc' => [
                'href' => route("block.credit.history", ['credit' => 'credit3']),
                'value' => $user->credit3,
            ],
            //累计收益
            'leiji' => [
                'href' => route("block.record.pricehistroy"),
                'value' => $leijishouyi,
            ],
            // 总资产
            'assets' => [
                'href' => "javascript:void(0)",
                'value' => Recording::where([
                    'uid' => $user->id,
                    'winbidding' => 1,
                    'userhave' => 1,
                    'transfer_status' => 0,
                ])->sum("price"),
            ],
            //推荐收益
            'share' => [
                'href' => route("block.credit.history", ['credit' => 'credit6']),
                'value' => $user->credit6,
            ],

            'shenfen' => $shenfen,
            'qiandao' => $qiandao,
        ];
        return view("user.index", ['info' => $info]);
    }

    function message()
    {
        //默认全部要在信息全部展示
        $message = Notice::get()->toArray();
        return view("user.message", ['message' => $message]);
    }

    public function setting()
    {
        return view("user.setting", ['user' => $this->user->user()]);
    }


    public function collect()
    {
        $statusinfo = [
            -1 => "审核失败", 0 => "待审核", 1 => "通过审核"
        ];
        $way = IdentityWayChinese();
        $list = IdentityReview::where("uid", $this->user->id())->get();
        foreach ($list as $k => $v) {

            $list[$k]['wayinfo'] = $way[$v['way']];
            $list[$k]['pic'] = $this->blockService->FullImage($v['image']);
            $list[$k]['statusinfo'] = $statusinfo[$v['status']];
        }
        return view("user.collect", [
            'user' => $this->user->user(),
            'list' => $list->toArray()
        ]);
    }

    public function delcollect(Request $request)
    {
        $info = IdentityReview::where("id", $request['id'])->get();
        if ($info == null) {
            return $this->error("删除失败");
        }
        IdentityReview::where("id", $request['id'])->delete();
        return $this->success("删除成功");
    }

    public function addcollect(Request $request)
    {
        if ($request->ajax()) {

            $inputs = $request->only(['way', 'name', 'phone', 'account', 'filepath']);

            $validator = Validator::make($inputs, [
                'way' => 'required',
                'realname' => 'required',
                'phone' => 'required',
                'account' => 'required',
                'image' => 'required',
            ]);
            if (!$validator->fails()) {
                return $this->error("存在未填写信息，补全后提交");
            }
            $IdentityReviewcount = IdentityReview::where([
                'way' => $inputs['way'],
                'uid' => $this->user->id(),
            ])->count();
            if ($IdentityReviewcount > 0) {
                return $this->error("您已经提交过了，如需提交请删除后重新提交");
            }
            $boo = IdentityReview::insert([
                'way' => $inputs['way'],
                'uid' => $this->user->id(),
                'realname' => $inputs['name'],
                'phone' => $inputs['phone'],
                'account' => $inputs['account'],
                'image' => $inputs['filepath'],
            ]);
            if ($boo) {
                return $this->success("提交成功", route("block.user.collect"));
            } else {
                return $this->error("提交失败");
            }
        }
        $pay = IdentityWayChinese();
        unset($pay[0]);
        return view("user.addcollect", ['paymentmethod' => $pay]);
    }


    public function team()
    {
        return view("user.team");
    }


    public function passwd(Request $request)
    {
        if ($request->ajax()) {
            $inputs = $request->only(["passwd", "passwd2"]);
            if (strcmp($inputs['passwd'], $inputs['passwd2']) != 0) {
                return $this->error("密码确认密码输入有误");
            }
            User::where('id', $this->user->id())->update(['password' => $inputs['passwd']]);
            $this->user->logout();
            return $this->success("修改成功", route("block.Auth.login"));
        }
        return view("user.passwd", ['user' => $this->user->user()]);
    }


    public function verified(Request $request)
    {
//        $curr = $this->identity();
//        if (!$curr) {
//            return $this->error("跳转中", route("block.user.addcollect", ['source' => "verified"]));
//        }
        return $this->success("未启用");
    }

    public function doteam(Request $request)
    {
        $page = $request->input("page", 1);
        $pagesize = $request->input("pagesize", 5);
        $uid = $this->user->id();
        $userlist = UserTree::UserSontree($uid);
        //以root相同进行统计
        $rootarr = array_column($userlist, 'root');
        $countryroot = $rootarr ? array_count_values($rootarr) : [1 => 0, 2 => 0, 3 => 0];
        //数组分页
        $itemCollection = collect($userlist);
        //总页数
        $totalpage = ceil(count($itemCollection) / $pagesize);
        $pageuserlist = $itemCollection->slice(($page * $pagesize) - $pagesize, $pagesize)->all();
        return $this->success("成功", "", [
            'userlist' => array("pages" => $totalpage, "data" => $pageuserlist),
            'countryroot' => $countryroot
        ]);
    }


    public function logout()
    {
        $this->user->logout();
        Session::forget("remember_uid");
        return $this->success("退出成功", route("block.Auth.login"));
    }

    public function qrcode()
    {

        $shenfen = $this->identity();
        if ($shenfen == null) {
            return redirect()->route("block.user.collect");
        }
        $uid = \Vinkla\Hashids\Facades\Hashids::encode($this->user->id());
        $url = route("block.Auth.register") . "/" . $uid;
        $gen = QrCode::format('png')->merge("/public/block/images/logo.png")->size(300)->generate($url);
        $base64img = 'data:image/png;base64,' . base64_encode($gen);
        return view("user.qrcode", ['image' => $base64img]);
    }
}
