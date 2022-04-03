<?php

namespace common\models\api\v1;

use Yii;
use yii\db\ActiveRecord;

class RateLimiter extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%api_ip_limits}}';
    }

    public function attributes()
    {
        return ['id', 'user_id', 'action', 'allowance', 'timestamp'];
    }
}