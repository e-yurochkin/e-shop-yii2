<?php

namespace backend\requests\auth;

use common\models\User;
use Yii;
use yii\base\Exception;
use yii\base\Model;

class ResendVerificationEmailRequest extends Model
{
    public ?string $email = null;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
//            ['email', 'exist',
//                'targetClass' => '\common\models\User',
//                'filter' => ['status' => User::STATUS_INACTIVE],
//                'message' => 'There is no user with this email address.'
//            ],
        ];
    }

    /**
     * Sends confirmation email to user
     *
     * @param array $data
     * @return array whether the email was sent
     * @throws Exception
     */
    public function sendEmail(array $data): array
    {
        Yii::$app->response->statusCode = 400;

        if (!$this->load($data, '') || !$this->validate()) {
            return ['errors' => $this->errors];
        }

        $user = User::findOne(['email' => $this->email]);

        if (!$user) {
            $this->addError('email', 'There is no user with this email address.');

            return ['errors' => $this->errors];
        }

        Yii::$app->response->statusCode = 200;

        if ($user->status === User::STATUS_ACTIVE) {
            return ['message' => 'Your email already verified.'];
        }

        if ($user->status === User::STATUS_DELETED) {
            return ['message' => 'Your account has been deleted.'];
        }

        $user->generateEmailVerificationToken();
        $user->save();

        Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();

        return ['message' => 'Verification link sent to your email address.'];
    }
}
