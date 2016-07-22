<?php
/**
 * author     : forecho <caizhenghai@gmail.com>
 * createTime : 2016/6/1 14:56
 * description:
 */

namespace yiier\slack;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\Json;

class Client extends Component
{
    public $url;
    public $username;
    public $emoji;
    public $defaultText = "Message from Yii application";

    /** @var string|object */
    public $httpClient = '';

    public function init()
    {
        if (!$this->httpClient) {
            throw new InvalidConfigException("Client::httpClient cannot be empty .");
        }

        $this->httpClient = Yii::createObject($this->httpClient);
        if (!method_exists($this->httpClient, 'post')) {
            throw new InvalidConfigException("Client::httpClient post method must exist .");
        }
    }

    public function send($text = null, $icon = null, $attachments = [])
    {
        $this->httpClient->post($this->url, [
            'payload' => Json::encode($this->getPayload($text, $icon, $attachments)),
        ]);
    }

    protected function getPayload($text = null, $icon = null, $attachments = [])
    {
        if ($text === null) {
            $text = $this->defaultText;
        }

        $payload = [
            'text' => $text,
            'username' => $this->username,
            'attachments' => $attachments,
        ];
        if ($icon !== null) {
            $payload['icon_emoji'] = $icon;
        }
        return $payload;
    }

}