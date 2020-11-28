<?php

namespace App\Http\Controllers\Block;

use App\Models\Block\Recording;
use App\Models\Block\RecordingAppeal;
use App\Models\Block\RecordingPayReview;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;

class PayrController extends BaseController
{

    /** 区块记录支付
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \Exception
     */
    public function Recording(Request $request)
    {
        $id = $request->input('id');
        $user = $this->user->user()->toArray();
        $recording = Recording::with(['card', 'releaseuser', 'user'])->where("id", $id)->first();

        if ($recording['releaseuser']['id']) {
            $recording['releaseuseridentity'] = $this->identity($recording['releaseuser']['id'], true);
        }
        $review = RecordingPayReview::where([
            'recording_id' => $id,
            'from_uid' => $recording['releaseuser']['id'],
            'uid' => $user['id'],
        ])->first();
        if ($review != null) {

            $reviewarr = $review->toArray();
            $reviewarr['image'] = $this->blockService->FullImage($reviewarr['image']);
            $recording['reviewarr'] = $reviewarr;
        }
        if ($request->ajax() && $recording) {
            $image = $request->input("filepath");
            if ($review == null) {
                RecordingPayReview::create([
                    'recording_id' => $id,
                    'from_uid' => $recording['releaseuser']['id'],
                    'uid' => $user['id'],
                    'price' => $recording['price'],
                    'image' => $image,
                ]);
            } else {
                RecordingPayReview::where([
                    'id' => $review->id,
                ])->update([
                    'image' => $image,
                    'status' => 0,
                    'fail_reason' => ""
                ]);
            }
            return $this->success("提交成功", route("block.record.adopt"));
        }
        return view("payr.recording", ['recording' => $recording]);
    }

    public function appeal(Request $request){
        $id=$request->get("id");
        $user = $this->user->user();
        // 别人中标 但是 没有拥有
        $infirst = Recording::with(['user'])->where([
            'id'=>$id,
            'winbidding' => 1,
            'release_uid'=>$user->id,
            'transfer_status'=>0,
            'userhave'=>0
        ])->first();
        if($infirst != null){
            $infirst->userphone = $infirst->user->phone;
            $appeal = RecordingAppeal::where([
                'recording_id'=>$id,
                'complaint_uid'=>$user->id,
            ])->first();
            if($appeal != null){
                $infirst->appeal = $appeal->reason;
            }else{
                $infirst->appeal = "";
            }
            $infirstArr = $infirst->toArray();
        }else{
            $infirstArr = [
                'hashblock'=>"",
                'price'=>"",
                'userphone'=>"",
                'appeal'=>"",
                'id'=>0
            ];
        }
        if($request->ajax()){
            $reason = $request->input("reason");
            $appeal= RecordingAppeal::create([
                'recording_id'=>$id,
                'complaint_uid'=>$user->id,
                'accused_uid'=>$infirst->uid,
                'reason'=>$reason
            ]);
            return $this->success("提交成功");
        }
        return View::make("payr.appeal",['recording'=>$infirstArr]);
    }

    /**
     * 区块支付详情
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \Exception
     */
    public function Recordingdetail(Request $request)
    {
        $id = $request->input('id');
        $user = $this->user->user()->toArray();
        $recording = Recording::with(['card', 'releaseuser', 'user'])->where("id", $id)->first()->toArray();
        $review = RecordingPayReview::where([
            'recording_id' => $id,
        ])->orderBy("id", "desc")->first();
        if ($review) {
            $reviewarr = $review->toArray();
            $reviewarr['image'] = $this->blockService->FullImage($reviewarr['image']);
            $recording['reviewarr'] = $reviewarr;
        }

        if ($request->ajax() && $recording) {
            $method = $request->input("method");
            if (!in_array($method, ['fail', 'pass'])) {
                return $this->error("提交数据有误");
            }
            return $this->$method($review->id);
        }
        return view("payr.recordingdetail", ['recording' => $recording]);
    }

    /**
     * @param $review_id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function fail($review_id)
    {
        $re = RecordingPayReview::where([
            "id" => $review_id,
            'status' => 0
        ])->first();
        if ($re == null) {
            return $this->error("操作失败1");
        }
        $reason = request()->input("reason");
        if (!$reason) {
            return $this->error("原因未填写");
        }
        RecordingPayReview::where('id', $review_id)->update([
            'status' => 2,
            'fail_reason' => $reason
        ]);
        return $this->success("操作成功");
    }

    /**
     * @param $review_id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    function pass($review_id)
    {
        $re = RecordingPayReview::where([
            "id" => $review_id,
            'status' => 0
        ])->first();

        if ($re == null) {
            return $this->error("操作失败1");
        }
        Recording::where('id', $re['recording_id'])->update([
            'userhave' => 1,
            'userhave_at' => Carbon::now(),
        ]);
        RecordingPayReview::where('id', $review_id)->update([
            'status' => 1
        ]);
        return $this->success("转让成功");
    }

}
