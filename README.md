**Тестовое задание**

написать тест на PHPUnit который определенно дает знать работает email ящик или нет

- проверить работают ли MX записи в DNS
- отправить письмо на ящик по SMTP протоколу
- учесть задержку на доставку почты
- зайти на ящик POP3 и проверить есть ли там письмо, которое было отправлено тестом
- скрипт должен логгировать свою работу (писать содержимое заголовка Received из тестового письма при каждом запуске). 
   Например: [10-11-2016 22:22] - [Received: by 10.80.130.230 with SMTP id 93csp844271edg; Thu, 10 Nov 2016 09:11:22 -0800 (PST)]


Результатом работы должно быть
1. файл с кодом
2. лог файл с отчетом

**Скрипт**

- настройка почтового ящика производится в классе Config.php.
- класс EmailProcessor.php содержит основные методы (отправка сообщения и получение, проверка MX записей DNS).
- отправка email через smtp была реализована с помощью дополнительной библиотеки PHPMailer.
- отправка и получение сообщение производится с указанием ID, для поиска сообщения и его удаления из почтового ящика.
- сам тест находиться в папке unit с названием EmailProcessorTest.
- при выполнении теста производится логирование с указанием даты и заголовка Received в файл log.dat (в корневой директории).