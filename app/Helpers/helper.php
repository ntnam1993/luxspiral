<?php

function checkDateActive($schedule)
{
    if ( $schedule <= date("Y-m-d H:i") ) return 'checked';
    return '';
}

function getDateFromDateTime($dateTime)
{
    $d = new DateTime($dateTime);
    return $d->format('Y-m-d');
}

function orderDisplayFirst()
{
    echo '<span class="fa fa-arrow-circle-down fa-3x"></span>';
}
function orderDisplayLast()
{
    echo '<span class="fa fa-arrow-circle-up fa-3x"></span>';
}

function orderDisplay()
{
    echo '<span class="fa fa-arrow-circle-up fa-3x"></span><span class="fa fa-arrow-circle-down fa-3x"></span>';
}

function getTimeFromDateTime($dateTime)
{
    $d = new DateTime($dateTime);
    return $d->format('H:i');
}

function getValueSearch($titleSeach)
{
    if (isset($_GET[$titleSeach])) return $_GET[$titleSeach];
    return '';
}

function getFileMp3Name($url)
{
    $fileNameSend = explode('/', $url);
    return array_pop($fileNameSend);
}

function formartTime($time)
{
    $myDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $time);
    $formatDate = $myDateTime->format('Y-m-d H:i');
    return $formatDate;
}

function renderListAnswer($datas)
{
    $count = count($datas);
    if ($count > 0) {
        $html = '';
        foreach ($datas as $data) {
            $routeEdit = route("admin.delivery.answer.edit", $data->id);
            $routeDel  = route("admin.delivery.answer.delete");
            $html .= '
                <tr>
                    <td>'.$data->title.'</td>
                    <td>
                        <a href="'.$routeEdit.'" class="btn btn-success btn-sm">詳細</a>
                        <a class="show-modal-delete btn btn-danger btn-sm" data-url="'.$routeDel.'" data-id="'.$data->id.'" data-title="アラート" data-question="メッセージを削除してもよろしいですか" data-yes="削除する" data-no="キャンセル">削除</a>
                    </td>
                </tr>
            ';
        }
        return $html;
    }
    return trans('message.no-data');
}

function renderListDelivery($listDeliveries)
{
    $count = count($listDeliveries);
    if ($count > 0) {
        $html = '';
        foreach ($listDeliveries as $listDeliverie) {
            $routeEdit = route("admin.delivery.send.edit", $listDeliverie->id);
            $routeDel  = route("admin.delivery.send.delete");
            $routeDetail  = route('admin.delivery.send.detail', $listDeliverie->id);
            $html .= '
                <tr>
                    <td><input type="checkbox" '.checkDateActive($listDeliverie->schedule).' disabled></td>
                    <td>'.formartTime($listDeliverie->schedule).'</td>
                    <td>'.$listDeliverie->title.'</td>
                    <td>'.formartTime($listDeliverie->created_at).'</td>';

            if( $listDeliverie->schedule < date("Y-m-d H:i:s")){
                $html  .= '<td>
                            <a href="'.$routeDetail.'" class="btn btn-primary">詳細</a>
                        </td>';
            }else {
                $html .= '<td>
                            <a href="'.$routeEdit.'" class="btn btn-success btn-edit">編集</a>
                            <a class="show-modal-delete btn btn-danger" data-url="'.$routeDel.'" data-id="{{ $delivery->id }}" data-title="アラート" data-question="メッセージを削除してもよろしいですか" data-yes="削除する" data-no="キャンセル">削除</a>
                        </td>';
            }
            $html .= '</tr>';
        }
        return $html;
    }
    return trans('message.no-data');
}

function sendiOSNotification($device_token, $message = null, $extraData = array(), $silent = false)
{
    // Provide the Host Information.
    if (env('NOTIFICATION_IOS_SANDBOX', '')) {
        $tHost = 'gateway.sandbox.push.apple.com';
    } else {
        $tHost = 'gateway.push.apple.com';
    }

    $device_tokens = !(is_array($device_token) && count($device_token)) ? [$device_token] : $device_token;

    $tPort = 2195;
    // Provide the Certificate and Key Data.
    if ( env('TYPE_SERVER','develop') == 'production' ) {
        $tCert = public_path().'/'.env('NOTIFICATION_IOS_CRT_FILE_PRODUCTION', '');
    }elseif( env('TYPE_SERVER','develop') == 'staging' ){
        $tCert = public_path().'/'.env('NOTIFICATION_IOS_CRT_FILE_STAGING', '');
    }else{
        $tCert = public_path().'/'.env('NOTIFICATION_IOS_CRT_FILE_DEVELOP', '');
    }

    $tPassphrase = env('NOTIFICATION_IOS_PASS_PHRASE', '');

    // Create the message content that is to be sent to the device.
    $tBody['aps'] = array(
        'alert'       => $silent ? '' : [
            "title" => isset($message->title) ? $message->title : "もうすぐ遊助から着信が、、?!",
            "body"  => isset($message->description) ? $message->description : "有料コースの方はもうすぐ着信があるかも?!お楽しみに！"
        ],
        'sound'       => 'default',
        'content-available' => 1,
    );

    // Encode the body to JSON.
    $tBody = json_encode($tBody);

    // Create the Socket Stream.
    $tContext = stream_context_create();
    stream_context_set_option($tContext, 'ssl', 'local_cert', $tCert);

    // Remove this line if you would like to enter the Private Key Passphrase manually.
    // stream_context_set_option($tContext, 'ssl', 'passphrase', $tPassphrase);
    // Open the Connection to the APNS Server.
    $tSocket = stream_socket_client('ssl://' . $tHost . ':' . $tPort, $error, $errstr, 60, 4, $tContext);

    // Check if we were able to open a socket.
    if (!$tSocket)
        exit ("APNS Connection Failed: $error $errstr" . PHP_EOL);

    $tResult[]  = array();
    $tokenFails = [];
    foreach ($device_tokens as $device_Token) {
        // Build the binary notification
        $pattern = "/[0-9a-f]{64}/";
        preg_match($pattern, $device_Token , $match);
        if( isset($match[0]) && $match[0] == $device_Token ) {
            try {
                $msg = chr(0) . chr(0) . chr(32) . pack('H*', $device_Token) . pack('n', strlen($tBody)) . $tBody;
                $result = fwrite($tSocket, $msg);
                $tResult[] = $result;
                Illuminate\Support\Facades\Log::info('send push to '.$device_Token);
            }
            catch (Exception $ex) {
                $tokenFails[] = [
                    'device_token_fail' => $device_Token,
                    'created_at'        => date('Y-m-d H:i:s'),
                    'updated_at'        => date('Y-m-d H:i:s')
                ];
                Illuminate\Support\Facades\Log::info('send push error , device token is '.$device_Token.' messge '.$ex->getMessage());
            }
        } else {
            Illuminate\Support\Facades\Log::info('token fail '.$device_Token);
        }
    }
    if (count($tokenFails) > 0) \App\Models\DeviceTokenFail::insert($tokenFails);
    // Close the Connection to the Server.
    fclose($tSocket);
    return 'Message ios successfully delivered';
}

function sendAndroidNotification($device_token,$message = 'test push ok')
{
    $device_tokens = !(is_array($device_token) && count($device_token)) ? [$device_token] : $device_token;
    $fire_base_server_key = getenv('FIRE_BASE_SERVER_KEY');
    $client = new GuzzleHttp\Client([
        'headers' => [
            'Content-Type' => 'application/json',
            'Authorization'=> "key=$fire_base_server_key"
        ]
    ]);
    \Illuminate\Support\Facades\Log::info('push android to '.json_encode($device_token));
    $r = $client->post('https://fcm.googleapis.com/fcm/send',
        [
            'body' => json_encode(
                [
                    'registration_ids' => $device_tokens,
                    'data' => [
                        "title" => isset($message->title) ? $message->title : 'もうすぐ遊助から着信が、、?!',
                        'message' => isset($message->description) ? $message->description : '有料コースの方はもうすぐ着信があるかも?!お楽しみに！'
                    ]
                ]
            )
        ]
    );
    return ($r->getStatusCode() == '200') ? 'Message ios successfully delivered' : 'Message ios error delivered';
}

/**
 * Form call 050
 * @return mixed
 */
function getTwilioForm() {
    return env('TWILIO_FORM', "+815031842715");
}
function sleep050(){
    sleep(env('SLEEP_050', 60));
}

function convertHTML($data)
{
    $html = '';
    if (count($data) > 0) {
        foreach ($data as $key => $value) {
            $fontAwesome = '';
            if (count($data) > 1) {
                if ($key == 0){
                    $fontAwesome = '<span class="fa fa-arrow-circle-down fa-3x"></span>';
                } elseif($key == ( count($data) - 1)) {
                    $fontAwesome = '<span class="fa fa-arrow-circle-up fa-3x"></span>';
                } else{
                    $fontAwesome = '<span class="fa fa-arrow-circle-up fa-3x"></span><span class="fa fa-arrow-circle-down fa-3x"></span>';
                }
            }else{
                $fontAwesome = '<span class="fa fa-ban fa-3x"></span>';
            }
            $html .= "
            <tr>
                <td class='dont-break-out'>".$value->title."</td>
                <td class='dont-break-out white-space'>".$value->content."</td>
                <td class='text-center displayOrder dont-break-out' data-id='".$value->id."'>".
                $fontAwesome
                ."<td class='dont-break-out'>
                    <a href='".route('admin.faq.edit',$value->id)."' class='btn btn-success btn-sm btn-edit'>編集</a>
                    <a class='show-modal-delete btn btn-danger btn-sm' data-url='".route('admin.faq.destroy',$value->id)."' data-title='アラート' data-question='こちらの質問を削除してもよろしいですか' data-yes='削除する' data-no='キャンセル'>削除</a>
                </td>
            </tr>
            ";
        }
        return $html;
    }
    return '<tr>
                <td colspan="4">'.trans("message.no-data").'</td>
            </tr>';
}

/***********************
 * Function 050
 */
function getLimit050() {
    return env('MAX_CALL_050', 60);
}

function getUrlCall050( $url ) {
    return env('APP_URL', '') . "/dataxmltwilio?delivery=$url";
}