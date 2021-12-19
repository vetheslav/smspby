<?php
namespace vetheslav\smspby;

use GuzzleHttp\Client;

/**
 * Общий класс для рассылки смс
 * @package vetheslav\smspby
 */
class smspby
{
    private $user;
    private $apikey;
    private $sender;
    public $service = 'https://cabinet.smsp.by/api/';
    private $last_global_error_number;

    /**
     * smspby constructor.
     *
     * @param string $user
     * @param string $apikey
     * @param string $sender
     */
    function __construct($user, $apikey, $sender)
    {
        $this->user = $user;
        $this->apikey = $apikey;
        $this->sender = $sender;
    }

    /**
     *Функция получения баланса
     *
     * @return float|bool
     */
    public function getUserBalance()
    {
        $res = $this->getRequest('balances');
        if ($res) {
            if (!$res->status) {
                $this->last_global_error_number = $res->error->description;
                return false;
            } else {
                return (float) $res->sms;
            }
        }

        return false;
    }

    /**
     * Отправка одиночного смс
     *
     * @param int    $number
     * @param string $msg
     * @param string $custom_id
     * @param string $sender
     *
     * @return bool|object
     */
    public function sendSms($number, $msg, $custom_id = '', $sender = '')
    {
        if ($sender == '') {
            $sender = $this->sender;
        }

        $params = [
            'msisdn' => $number,
            'text' => $msg,
            'sender' => $sender
        ];
        if ($custom_id != '') {
            $params['custom_id'] = $custom_id;
        }
        $res = $this->getRequest('send/sms', $params);
        if ($res) {
            if (!$res->status) {
                $this->last_global_error_number = $res->error->description;
                return false;
            } else {
                return $res;
            }
        }

        return false;
    }

    /**
     * @param array $messages
     *
     * @return bool|object
     */
    public function sendSmsBulk($messages)
    {
        $res = $this->getRequest('sendBulk/sms', ['messages' => json_encode($messages)]);
        if ($res) {
            if (!$res->status) {
                $this->last_global_error_number = $res->error->description;
                return false;
            } else {
                return $res;
            }
        }

        return false;
    }

    /**
     * Проверка статуса сообщений
     *
     * @param string $sms_id
     *
     * @return bool|object
     */
    public function getSmsStatus($sms_id)
    {
        $res = $this->getRequest('status/sms', ['message_id' => $sms_id]);
        if ($res) {
            if (!$res->status) {
                $this->last_global_error_number = $res->error->description;
                return false;
            } else {
                return $res;
            }
        }

        return false;
    }

    ###################################################################
    ###########################          ##############################
    ########################### Системные #############################
    ###########################          ##############################
    ###################################################################

    /**
     * Функция для получения данных с сервера
     *
     * @param string $cmd
     * @param array  $params
     *
     * @return object|bool
     */
    private function getRequest($cmd, $params = [])
    {
        $client = new Client(['base_uri' => $this->service . $cmd]);
        $params['user'] = $this->user;
        $params['apikey'] = $this->apikey;

        $res = $client->post(
            '', ['form_params' => $params]
        );

        if ($res->getStatusCode() == 200) {
            $data = json_decode($res->getBody());

            return $data;
        }

        return false;
    }

    /**
     * Получение последней ошибки
     * @return string
     */
    public function getLastError()
    {
        return $this->last_global_error_number;
    }

    /**
     * Перевод статуса смс
     *
     * @param $status
     *
     * @return string
     */
    static public function statusTranslate($status)
    {
        switch ($status) {
            case 0:
                return 'В очереди на отправку';
            case 1:
                return 'Принято оператором';
            case 3:
                return 'Доставлено абоненту';
            case 4:
                return 'Не доставлено абоненту';
            case 2:
                return 'Отклонено, заблокировано оператором ';
            case 5:
                return 'Доставляется оператором';
            default:
                return 'Не удалось перевести статус';
        }
    }
}
