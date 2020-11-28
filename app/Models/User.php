<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use TheSeer\Tokenizer\Exception;

class User extends AuthUser implements Authenticatable
{
    //
    use Notifiable, SoftDeletes;


    /**
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'name',
        'phone',
        'email',
        'password',
        'avatar',
        'realname',
        'idcard',
        'credit1',
        'credit2',
        'credit3',
        'credit4',
        'credit5',
        'credit6',
        'status',
        'order',
        'level',
        'path'
    ];

    /**
     *
     */
    protected static function boot()
    {
        parent::boot();
        //用于初始化 path 和 level 字段值
        static::creating(function (User $user) {
            // 如果创建的是一个根类目
            if (empty($user->parent_id)) {
                // 将层级设为 0
                $user->level = 0;
                // 将 path 设为 -
                $user->path  = '';
            } else {
                // 将层级设为父类目的层级 + 1
                $user->level = $user->parent->level + 1;
                // 将 path 值设为父类目的 path 追加父类目 ID 以及最后跟上一个 - 分隔符
                $user->path  = $user->parent->path.$user->parent_id.',';
            }
        });
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return mixed
     */
    public static function modelGurd()
    {
        return Auth::guard("wapblock");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sons()
    {
        return $this->hasMany($this, 'parent_id', 'id')->select([
            'id', 'parent_id', 'name', 'phone', 'email','created_at'
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function verifie()
    {
        return $this->belongsTo(IdentityReview::class, 'uid');
    }



    public static function Transfer($form_uid, $touid, $currency, $much, $remark = [])
    {
        DB::beginTransaction();
        try {
            //提交一个转账申请 默认是自动审核通过
            $review = CreditReview::InsertReview([
                'type' => 3,
                'p_uid' => $form_uid,
                'uid' => $touid,
                'credit' => $currency,
                'number' => $much,
                'status' => 1,
            ]);
            $f_remark = $remark['f'] ?? "主动转账给" . $touid . $much;
            $f = self::CurrencyChange($form_uid, $currency, -($much), $f_remark);
            $t_remark = $remark['t'] ?? "接收{$form_uid}转账" . $much;
            $t = self::CurrencyChange($touid, $currency, ($much), $t_remark);
            DB::commit();
            return $review && $f && $t;
        } catch (\Exception $e) {
            DB::rollBack();
            throw  new \Exception($e);
        }
    }


    public static function CurrencyChange($uid, $credit = "credit1", $much = 0.00, $remark = "")
    {

        DB::beginTransaction();
        try {
            $credits = array_keys(CreditChinese());
            if (!in_array($credit, $credits)) {
                throw new Exception("无法对" . $credit . "操作");
            }
            $u = 0;
            $opt = '';
            if ($much > 0) {
                $u = self::where("id", $uid)->first()->increment($credit, abs($much));
                $opt = '+';
                if ($remark == "") {
                    $remark = "增加" . $much;
                }
            }else{
                $u = self::where("id", $uid)->first()->decrement($credit, abs($much));
                $opt = '-';
                if ($remark == "") {
                    $remark = "减少" . $much;
                }
            }

            $log = CreditLog::Insertlog([
                'option' => $opt,
                'credit' => $credit,
                'uid' => $uid,
                'much' => $much,
                'remark' => $remark
            ]);
            DB::commit();
            return $log && $u;
        } catch (\Exception $e) {
            DB::rollBack();
            throw  new \Exception($e);
        }

    }
}
