<?php
use yii\helpers\Html;
?>

<?php

    $a = 1;
    if ($a == 1 && isset($messages)): ?>
        <ul class="chat">
            <?php foreach ($messages as $message):
                $message_date = date('d/m H:i', strtotime($message->date));
                ?>
                <?php if (Yii::$app->user->id == $message->sender_id) {
                    ?>
                    <?php echo $message->status ? '<li class="chat_msg_user"><span class="chat_msg_status_ico glyphicon glyphicon-envelope"></span><span class="pull-right msg-date">' . $message_date . '</span>' : '<li class="chat_msg_user_unread"><span class="chat_msg_status_ico glyphicon glyphicon-send"></span><span class="pull-right msg-date">' . $message_date . '</span>'; ?>
                    <?php echo '<span class="glyphicon glyphicon-user"></span> ' . Yii::t('app', 'Me') . ':<br />'; ?>

                <?php } else { ?>

                    <li class="chat_msg_opponent">
                    <?php if (isset($user)) echo '<span class="glyphicon glyphicon-user"></span> ' . $user->displayName() . ': <span class="pull-right msg-date">' . $message_date . '</span><br />'; ?>

                <?php } ?>

                <?php echo nl2br(Html::encode($message->msg)); ?>
                <!--<span class="delete_chat_msg">x</span>-->
                </li>
            <?php endforeach; ?>
        </ul>
<?php endif;
