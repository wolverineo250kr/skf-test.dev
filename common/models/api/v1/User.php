<?php

namespace common\models\api\v1;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\filters\RateLimitInterface;
use common\models\api\v1\RateLimiter;

/**
 * Api v1.0 User model
 *
 * @property integer $id
 * @property integer $status
 * @property string $token
 * @property string $domain
 * @property string $contact_name
 * @property string $phone
 * @property string $email
 * @property timestamp $timestamp
 * @property timestamp $timestamp_update
 */
class User extends ActiveRecord implements RateLimitInterface, IdentityInterface
{
    const DISABLED = 0;
    const ACTIVE = 1;

    public static function tableName()
    {
        return '{{%api_v1_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'safe'],
            [['email', 'client_id', 'phone', 'contact_name'], 'required'],
            [['id', 'status', 'rate_limit', 'client_id', 'crossdomain'], 'integer'],
            [['status'], 'default', 'value' => self::DISABLED],
            [['status'], 'in', 'range' => [self::ACTIVE, self::DISABLED]],
            [['key', 'domain', 'contact_name', 'email', 'phone'], 'trim'],
            ['email', 'email'],
            [['email',], 'string', 'max' => 255],
            ['key', 'unique', 'targetClass' => self::className(), 'message' => 'Данный токен уже занят', 'when' => function () {
                if ($this->id) {
                    $client = self::find()->select(['key'])->asArray()->where(['id' => $this->id])->one();
                    if ($client) {
                        if ($this->key != $client['key']) {
                            return true;
                        }
                    }
                }

                return ($this->id) ? false : true;
            }
            ],
            ['key', 'string', 'max' => 32],
            [['timestamp', 'timestamp_update',], 'date', 'format' => 'php:Y-m-d H:i:s'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'phone' => 'Телефон',
            'email' => 'Email',
            'client_id' => 'id клиента',
            'crossdomain' => 'Кроссдоменность',
            'status' => 'Активность',
            'key' => 'Токен',
            'domain' => 'Домен сайта',
            'rate_limit' => 'Лимит запросов на метод',
            'contact_name' => 'ФИО контактного лица',
            'timestamp' => 'Дата создания',
            'timestamp_update' => 'Дата обновления',
        ];
    }

    public function beforeDelete()
    {
        return parent::beforeDelete();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::ACTIVE]);
    }

    public static function getClient($client_id)
    {
        return \common\models\client\Client::findOne(['id' => $client_id, 'status' => self::ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($authKey, $type = null)
    {
        //    2) для авторизации используется поле key (текстовая строка);
        return static::findOne(['key' => $authKey, 'status' => self::ACTIVE]);
    }


    public function getDebitor()
    {
        $client = \common\models\client\Client::findOne(['id' => $this->client_id]);
        return $client->debitor;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function getRateLimit($request, $action)
    {
        return [$this->rate_limit, 1];
    }

    public function loadAllowance($request, $action)
    {
        $limiter = RateLimiter::findOne(['user_id' => $this->id, 'action' => $action->id]);

        if (!$limiter) {
            $limiter = new RateLimiter([
                'user_id' => $this->id,
                'action' => $action->id,
                'timestamp' => time(),
                'allowance' => $this->rate_limit,
            ]);
            $limiter->save();
        }

        return [$limiter->allowance, $limiter->timestamp];
    }

    public function saveAllowance($request, $action, $allowance, $timestamp)
    {
        $limiter = RateLimiter::findOne(['user_id' => $this->id, 'action' => $action->id]);
        $limiter->timestamp = $timestamp;
        $limiter->allowance = $allowance;
        $limiter->save();
    }
}