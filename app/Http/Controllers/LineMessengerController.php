<?php

namespace App\Http\Controllers;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
use App\Models\User;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use Illuminate\Http\Request;
use App\Models\Task;
use LINE\LINEBot\TemplateActionBuilder\DatetimePickerTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class LineMessengerController extends Controller
{
    public function webhook(Request $request)
    {
        // LINEBOTSDKの設定
        $http_client = new CurlHTTPClient(config('services.line.channel_token'));
        $bot = new LINEBot($http_client, ['channelSecret' => config('services.line.messenger_secret')]);
        //メッセージ取得
        $input = $request->all();
        //リプライトークン
        $reply_token = $input["events"][0]['replyToken'];
        //データタイプ(messageかpostback)
        $receive_type = $request['events'][0]['type'];
        //条件分岐
        if($receive_type == "message"){
            $episode = $input["events"][0]['message']['text'];
            $userId=$request['events'][0]['source']['userId'];
            //DB保存
            Task::create([
                'line_id' => $userId,
                'task' => $episode
            ]);
            // userIdがあるユーザーを検索
            $user=User::where('line_id', $userId)->first();
            // もし見つからない場合は、データベースに保存
            if($user==NULL) {
                $profile=$bot->getProfile($userId)->getJSONDecodedBody();
                $user=new User();
                $user->provider='line';
                $user->line_id=$userId;
                $user->name=$profile['displayName'];
                $user->save();
            }
            $date_time = new DatetimePickerTemplateActionBuilder('日付を選択', 'storeId=12345', 'datetime');
            $no_button = new PostbackTemplateActionBuilder('キャンセル', 'button=0');
            $actions = [$date_time,$no_button];
            $button = new ButtonTemplateBuilder('期限はいつですか？', $episode, '', $actions);
            $button_message = new TemplateMessageBuilder('日付選択', $button);
            //日時指定返す
            $reply=$bot->replyMessage($reply_token, $button_message);
        }
        if($receive_type == "postback"){
            Log::info($request->all());
            $data = $input["events"][0]["postback"]["data"];
            $update_column = Task::latest()->first();
            if($data == "button=0"){
                Task::where('id',$update_column->id)->delete();
                $reply_message = "タスクを削除しました！";
                $reply = $bot->replyText($reply_token,$reply_message);
            }
            else{
                $limit_date = $input["events"][0]["postback"]["params"]["datetime"];
                Log::info($limit_date);
                $time = date('Y-m-d H:i:s',strtotime($limit_date));
                //LINEのuserid取得
                $user_line_id = $input["events"][0]["source"]["userId"];
                $userid = User::where('line_id', $user_line_id)->first();
                $update = [
                    'deadline' =>$limit_date,
                    'user_id' =>$userid->id,
                ];
                Task::where('id',$update_column->id)->update($update);
                $reply_message = "タスクを追加しました！";
                $reply = $bot->replyText($reply_token,$reply_message);
            }
        }
    }
}
