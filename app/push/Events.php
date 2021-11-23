<?php
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);
namespace app\push;
use GatewayWorker\Lib\Gateway;
use Workerman\Worker;
use think\worker\Application;

/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events
{

    public static $user = [];
    public static $uuid = [];
    /**
     * onWorkerStart 事件回调
     * 当businessWorker进程启动时触发。每个进程生命周期内都只会触发一次
     *
     * @access public
     * @param  \Workerman\Worker    $businessWorker
     * @return void
     */
    public static function onWorkerStart(Worker $businessWorker)
    {
        $app = new Application;
        $app->initialize();
    }

    /**
     * onConnect 事件回调
     * 当客户端连接上gateway进程时(TCP三次握手完毕时)触发
     *
     * @access public
     * @param  int       $client_id
     * @return void
     */
    public static function onConnect($client_id)
    {
        //Gateway::sendToCurrentClient(json_encode(["cliend_id"=>$client_id]));
    }

    /**
     * onWebSocketConnect 事件回调
     * 当客户端连接上gateway完成websocket握手时触发
     *
     * @access public
     * @param  integer  $client_id 断开连接的客户端client_id
     * @param  mixed    $data
     * @return void
     */
    public static function onWebSocketConnect($client_id, $data)
    {
        var_export($data);
    }

    /**
     * onMessage 事件回调
     * 当客户端发来数据(Gateway进程收到数据)后触发
     *
     * @access public
     * @param  int       $client_id
     * @param  mixed     $message
     * @return void
     */
   public static function onMessage($client_id, $message)
   {
        // 向所有人发送 
//        Gateway::sendToAll("$client_id said $message\r\n");

       /*监听事件，需要把客户端发来的json转为数组*/
       $data = json_decode($message, true);
       switch ($data['type']) {

           //当有用户上线时
           case 'reg':
               //绑定uid 用于数据分发
               Gateway::bindUid($client_id, $data['content']['uid']);
               self::$user[$data['content']['uid']] = $client_id;
               self::$uuid[$data['content']['uid']] = $data['content']['uid'];

               //给当前客户端 发送当前在线人数，以及当前在线人的资料
               $reg_data['uuser'] = self::$uuid;
               $reg_data['num'] = count(self::$user);
               $reg_data['type'] = "reguser";
               Gateway::sendToClient($client_id, json_encode($reg_data));

               //将当前在线用户数量，和新上线用户的资料发给所有人 但把排除自己，否则会出现重复好友
               $all_data['type'] = "addList";
               $all_data['content'] = $data['content'];
               $all_data['content']['type'] = 'friend';
               $all_data['content']['groupid'] = 2;
               $all_data['num'] = count(self::$user);
               Gateway::sendToAll(json_encode($all_data), '', $client_id);
               break;


           case 'chatMessage':
               //处理聊天事件
               $msg['username'] = $data['content']['mine']['username'];
               $msg['avatar'] = $data['content']['mine']['avatar'];
               $msg['id'] = $data['content']['mine']['id'];
               $msg['content'] = $data['content']['mine']['content'];
               $msg['type'] = $data['content']['to']['type'];
               $chatMessage['type'] = 'getMessage';
               $chatMessage['content'] = $msg;

               //处理单聊
               if ($data['content']['to']['type'] == 'friend') {

                   if (isset(self::$uuid[$data['content']['to']['id']])) {
                       Gateway::sendToUid(self::$uuid[$data['content']['to']['id']], json_encode($chatMessage));
                   } else {
                       //处理离线消息
                       $noonline['type'] = 'noonline';
                       Gateway::sendToClient($client_id, json_encode($noonline));
                   }
               } else {
                   //处理群聊
                   $chatMessage['content']['id'] = $data['content']['to']['id'];
                   Gateway::sendToAll(json_encode($chatMessage), '', $client_id);
               }
               break;
       }

   }

    /**
     * onClose 事件回调 当用户断开连接时触发的方法
     *
     * @param  integer $client_id 断开连接的客户端client_id
     * @return void
     */
   public static function onClose($client_id)
   {
       // 向所有人发送 
       GateWay::sendToAll("$client_id logout\r\n");

       //有用户离线时触发 并推送给全部用户
       $data['type'] = "out";
       $data['id'] = array_search($client_id, self::$user);
       unset(self::$user[$data['id']]);
       unset(self::$uuid[$data['id']]);
       $data['num'] = count(self::$user);
       Gateway::sendToAll(json_encode($data));

   }


    /**
     * onWorkerStop 事件回调
     * 当businessWorker进程退出时触发。每个进程生命周期内都只会触发一次。
     *
     * @param  \Workerman\Worker    $businessWorker
     * @return void
     */
    public static function onWorkerStop(Worker $businessWorker)
    {
        echo "WorkerStop\n";
    }
}
