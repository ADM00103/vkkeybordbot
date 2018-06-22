<?php
include "vk_api.php"; //Подключаем нашу волшебную библиотеку для работы с api vk

//**********CONFIG**************
$vk_key = "9c7d54b8d32067a1c4312975ca21c8ad573483fd76f1172b6bfc3a74e9d77b0fb3f525870e3466f16ebd1"; //тот самый длинный ключ доступа сообщества
$access_key = "19d8f2c3"; //например c40b9566, введите свой
$uploaddir = __DIR__ . "/img/"; //Путь к каталогу с картинками
//******************************

$vk_api = new vk_api($vk_key); //Ключ сообщества VK

$data = json_decode(file_get_contents('php://input')); //Получает и декодирует JSON пришедший из ВК

if (isset($data->type) and $data->type == 'confirmation') { //Если vk запрашивает ключ
	echo $access_key; //Отправляем ключ
	exit(0); //Завершаем скрипт
}

echo 'ok'; //Говорим vk, что мы приняли callback

if (isset($data->type) and $data->type == 'message_new') { //Проверяем, если это сообщение от пользователя

  $id = $data->object->user_id; //Получаем id пользователя, который написал сообщение

  $send = 0; //Флаг 0 (Магия о_О)

  $message = $data->object->body; //Получаем тест сообщение пользователя(в этом скрипте не используется, но вам может понадобится)
  if (!isset($data->object->payload)){ //Если кнопка не нажата
  
    $button1_1 = [["animals" => 'Fish'], "!FAQ", "red"]; //Генерируем кнопку 'Fish'
    $button1_2 = [["animals" => 'Other animals'], "!бот", "green"]; //Генерируем кнопку 'Other animals'

    $vk_api->sendButton($id, 'Спасибо', [ //Отправляем кнопки пользователю
    [$button1_1, $button1_2]
    ]);
	
 

      
    
    }
  }

?>
