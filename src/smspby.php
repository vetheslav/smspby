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

    const IS_URGENT = 1;

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
        $res = $this->getRequest('user_balance');
        if ($res) {
            if ($res->status == 'error') {
                $this->last_global_error_number = $res->error;

                return false;
            } else {
                return (float) $res->balance;
            }
        }

        return false;
    }

    /**
     * Отправка одиночного смс
     *
     * @param int    $number
     * @param string $msg
     * @param int    $urgent Если 1, то срочное сообщение
     * @param string $custom_id
     * @param string $sender
     * @param int    $test
     *
     * @return bool|object
     */
    public function sendSms($number, $msg, $urgent = 0, $custom_id = '', $sender = '', $test = 0)
    {
        if ($sender == '') {
            $sender = $this->sender;
        }

        $params = [
            'recipients' => $number,
            'message' => $msg,
            'sender' => $sender,
            'test' => $test,
            'urgent' => $urgent
        ];
        if ($custom_id != '') {
            $params['custom_id'] = $custom_id;
        }
        $res = $this->getRequest('msg_send', $params);
        if ($res) {
            if ($res->status == 'error') {
                $this->last_global_error_number = $res->error;

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
        $res = $this->getRequest('msg_send_bulk', ['messages' => json_encode($messages)]);
        if ($res) {
            if ($res->status == 'error') {
                $this->last_global_error_number = $res->error;

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
        $res = $this->getRequest('msg_status', ['messages_id' => $sms_id]);
        if ($res) {
            if ($res->status == 'error') {
                $this->last_global_error_number = $res->error;

                return false;
            } else {
                return $res;
            }
        }

        return false;
    }

    ###################################################################
    ###########################          ##############################
    ########################### Контакты ##############################
    ###########################          ##############################
    ###################################################################

    /**
     * Создание контакта
     * Возвращает id контакта, если создан успешно. Иначе false
     *
     * @param int    $phone
     * @param string $first_name
     * @param string $last_name
     * @param string $middle_name
     * @param array  $groups_list
     * @param string $gender
     * @param string $birth_date
     * @param string $description
     * @param string $param1
     * @param string $param2
     *
     * @return bool|int
     */
    public function createContact(
        $phone,
        $first_name = '',
        $last_name = '',
        $middle_name = '',
        $groups_list = [],
        $gender = 'N',
        $birth_date = '',
        $description = '',
        $param1 = '',
        $param2 = ''
    ) {
        if (!in_array($gender, ['N', 'M', 'F'])) {
            $this->last_global_error_number = 'Введен не корректный gender. Должен быть N(null), M(мужской), F(жеский)';

            return false;
        }

        $groups_list = implode(',', $groups_list);

        $params = [
            'phone' => $phone,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'middle_name' => $middle_name,
            'gender' => $gender,
            'description' => $description,
            'param1' => $param1,
            'param2' => $param2,
            'groups_list' => $groups_list
        ];
        if ($birth_date != '') {
            $params['birth_date'] = $birth_date;
        }

        $res = $this->getRequest(
            'contact_create', $params
        );
        if ($res) {
            if ($res->status == 'error') {
                $this->last_global_error_number = $res->error;

                return false;
            } else {
                return $res->id;
            }
        }

        return false;
    }

    /**
     * Удаление контакта
     * Возвращает id контакта, если создан успешно. Иначе false
     *
     * @param int $contact_id
     *
     * @return bool|int
     */
    public function deleteContact($contact_id)
    {
        $res = $this->getRequest('contact_delete', ['id' => $contact_id]);
        if ($res) {
            if ($res->status == 'error') {
                $this->last_global_error_number = $res->error;

                return false;
            } else {
                return $res->id;
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
        $client = new Client(['base_uri' => $this->service]);
        $params['r'] = 'api/' . $cmd;
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
        switch ($this->last_global_error_number) {
            case 1:
                return 'Логин не существует или указан не верно';
            case 2:
                return 'Некорректный api-ключ';
            case 3:
                return 'Ошибка на сервере, обратитесь в техподдержку';
            case 4:
                return 'Ошибка валидации входных параметров для функций create, update.';
            case 5:
                return 'Искомый объект не найден. ';
            case 6:
                return 'Неправильный запрос к серверу API';
            case 7:
                return 'Обязательный параметр отсутствует в запросе';
            case 10:
                return 'Текст сообщения пуст';
            case 11:
                return 'Превышено количество номеров получателей, максимум N номеров на 1 запрос';
            case 12:
                return 'Недостаточно средств на балансе';
            case 13:
                return 'Некорректное или незарегистрированное имя отправителя';
            case 15:
                return 'Лимит срочных сообщений на сегодня исчерпан для одной или нескольких тарифных зон, к которым относятся номера получателей';
            default:
                return '';
        }
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
            case 'new':
                return 'В очереди на отправку';
            case 'send':
                return 'Принято оператором';
            case 'delivered':
                return 'Доставлено абоненту';
            case 'notdelivered':
                return 'Не доставлено абоненту';
            case 'blocked':
                return 'Отклонено, заблокировано оператором ';
            case 'inprogress':
                return 'Доставляется оператором';
            case 'absent':
                return 'СМС с таким ID отсутствует на сервере';
            default:
                return 'Не удалось перевести статус';
        }
    }
}
