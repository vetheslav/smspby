<?php

declare(strict_types=1);

namespace Vetheslav\SmspBy\ValueObject;

final class ApiErrorCodes
{
    // Общие коды ошибок API.
    public const int GENERAL_INTERNAL_ERROR = 1;

     // Внутренняя ошибка.
    public const int GENERAL_INVALID_API_CALL = 2;

     // Некорректный API-вызов.
    public const int GENERAL_MISSING_AUTH = 3;

     // Отсутствуют параметры авторизации: user или apikey.
    public const int GENERAL_INVALID_AUTH = 4;

     // Указан неверный user или apikey.
    public const int GENERAL_IP_NOT_ALLOWED = 5;

     // Доступ с данного IP-адреса не разрешен.
    public const int GENERAL_ACCOUNT_INACTIVE = 6;

     // Ваша учетная запись не активирована.
    public const int GENERAL_RATE_LIMIT = 7;

     // Превышена максимальная частота API-запросов.
    public const int GENERAL_CHANNEL_UNAVAILABLE = 8;

     // Данный канал доставки недоступен.
    public const int GENERAL_INVALID_JSON = 9;

     // Передана невалидная JSON-строка.
    public const int GENERAL_EXPECTED_JSON_ARRAY = 10;

     // Неверный параметр: ожидается JSON-массив.
    public const int GENERAL_EMPTY_MESSAGES = 11;

     // Список сообщений пуст.
    public const int GENERAL_MESSAGE_NOT_FOUND = 12;

     // Сообщение не найдено.
    public const int GENERAL_BATCH_TOO_LARGE = 13;

     // Превышен максимальный размер пакета сообщений (до 500).
    public const int GENERAL_TEMPLATE_NOT_FOUND = 14;

     // Шаблон не найден.
    public const int GENERAL_INVALID_REQUEST_PARAMS = 15;

     // Неправильные параметры запроса.
    public const int GENERAL_INVALID_MESSAGE_IDS = 16; // Список ID сообщений пуст или отформатирован неправильно.

    // Коды ошибок валидации параметров функций отправки сообщений.
    public const int VALIDATION_REQUIRED_MISSING = 1;

     // Обязательный параметр отсутствует или имеет пустое значение.
    public const int VALIDATION_TOO_LONG = 2;

     // Слишком длинное значение.
    public const int VALIDATION_INVALID_DATE = 3;

     // Невалидная дата/время. Ожидается формат "yyyy-mm-dd hh:ii:ss".
    public const int VALIDATION_INVALID_URL = 4;

     // Неправильный URL.
    public const int VALIDATION_OUT_OF_RANGE = 5;

     // Значение выходит за границы допустимого диапазона.
    public const int VALIDATION_DUPLICATE_MESSAGE = 20;

     // Дубликат сообщения.
    public const int VALIDATION_INVALID_MSISDN = 100;

     // Невалидный номер получателя.
    public const int VALIDATION_USER_STOPLIST = 101;

     // Номер получателя в стоп-листе (пользовательский).
    public const int VALIDATION_GLOBAL_STOPLIST = 102;

     // Номер получателя в стоп-листе (глобальный).
    public const int VALIDATION_FORBIDDEN_OPERATOR = 103;

     // Номер получателя относится к запрещенному оператору.
    public const int VALIDATION_SENDER_NOT_APPROVED = 104;

     // Имя отправителя недоступно.
    public const int VALIDATION_EMPTY_TEXT = 105;

     // Текст сообщения пуст.
    public const int VALIDATION_STOP_WORD = 106;

     // Текст сообщения содержит стоп-слово.
    public const int VALIDATION_INVALID_SEND_TIME = 107; // Невалидное время отправки. Ожидается формат "yyyy-mm-dd hh:ii:00".
}
