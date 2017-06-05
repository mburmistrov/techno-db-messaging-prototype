<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "loginHistory".
 *
 * @property integer $loginID
 * @property integer $userID
 * @property string $loginIPAddress
 * @property integer $platformID
 * @property string $deviceInfo
 * @property string $dateTime
 */
class LoginHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'loginHistory';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userID', 'loginIPAddress', 'platformID', 'dateTime'], 'required'],
            [['userID', 'platformID'], 'integer'],
            [['dateTime'], 'safe'],
            [['loginIPAddress'], 'string', 'max' => 15],
            [['deviceInfo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'loginID' => Yii::t('app', 'Login ID'),
            'userID' => Yii::t('app', 'User ID'),
            'loginIPAddress' => Yii::t('app', 'Login Ipaddress'),
            'platformID' => Yii::t('app', 'Platform ID'),
            'deviceInfo' => Yii::t('app', 'Device Info'),
            'dateTime' => Yii::t('app', 'Date Time'),
        ];
    }
}