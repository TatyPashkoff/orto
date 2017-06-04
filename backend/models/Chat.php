<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%chat}}".
 *
 * @property string $id
 * @property string $date
 * @property string $date_view
 * @property string $doctor_id
 * @property string $director_id
 * @property integer $status
 * @property integer $type
 * @property string $msg
 */
class Chat extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%chat}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'sender_id', 'status', 'type'], 'integer'],
            [['msg'], 'string', 'max' => 1024],
            [['date', 'date_view'], 'date', 'format' => 'php:Y-m-d H:i:s']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'date_view' => 'Date View',
            'user_id' => 'User ID',
            'sender_id' => 'Sender ID',
            'status' => 'Status',
            'type' => 'Type',
            'msg' => 'Msg',
        ];
    }

    /**
     * @inheritdoc
     * @return ChatQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ChatQuery(get_called_class());
    }

    public static function createMessage($user_id, $text) {
        if (empty($user_id) || empty($text)) return false;
        if (Yii::$app->user->id == $user_id) return false;
        $model = new self();
        $model->user_id = $user_id;
        $model->sender_id = Yii::$app->user->id;
        $model->msg = $text;
        $model->date = date('Y-m-d H:i:s');
        if ($model->save()) {
            return true;
        } else {
            return $model->errors;
        }
    }

    public static function loadConversation($user_id, $limit) {
        if ( ! $user_id ) return [];
        $query1 = Chat::find()
            ->where(['user_id' => $user_id])
            ->andWhere(['sender_id' => Yii::$app->user->id])
            ->orderBy(['id'=>SORT_DESC])
            ->limit($limit);
        $query2 = Chat::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->andWhere(['sender_id' => $user_id])
            ->orderBy(['id'=>SORT_DESC])
            ->limit($limit);
        $query = $query1->union($query2, false);
        $sql = $query->createCommand()->getRawSql();
        $messages = Chat::findBySql('SELECT * FROM ('.$sql.') s ORDER BY date DESC LIMIT '.intval($limit))->all();
        if (!empty($messages)) {
            $messages = array_reverse($messages);
        }
        return $messages;
    }

    public static function unreadMessages() {
        $unread = Chat::find()
                ->select(['COUNT(*) as co','sender_id'])
				->where(['user_id' => Yii::$app->user->id])
				->andWhere(['status' => 0])
				->groupBy('sender_id')
                ->asArray()
				->all()
				;

		if (empty($unread)) return [];
		
		$return = [];
		foreach($unread as $message) {
			$return[$message['sender_id']] = $message['co'];
		}
        return $return;
    }
    
    public static function unreadMessagesBySenderId($sender_id) {
        $unread = Chat::find()
                ->select(['COUNT(*) as co'])
				->where(['user_id' => Yii::$app->user->id])
				->andWhere(['sender_id' => $sender_id])
				->andWhere(['status' => 0])
                ->asArray()
				->one()
				;

		if (empty($unread)) return 0;
		
        return $unread['co'];
    }
    
    public static function removeUnreadMessageBySenderId($sender_id) {
		Yii::$app->db->createCommand()
					->update(self::tableName(), ['date_view'=>date('Y-m-d'), 'status'=>1], 'user_id = :user AND sender_id = :sender')
					->bindValues([
						':user' => Yii::$app->user->id,
						':sender' => $sender_id
					])->execute();
	}
}
