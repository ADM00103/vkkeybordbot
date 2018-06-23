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
  
  if ($data->type == 'message_new' && $data->object->body == '/hi')
  {
	       
		   $vk_api->sendButton($id, 'Fuckshit', null); 
		  return false;
  }
  
  if (!isset($data->object->payload)){ //Если кнопка не нажата
  
    $button1_1 = [["animals" => 'Fish'], "!FAQ", "red"]; //Генерируем кнопку 'Fish'
    $button1_2 = [["animals" => 'Other animals'], "!бот", "green"]; //Генерируем кнопку 'Other animals'

    $vk_api->sendButton($id, 'Спасибо', [ //Отправляем кнопки пользователю
    [$button1_1, $button1_2]
    ]);
	
  } else {
  
    $payload = json_decode($data->object->payload, True); //Получаем её payload
	
    $button2_1 = [null, "<< Назад", "red"]; // Код кнопки "<< Back"

    switch ($payload['animals']) { //Смотрим что в payload кнопках
      case 'Fish': //Если это Fish
        $button1_1 = [["animals" => 'Pink_salmon'], "!чат", "white"];
        $button1_2 = [["animals" => 'Goldfish'], "!сократить", "blue"];
        $button1_3 = [["animals" => 'Plotva'], "!ADM", "green"];
        $send = 1; //Флаг 1
        break;
      case 'Other animals': //Если это Other animals
        $button1_1 = [["animals" => 'Chicken'], "!привет", "white"];
        $button1_2 = [["animals" => 'Pig'], "!как дела?", "blue"];
        $button1_3 = [["animals" => 'Cow'], "!что нового?", "green"];
        $send = 1; //Флаг 1
        break;
      case 'Pink_salmon': //Если это Pink_salmon
        $vk_api->sendMessage($id, "Анонимный чат!"); //отправляем сообщение
        $vk_api->sendImage($id, $uploaddir."pink_salmon.jpg", "pink_salmon.jpg"); //отправляем картинку
        break;
      case 'Goldfish': //Если это Goldfish
        $vk_api->sendMessage($id, "Напиши !сократить и ссылку");
        $vk_api->sendImage($id, $uploaddir."goldfish.jpg", "goldfish.jpg");
        break;
      case 'Plotva': //Если это Plotva
        $vk_api->sendMessage($id, "vk.com/id_adm00103");
        $vk_api->sendImage($id, $uploaddir."plotva.jpg", "plotva.jpg");
        break;
      case 'Chicken': //Если это Chicken
        $vk_api->sendMessage($id, "Привет друг");
        $vk_api->sendImage($id, $uploaddir."chicken.jpg", "chicken.jpg");
        break;
      case 'Pig': //Если это Pig
        $vk_api->sendMessage($id, "Лучше чем у Илона Маска!");
        $vk_api->sendImage($id, $uploaddir."pepa.jpg", "pepa.jpg");
        break;
      case 'Cow': //Если это Cow
        $vk_api->sendMessage($id, "Новые кнопки у нас");
        $vk_api->sendImage($id, $uploaddir."cow.jpg", "cow.jpg");
        break;

      default:
        break;
    }

    if ($send) { //Если флаг = 1, отправить сформированные кнопки
      $vk_api->sendButton($id, 'ADM ответит', [ //Отправляем кнопки пользователю
        [$button1_1, $button1_2, $button1_3],
        [$button2_1]
      ]);
    }
  }
}

?>
