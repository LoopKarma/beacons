<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "ai_user".
 *
 * @property integer $user_id
 * @property string $create_date
 * @property string $update_date
 * @property string $login
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string $auth_key
 * @property string $merchant_id
 */
class User extends ActiveRecord implements IdentityInterface
{
    const ROLE_ADMIN = 'administrator';
    const ROLE_MERCHANT = 'merchant';
    const ROLE_SELLER = 'seller';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login', 'password'], 'required'],
            [['login', 'role', 'auth_key'], 'string', 'max' => 50],
            [['email'], 'string', 'max' => 100],
            [['password'], 'string', 'max' => 256],
            [['merchant_id', 'active'], 'integer'],
            [['merchant_id'], 'exist', 'targetAttribute' => 'merchant_id', 'targetClass' => Merchant::className()],
            [
                ['role'],
                'in',
                'enableClientValidation' => true,
                'range' => [static::ROLE_ADMIN, static::ROLE_MERCHANT, static::ROLE_SELLER]
            ],
            [['role'], 'validateMerchant'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'active' => 'Активность',
            'user_id' => 'ID',
            'create_date' => 'Дата создания',
            'update_date' => 'Дата изменения',
            'login' => 'Логин',
            'email' => 'Email',
            'password' => 'Пароль',
            'role' => 'Роль',
            'merchant_id' => 'Мерчант',
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'create_date',
                'updatedAtAttribute' => 'update_date',
                'value' => function () {
                    return date("Y-m-d H:i:s");
                },
            ],
        ];
    }

    public function getMerchant()
    {
        return $this->hasOne(Merchant::className(), ['merchant_id' => 'merchant_id']);
    }

    public function validateMerchant($attribute)
    {
        if (in_array($this->{$attribute}, [static::ROLE_MERCHANT, static::ROLE_SELLER])&& !$this->merchant_id) {
            $this->addError('merchant_id', 'Необходимо выбрать организацию');
        }
    }

    /**
     * @param int|string $userId
     * @return null|static
     */
    public static function findIdentity($userId)
    {
        return static::findOne($userId);
    }

    /**
     * @param mixed $token
     * @param null $type
     * @return null|static
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['accessToken' => $token]);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->user_id;
    }


    /**
     * @return mixed
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return bool
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @param $password
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
    }

    public static function findByLogin($login)
    {
        return static::findOne(['login' => $login]);
    }

    public function beforeSave($insert)
    {
        if (isset($this->password)) {
            $this->password = Yii::$app->security->generatePasswordHash($this->password);
        }
        $this->auth_key = Yii::$app->security->generateRandomString(50);
        return parent::beforeSave($insert);
    }

    public function afterLogin()
    {
        //set role
        $auth = Yii::$app->authManager;
        $roleName = $this->role;
        $role = $auth->getRole($roleName);
        if (!$role) {
            Yii::$app->user->logout();
            Yii::$app->session->setFlash(
                'danger',
                'Невозможно определить тип пользователя. Свяжитесь с администратором'
            );
        } else {
            $auth->revokeAll($this->user_id);
            $auth->assign($role, $this->user_id);
        }
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            if ($this->primaryKey != 1) {
                return true;
            } else {
                Yii::$app
                    ->session
                    ->setFlash(
                        'danger',
                        'Нельзя удалить администратора'
                    );
            }
        }
        return false;
    }
}
