<?php
namespace app\rbac;

use Yii;
use yii\rbac\Rule;
use app\models\User;

class CouponOwnerRule extends Rule
{
    public $name = 'couponOwner';

    /**
     * @param string|integer $user   the user ID.
     * @param \yii\rbac\Item $item   the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user, $item, $params)
    {
        $res = false;
        /** @var \app\models\User $user */
        $user = User::findOne($user);
        if ($user->role == User::ROLE_ADMIN) {
            $res = true;
        } else {
            if ($params['coupon']->merchant_id == $user->merchant_id) {
                $res = true;
            }
        }
        return $res;
    }
}