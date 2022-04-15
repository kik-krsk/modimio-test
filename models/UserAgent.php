<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_agent".
 *
 * @property int $id
 * @property string $full
 * @property string|null $os
 * @property string|null $arch
 * @property string|null $browser
 *
 * @property Log[] $logs
 */
class UserAgent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_agent';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['full'], 'required'],
            [['full'], 'string'],
            [['os', 'arch', 'browser'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'full' => 'Full',
            'os' => 'Os',
            'arch' => 'Arch',
            'browser' => 'Browser',
        ];
    }

    /**
     * Gets query for [[Logs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLogs()
    {
        return $this->hasMany(Log::class, ['user_agent_id' => 'id']);
    }
}
