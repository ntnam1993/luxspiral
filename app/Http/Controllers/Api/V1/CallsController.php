<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\BaseController;
use App\Models\Answer;
use App\Models\Call;
use App\Models\Delivery;
use App\Models\User;
use App\Repositories\Contracts\AnswerRepository;
use App\Repositories\Contracts\DeliveryRepository;
use App\Repositories\Contracts\UserRepository;
use App\Utils\BaseUtils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\CallCreateRequest;
use App\Http\Requests\CallUpdateRequest;
use App\Repositories\Contracts\CallRepository;
use App\Validators\CallValidator;
use Twilio\Rest\Client;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VoiceGrant;

/**
 * Class CallsController.
 *
 * @package namespace App\Http\Controllers\Api\V1;
 */
class CallsController extends BaseController
{
    /**
     * @var CallRepository
     */
    protected $repository;

    /**
     * @var AnswerRepository
     */
    protected $answerRepository;

    /**
     * @var AnswerRepository
     */
    protected $deliveryRepository;

    /**
     * @var AnswerRepository
     */
    protected $userRepository;

    /**
     * @var CallValidator
     */
    protected $validator;

    /**
     * CallsController constructor.
     *
     * @param CallRepository $repository
     * @param CallValidator $validator
     */
    public function __construct(CallRepository $repository, CallValidator $validator, AnswerRepository $answerRepository, DeliveryRepository $deliveryRepository, UserRepository $userRepository)
    {
        $this->repository = $repository;
        $this->answerRepository = $answerRepository;
        $this->deliveryRepository = $deliveryRepository;
        $this->userRepository = $userRepository;
        $this->validator  = $validator;
    }

    /*
     *
     */
    public function placeCall(Request $request)
    {
        $callerId = 'client:quick_start';
        $client   = new Client(getenv('API_KEY'), getenv('API_KEY_SECRET'), getenv('ACCOUNT_SID'));

        $call  = NULL;
        $param = (object)[
            'to'         => $request->to,
            'type'       => $request->type,
            'descripton' => isset($request->message) ? $request->message : '',
            'url'        => $request->has('url_mp3') ? $request->url_mp3 : null
        ];
        if ($param->type == User::TYPE_IOS) $this->push($param);
        if (isset($param->to)) {
            $call = $client->calls->create(
                'client:'.md5($param->to), // Call this number
                $callerId,     // From a valid Twilio number
                array(
                    "method" => "GET",
                    'url' => $param->url ? $param->url : 'http://huuvien.website/public/despacito.mp3'
                )
            );
        } else {
            return $this->responseError('Device Token Miss', '', 400);
        }

        return $this->responseSuccess('success',['call_id'=>$call->sid]);
    }

    /*
     *
     */
    public function getTokenTwilio(Request $request)
    {
        // Use identity and room from query string if provided
        $identity = $request->identity ? $request->identity : getenv('APP_NAME');
        // Create access token, which we will serialize and send to the client
        $token = new AccessToken(getenv('ACCOUNT_SID'),
            getenv('API_KEY'),
            getenv('API_KEY_SECRET'),
            3600,
            $identity
        );

        // Grant access to Video
        $grant = new VoiceGrant();
        $grant->setOutgoingApplicationSid(getenv('APP_SID'));

        if ( isset($request->platform) && $request->platform == User::TYPE_ANDROID ) {
            $grant->setPushCredentialSid(getenv('PUSH_CREDENTIAL_SID'));
        }else{
            $grant->setPushCredentialSid(getenv('PUSH_CREDENTIAL_SID_IOS'));
        }

        $token->addGrant($grant);

        try {
            $user_id = $this->toUser();
            $status = $this->userRepository->find($user_id)->verify_status;
        } catch (\Exception $e) {
            $status = Call::DEFAULT_STATUS;
        }

        return $this->responseSuccess('success',['twilio_token' => $token->toJWT(),'verify_status' => $status]);

    }

    public function callHistory(Request $request)
    {
        $user_id = $this->toUser();
        $key_date = $request->key_date ? $request->key_date : BaseUtils::KEY_DATE_GET_ALL;

        if ($key_date == BaseUtils::KEY_DATE_GET_ALL) {
            $call = $this->repository->scopeQuery(function ($query) use ($user_id){
                return $query->where('user_id',$user_id);
                        //->where('status', '!=', Call::STATUS_NO_RECEIVE);
            })->all();
        }else{
            $call = $this->repository->findCallByField($key_date,$user_id);
        }
        if (!empty($call) && count($call) > 0) {
            // Format Status
            foreach ($call as $key=>$value) {
                if( isset($value['status']) && ($value['status'] == 3 || $value['status'] == 4) ) $call[$key]['status'] = 0;
            }
            return $this->responseSuccess('success',$call);
        }else{
            return $this->responseSuccess('success',null);
        }
    }

    public function getURL()
    {
        $user_id = $this->toUser();
        $call = $this->repository->findCallNewestWithInNoReceive($user_id);
        $list_answer = $this->answerRepository->getListWithRelation();

        if(!empty($call) && count($call) > 0) {
            if ($call->status == Call::STATUS_ACCEPT_CALL) {
                if (count($list_answer) > 0) {
                    $random = rand(1, count($list_answer)) - 1;
                    $answer_id = $list_answer[$random]['id'];
                    $url = $this->answerRepository->findWithRelation($answer_id)->sound->url ? $this->answerRepository->findWithRelation($answer_id)->sound->url : '';

                    return $this->responseSuccess('success', ['url' => $url, 'title' => $list_answer[$random]['title']]);
                } else {
                    return $this->responseError('no answer url', null, 400);

                }
            } else {
                $delivery = $this->deliveryRepository->findWithRelation($call->delivery_id);
                $url = $delivery->soundNoAnswer->url;
                $this->repository->update(['status' => Call::STATUS_ACCEPT_CALL], $call->id);

                return $this->responseSuccess('success', ['url' => $url, 'title' => $delivery->title]);
            }
        } else {
            if (count($list_answer) > 0) {
                $random = rand(1, count($list_answer)) - 1;
                $answer_id = $list_answer[$random]['id'];
                $url = $this->answerRepository->findWithRelation($answer_id)->sound->url ? $this->answerRepository->findWithRelation($answer_id)->sound->url : '';

                return $this->responseSuccess('success', ['url' => $url, 'title' => $list_answer[$random]['title']]);
            } else {
                return $this->responseError('no answer url', null, 400);

            }
        }
    }

    public function updateStatus()
    {
        $user_id = $this->toUser();
        $call = $this->repository->findCallNewestWithInNoReceive($user_id);
        if (count($call) > 0) {
            $this->repository->update(['status'=>Call::STATUS_ANSWER],$call->id);
            return $this->responseSuccess('success',null);
        }
        return $this->responseError('error',null,400);
    }

    public function updateStatusNewestCall(Request $request)
    {
        return $this->responseSuccess('success', null);
//        try {
//            $status = $request->has('status') ? $request->status : null;
//            if ( $status != null && in_array($status, Call::LIST_CALL_STATUS) ) {
//                $user_id = $this->toUser();
//                $checkStatus = collect(DB::select("SELECT tmp.id, call.status FROM (SELECT MAX(id) as id FROM `call` WHERE user_id = $user_id) AS tmp, `call` WHERE tmp.id=call.id"))->first();
//
//                if( isset($checkStatus->status) && $checkStatus->status == Call::STATUS_NO_RECEIVE ) {
//                    $id = $checkStatus->id;
//
//                    DB::update("UPDATE
//                              `call`
//                            SET status=$status
//                            WHERE id = $id");
//                }
//
//                return $this->responseSuccess('success', null);
//
//                //$call = $this->repository->findCallNewestWithInNoReceive($user_id);
//                //if ($call) {
//                    //$this->repository->update(['status' => $status], $call->id);
//                //}
//                //return $this->responseError('error', null, 400);
//            }
//            return $this->responseError('error', null, 400);
//        } catch (\Exception $e) {
//            \Log::warning('WARNING. An error occurred during the process update status');
//            \Log::info($e->getMessage());
//            return $this->responseError('error', null, 400);
//        }
    }

    /**
     * @param Request $request
     */
    public function statuscallback() {
        $callSid = isset($_REQUEST['CallSid']) ? $_REQUEST['CallSid'] : false;
        $status  = isset($_REQUEST['CallStatus']) ? $_REQUEST['CallStatus'] : false;
        $phone   = isset($_REQUEST['Called']) ? $_REQUEST['Called'] : false;

        if( $callSid && $status ) {

            Log::info('Update status ' . $callSid . ' Phone: ' . $phone .' status [>>] ' . $status);
            //$path = public_path('/callback/');
            //file_put_contents($path. 'request' . date('_Ymdhis') . '.txt', print_r($_REQUEST, true));

            switch ($status) {
//                case 'queued': $status = 4;break;
//                case 'ringing': $status = 4;break;
                case 'in-progress': $status = 1;break;
                case 'completed': $status = 1;break;
                case 'busy': $status = 3;break;
//                case 'failed': $status = 4;break;
//                case 'no-answer': $status = 4;break;
                default: $status = 0;
            }

            if( $status ) {
                Call::where('twilio_call_id', $callSid)
                    ->update(['status' => $status]);
            }
        }
    }
}
