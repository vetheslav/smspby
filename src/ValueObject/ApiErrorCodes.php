<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\ValueObject;

final class ApiErrorCodes
{
    /* Общие коды ошибок API. */
    // Внутренняя ошибка.
    public const int GENERAL_INTERNAL_ERROR = 1;

    // Некорректный API-вызов.
    public const int GENERAL_INVALID_API_CALL = 2;

    // Отсутствуют параметры авторизации: user или apikey.
    public const int GENERAL_MISSING_AUTH = 3;

    // Указан неверный user или apikey.
    public const int GENERAL_INVALID_AUTH = 4;

    // Доступ с данного IP-адреса не разрешен.
    public const int GENERAL_IP_NOT_ALLOWED = 5;

    // Ваша учетная запись не активирована.
    public const int GENERAL_ACCOUNT_INACTIVE = 6;

    // Превышена максимальная частота API-запросов.
    public const int GENERAL_RATE_LIMIT = 7;

    // Данный канал доставки недоступен.
    public const int GENERAL_CHANNEL_UNAVAILABLE = 8;

    // Передана невалидная JSON-строка.
    public const int GENERAL_INVALID_JSON = 9;

    // Неверный параметр: ожидается JSON-массив.
    public const int GENERAL_EXPECTED_JSON_ARRAY = 10;

    // Список сообщений пуст.
    public const int GENERAL_EMPTY_MESSAGES = 11;

    // Сообщение не найдено.
    public const int GENERAL_MESSAGE_NOT_FOUND = 12;

    // Превышен максимальный размер пакета сообщений (до 500).
    public const int GENERAL_BATCH_TOO_LARGE = 13;

    // Шаблон не найден.
    public const int GENERAL_TEMPLATE_NOT_FOUND = 14;

    // Неправильные параметры запроса.
    public const int GENERAL_INVALID_REQUEST_PARAMS = 15;

    // Список ID сообщений пуст или отформатирован неправильно.
    public const int GENERAL_INVALID_MESSAGE_IDS = 16;

    /* Коды ошибок валидации параметров функций отправки сообщений. */
    // Обязательный параметр отсутствует или имеет пустое значение.
    public const int VALIDATION_REQUIRED_MISSING = 1;

    // Слишком длинное значение.
    public const int VALIDATION_TOO_LONG = 2;

    // Невалидная дата/время. Ожидается формат "yyyy-mm-dd hh:ii:ss".
    public const int VALIDATION_INVALID_DATE = 3;

    // Неправильный URL.
    public const int VALIDATION_INVALID_URL = 4;

    // Значение выходит за границы допустимого диапазона.
    public const int VALIDATION_OUT_OF_RANGE = 5;

    // Дубликат сообщения.
    public const int VALIDATION_DUPLICATE_MESSAGE = 20;

    // Невалидный номер получателя.
    public const int VALIDATION_INVALID_MSISDN = 100;

    // Номер получателя в стоп-листе (пользовательский).
    public const int VALIDATION_USER_STOPLIST = 101;

    // Номер получателя в стоп-листе (глобальный).
    public const int VALIDATION_GLOBAL_STOPLIST = 102;

    // Номер получателя относится к запрещенному оператору.
    public const int VALIDATION_FORBIDDEN_OPERATOR = 103;

    // Имя отправителя недоступно.
    public const int VALIDATION_SENDER_NOT_APPROVED = 104;

    // Текст сообщения пуст.
    public const int VALIDATION_EMPTY_TEXT = 105;

    // Текст сообщения содержит стоп-слово.
    public const int VALIDATION_STOP_WORD = 106;

    // Невалидное время отправки. Ожидается формат "yyyy-mm-dd hh:ii:00".
    public const int VALIDATION_INVALID_SEND_TIME = 107;
}
