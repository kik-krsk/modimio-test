<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "log".
 *
 * @property int $id
 * @property string $ip
 * @property string $date
 * @property string $url
 * @property int|null $user_agent_id
 *
 * @property UserAgent $userAgent
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ip', 'date', 'url'], 'required'],
            [['date'], 'safe'],
            [['url'], 'string'],
            [['user_agent_id'], 'integer'],
            [['ip'], 'string', 'max' => 255],
            [['user_agent_id'], 'exist', 'skipOnError' => true, 'targetClass' => UserAgent::class, 'targetAttribute' => ['user_agent_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ip' => 'Ip',
            'date' => 'Date',
            'url' => 'Url',
            'user_agent_id' => 'User Agent ID',
        ];
    }

    /**
     * Gets query for [[UserAgent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserAgent()
    {
        return $this->hasOne(UserAgent::class, ['id' => 'user_agent_id']);
    }
}
