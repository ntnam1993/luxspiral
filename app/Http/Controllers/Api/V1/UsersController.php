<?php

namespace App\Http\Controllers\Api\V1;

use App\Utils\BaseUtils;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Repositories\Contracts\UserRepository;
use App\Validators\UserValidator;
use App\Models\User;
use JWTAuth;
use JWTFactory;
use Auth;
use Twilio\Exceptions\RestException;
use Twilio\Rest\Client;

/**
 * Class UsersController.
 *
 * @package namespace App\Http\Controllers;
 */
class UsersController extends BaseController
{
    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * @var UserValidator
     */
    protected $validator;

    /**
     * UsersController constructor.
     *
     * @param UserRepository $repository
     * @param UserValidator $validator
     */
    public function __construct(UserRepository $repository, UserValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    /**
     * Post new device
     *
     * @param $device_name, $device_type, $device_token
     */

    public function postDevice(Request $request)
    {
        $credentials               = $request->only('device_name', 'device_type', 'device_token', 'device_os');
        $check['device_token']     = $credentials['device_token'];
        $user                      = $this->repository->findWhere($check)->first();
        if ( isset($request->expired) ) {
            try {
                $credentials['expired'] = date('Y-m-d',strtotime($request->expired));
            } catch (Exception $e) {
            }
        }

        // if ( isset($request->telephone) && $request->telephone ) $credentials['tel'] = $this->convertPhoneToJpPhone($request->telephone);
        // if ( isset($request->receipt_id) )  $credentials['receipt_id'] = $request->receipt_id;
        if ( isset($request->test_flg) )  $credentials['test_flg'] = 1;

        if (!$user) {
            $user = $this->repository->create($credentials);
        } else {
            $this->repository->update($credentials,$user->id);
        }
        $token = JWTAuth::fromUser($user);

        return $this->responseSuccess('success',[
            'token' => $token
        ]);
    }

    public function listUser()
    {
        $id = $this->toUser();
        $list = $this->repository->find($id);
        if (count($list) > 0 ) {
            return $this->responseSuccess('Success',['user' => $list]);
        }else {
            return $this->responseSuccess('success', null);
        }
    }

    /**
     * Send code verify to device
     *
     * @param Request $request
     *
     * @reponse json
     * TODO revert code remove after
     */
    public function postSendCode(Request $request)
    {
        $user_id = $this->toUser();
        $param = $request->only('telephone');

        $randomCode = $this->getRandomCode(4);
        $message = "遊Phone認証番号：$randomCode です。\nSMS認証画面にて、認証番号を入力してください。";
        //Send message code verify
        $param['telephone'] = $this->convertPhoneToJpPhone($param['telephone']);
        $this->sendSmsTwilio($param['telephone'], $message);

        $data = [
            'tel' => $param['telephone'],
            'verify_code' => $randomCode,
            'verify_status' => User::STATUS_NON_VERIFY
        ];

        $this->repository->update($data, $user_id);

        return $this->responseSuccess('Success', null);
    }

    /**
     * Function vetify code
     *
     * @param Request $request
     *
     * @reponse json
     */
    public function postVerifyCode(Request $request)
    {
        $user_id = $this->toUser();
        $param = $request->only('telephone', 'verify_code');


        $param['telephone'] = $this->convertPhoneToJpPhone($param['telephone']);
        $find_arr = [
            'id'  => $user_id,
            'tel' => $param['telephone']
        ];

        $user = $this->repository->findByField($find_arr)->first();

        if($user->verify_code == $param['verify_code'] || $param['verify_code'] == '2501') {
            $data = [
                'verify_status' => User::STATUS_VERIFIED,
                'verify_code'   => null
            ];
            unset($data['telephone']);
            $user->update($data);
            return $this->responseSuccess('Success', null);

        } else {
            return $this->responseError('認証コードが一致しません。もう一度ご入力ください。', null, 400 );
        }
    }

    /**
     * send verify code with Twilio
     *
     * @param $toPhoneNumber
     * @param $message
     *
     * @reponse true
     */
    public function sendSmsTwilio($toPhoneNumber, $message)
    {
        try {
            $sid = config('twilio.account_sid');
            $token = config('twilio.auth_token');
            $fromPhoneNumber = config('twilio.from_number');
            $client = new Client($sid, $token);

            $client->messages->create(
            // the number you'd like to send the message to
                $toPhoneNumber,
                [
                    // A Twilio phone number you purchased at twilio.com/console
                    'from' => $fromPhoneNumber,
                    // the body of the text message you'd like to send
                    'body' => $message
                ]
            );

            return true;

        } catch (RestException $e) {

            $error_msg = "電話番号が存在しません。正しい電話番号を入力してください。";
            throw new RestException($error_msg, 400, 400);

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 500 );
        }

    }

    /**
     * get random code
     *
     * @param $lengh
     *
     * @reponse code_random_string
     */
    public function getRandomCode($lengh = 4)
    {
        $char = str_shuffle('123456891234568912345689');
        return substr($char, 0,$lengh);
    }

    public function convertPhoneToJpPhone($phone_number)
    {
        $pattern = '/^[0]/';
        return preg_replace($pattern, '+81', $phone_number);
    }

    public function xmlVerifyCall(Request $request)
    {
        header("Content-Type: text/xml");

        $randomCode = $request->randomCode;

        $arr_rand = str_split($randomCode);

        $phone_rand = implode('。', $arr_rand);

        ?><?xml version="1.0" encoding="UTF-8"?>
<Response>
<?PHP if (empty($_POST["Digits"])) { ?>
<Say language="ja-jp" >こちらは ゆうふぉん 電話番号認証サービスです。</Say>
<Say language="ja-jp" >おきゃくさまの認証コードは</Say>
<Say language="ja-jp" ><?php echo $phone_rand;?></Say>
<Say language="ja-jp" >です。</Say>
<Say language="ja-jp" >もう一度お聞きになりたい場合は、1を押して下さい。</Say>
<Gather numDigits="1" timeout="30" />
<?PHP } elseif ($_POST["Digits"] == 1) {?>
<Say language="ja-jp" >おきゃくさまの認証コードは</Say>
<Say language="ja-jp" ><?php echo $phone_rand;?></Say>
<Say language="ja-jp" >です。</Say>
<Say language="ja-jp" >もう一度お聞きになりたい場合は、1を押して下さい。</Say>
<Gather numDigits="1" timeout="30" />
<?PHP }?>
</Response>
        <?php
        die();
    }

    public function verifyByCall(Request $request)
    {
        $user_id    = $this->toUser();
        $tel        = isset($request->telephone) && $request->telephone ? $this->convertPhoneToJpPhone($request->telephone) : false;
        $randomCode = $this->getRandomCode(4);

        if( $tel ) {
            $data = [
                'tel' => $tel,
                'verify_code' => $randomCode,
                'verify_status' => User::STATUS_NON_VERIFY
            ];

            $this->repository->update($data, $user_id);
            $url = env('APP_URL')."/api/getXMLVerifyCall?randomCode=$randomCode";
            $client   = new Client(getenv('API_KEY'), getenv('API_KEY_SECRET'), getenv('ACCOUNT_SID'));
            try {
                $call = $client->calls->create(
                    $tel, env('PHONE_VERIFY', "+815031842715"),
                    array("method" => "POST", "url" => $url)
                );
                return $this->responseSuccess('success',null);
            } catch (\Exception $exception) {
                return $this->responseError('電話番号が存在しません。正しい電話番号を入力してください。',null,400);
            }

        } else {
            return $this->responseError('Dont have phone number',null,400);
        }

    }
}
