<?php

namespace App\Models;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;
use Illuminate\Database\Eloquent\Model;

class UserTree extends Model
{
    //
    use ModelTree, AdminBuilder;

    protected $table = "users";

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTitleColumn('phone');
    }


    public static function UserSontree($uid)
    {
        $users = User::with("sons.sons.sons")->where("id", $uid)->first()->makeHidden('password');
        if ($users == null) {
            return [];
        }
        $userArr = $users->toArray();
        if (!$userArr['sons']){
            return [];
        }
      return self::dataRootTree($userArr['sons'],1,'sons');
    }

    protected static function dataRootTree(&$dataArr, $level = 1,$filed='sons')
    {
        static $sonlist = [];
        foreach ( $dataArr as $k =>$val){
            $val['root'] = $level;
            if (empty($val[$filed])){
                $sonlist[]=$val;
            }else{
                $temp_son = $val[$filed];
                unset($val[$filed]);
                $sonlist[]=$val;
                self::dataRootTree($temp_son,$level+1);
            }
        }
        return $sonlist;
    }


    public static function UserFathertree($uid, $root = 1, $maxRoot = 3)
    {
        static $userlist = [];
        $user = self::where("id", $uid)->select([
            'id', 'parent_id', 'name', 'phone', 'email', 'created_at'
        ])->first()->toArray();
        $pid = $user['parent_id'];
        if ($pid) {
            if ($root <= $maxRoot) {
                $f = self::where("id", $pid)->select([
                    'id', 'parent_id', 'name', 'phone', 'email', 'created_at'
                ])->first()->toArray();
                $f['root'] = $root;
                $userlist[] = $f;
                return self::UserFathertree($pid, $root + 1);
            }
        }
        return $userlist;
    }
}
