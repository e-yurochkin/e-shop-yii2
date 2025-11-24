<?php

namespace common\RateLimiter;

use Throwable;
use Yii;
use yii\filters\RateLimiter;

class IpRateLimiter extends RateLimiter
{
    /**
     * @var boolean whether to separate rate limiting between non and authenticated users
     */
    public bool $separateRates = true;

    /**
     * @var integer the maximum number of allowed requests
     */
    public int $rateLimit;

    /**
     * @var integer the time period for the rates to apply to
     */
    public int $timePeriod;

    /**
     * @inheritdoc
     * @throws Throwable
     */
    public function beforeAction($action): bool
    {
        $user = $this->user;

        if ($this->separateRates) {
            $user = $user ?: (Yii::$app->getUser() ? Yii::$app->getUser()->getIdentity(false) : null);
        }

        /** @var IpRateLimitInterface $identityClass */
        $identityClass = Yii::$app->getUser()->identityClass;

        $user = $user
            ? $user->setRateLimit($this->rateLimit, $this->timePeriod)
            : $identityClass::findByIp(
                Yii::$app->getRequest()->getUserIP(),
                $this->rateLimit,
                $this->timePeriod
            );

        if ($user instanceof IpRateLimitInterface) {
            $this->checkRateLimit(
                $user,
                $this->request ?: Yii::$app->getRequest(),
                $this->response ?: Yii::$app->getResponse(),
                $action
            );

            return true;
        }

        return parent::beforeAction($action);
    }
}