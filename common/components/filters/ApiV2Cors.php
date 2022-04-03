<?php

namespace common\components\filters;

use yii\filters\Cors;
use Yii;

class ApiV2Cors extends Cors
{

    public function prepareHeaders($requestHeaders)
    {
        if (Yii::$app->user->identity->crossdomain && isset($requestHeaders['Origin'])) {
            $internalOrigin = str_replace(['http://', 'https://'], '', $requestHeaders['Origin']);

            if (stripos($internalOrigin, Yii::$app->user->identity->domain) !== false) {
                $this->cors['Origin'] = [
                    'http://'.$internalOrigin,
                    'https://'.$internalOrigin
                ];
            } else {
                $this->cors['Origin'] = [
                    'http://'.Yii::$app->user->identity->domain,
                    'https://'.Yii::$app->user->identity->domain,
                ];
            }
        }
//        print_r($this->cors['Origin']);
        return parent::prepareHeaders($requestHeaders);
    }
}