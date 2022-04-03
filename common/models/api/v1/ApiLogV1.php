<?php

namespace common\models\api\v1;

use Yii;

class ApiLogV1 extends \yii\db\ActiveRecord
{
    const GOOD = 1;
    const BAD = 0;

    public static function tableName()
    {
        return '{{%api_v1_log}}';
    }

    public function rules()
    {
        return [
            [['status', 'user_id'], 'integer'],
            [['status'], 'boolean'],
            [['status'], 'in', 'range' => [self::GOOD, self::BAD]],
            [['ip'], 'ip'],
            [['params', 'response', 'function', 'ip', 'user_id'], 'required'],
            [['function', 'ip', 'params', 'response'], 'string'],
            [['timestamp'], 'date', 'format' => 'php:Y-m-d H:i:s'],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['user_id' => 'id']);
    }

    public function getParamsDecode()
    {
        return json_decode($this->params);
    }

    public function getResponseDecode()
    {
        return json_decode($this->result);
    }

    public function runLogger(int $code, string $response)
    {
        $log = new self();
        $log->user_id = (int)Yii::$app->user->identity->id;
        $log->function = 'v1/' . Yii::$app->controller->id . '/' . Yii::$app->controller->action->id;
        $log->status = $code;
        $log->ip = Yii::$app->request->userIP;
        $log->params = json_encode(Yii::$app->request->post(), JSON_UNESCAPED_UNICODE);
        $log->response = json_encode($response, JSON_UNESCAPED_UNICODE);
        $log->save();
    }

    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'user_id' => 'id пользователя',
            'function' => 'Функция АПИ',
            'params' => 'Параметры',
            'ip' => 'ip адрес',
            'response' => 'ответ сервера',
            'status' => 'Статус операции',
            'timestamp' => 'Дата и время создания',
        ];
    }
}