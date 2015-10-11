<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use app\models\User;
use yii\console\Controller;
use yii\rbac\DbManager;
use app\rbac\CouponOwnerRule;

class RbacController extends Controller
{

    public function actionIndex()
    {
        $auth = new DbManager();
        $auth->init();
        $auth->removeAll();

        $added = 0;


        // add rule
        $coupOwnerRule = new CouponOwnerRule();
        $auth->add($coupOwnerRule);
        //create permission
        $viewCoupon = $auth->createPermission('view coupon');
        $viewCoupon->ruleName = $coupOwnerRule->name;
        $auth->add($viewCoupon);


        //add merchant role
        $roleMerchant = $auth->createRole(User::ROLE_MERCHANT);
        $roleMerchant->description = 'Роль организации';
        if ($auth->add($roleMerchant)) {
            $auth->addChild($roleMerchant, $viewCoupon);
            $added++;
        }

        //add admin role
        $roleAdmin = $auth->createRole(User::ROLE_ADMIN);
        $roleAdmin->description = 'Администратор';
        //admin <- merchant
        if ($auth->add($roleAdmin) && $auth->addChild($roleAdmin, $roleMerchant)) {
            $added++;
        }

        echo "Added {$added} roles";
    }
}
