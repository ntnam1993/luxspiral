<?php

namespace App\Http\Controllers\Admin;

use App\Models\Call;
use App\Models\Delivery;
use App\Models\User;
use App\Repositories\Contracts\CallRepository;
use App\Repositories\Contracts\SoundRepository;
use Dotenv\Exception\ValidationException;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Http\Requests;
use DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Repositories\Contracts\DeliveryRepository;
use App\Validators\DeliveryValidator;
use App\Repositories\Contracts\AnswerRepository;
use Twilio\Rest\Client;
use GuzzleHttp\Client as GuzzClient;
use Twilio\Twiml;

/**
 * Class DeliveriesController.
 *
 * @package namespace App\Http\Controllers\Admin;
 */
class DeliveriesController extends BaseController
{
    /**
     * @var DeliveryRepository
     */
    protected $repository;

    /**
     * @var AnswerRepository
     */
    protected $answerRepository;

    /**
     * @var AnswerRepository
     */
    protected $soundRepository;

    /**
     * @var DeliveryValidator
     */
    protected $validator;

    protected $callRepository;

    /**
     * DeliveriesController constructor.
     *
     * @param DeliveryRepository $repository
     * @param DeliveryValidator $validator
     */
    public function __construct(DeliveryRepository $repository, DeliveryValidator $validator, AnswerRepository $answerRepository, SoundRepository $soundRepository, CallRepository $callRepository)
    {
        $this->repository       = $repository;
        $this->answerRepository = $answerRepository;
        $this->validator        = $validator;
        $this->soundRepository  = $soundRepository;
        $this->callRepository   = $callRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = $request->titleSearch ? $request->titleSearch : null;
        $listDeliveries = $this->repository->showList($title);
        $listAns = $this->answerRepository->showList($title);
        if ($request->ajax()) {
            $data = [];
            if (isset($request->listAns)) {
                $paginate= "{$listAns->appends(['listAns' => $listAns->currentPage()])->links()}";
                $listAns = renderListAnswer($listAns);

                $data =[
                    'listAns' => $listAns,
                    'paginate'=> $paginate
                ];
            } else {
                $paginate= "{$listDeliveries->appends(['listDeli' => $listDeliveries->currentPage()])->links()}";
                $listDeliveries = renderListDelivery($listDeliveries);

                $data =[
                    'listDeliveries' => $listDeliveries,
                    'paginate'       => $paginate
                ];
            }
            return $data;
        }
        return view('admin.delivery.list', compact('listDeliveries', 'listAns'));
    }

    public function create()
    {
        return view("admin.delivery.send.add");
    }

    /**
     * @return DeliveryValidator
     */
    public function store(Request $request)
    {
        try {
            $credential = $request->only('title','date','time','filemsgsend', 'filemsgnoans', 'test_flg');
            $credential['schedule'] = $request->date . " " . $request->time.":00";
            unset($credential['date']);
            unset($credential['time']);
            $this->validator->with($credential)->passesOrFail(DeliveryValidator::RULE_CREATE);

            $newNameFileSend     = date('YmdHis')."message.".$credential['filemsgsend']->getClientOriginalExtension();
            $newNameFileNoAnswer = date('YmdHis')."noanswer.".$credential['filemsgnoans']->getClientOriginalExtension();
            $nameFileSend        = $credential['filemsgsend']->getClientOriginalName();
            $nameFileNoAnswer    = $credential['filemsgnoans']->getClientOriginalName();
            $urlSendS3           = $this->uploadMP3ToS3($newNameFileSend, $credential['filemsgsend']->getPathName());
            $urlNoAnswerS3       = $this->uploadMP3ToS3($newNameFileNoAnswer, $credential['filemsgnoans']->getPathName());

            $idMsgSend    = $this->soundRepository->createMsgGetId($urlSendS3, $nameFileSend);
            $idMsgNoAnswer= $this->soundRepository->createMsgGetId($urlNoAnswerS3, $nameFileNoAnswer);

            unset($credential['filemsgsend']);
            unset($credential['filemsgnoans']);

            $credential['sound_id']           = $idMsgSend;
            $credential['sound_id_no_answer'] = $idMsgNoAnswer;

            $this->repository->create($credential);
            return redirect()->route("admin.delivery.list")->with('success',trans('message.create-delivery-success'));
        } catch (ValidatorException $e) {
            return redirect()->route("admin.delivery.list")->with('error',trans('message.create-delivery-error'));
        }
    }

    public function delete(Request $request)
    {
        try{
            $credential = $request->only('id');
            $this->validator->with($credential)->passesOrFail(DeliveryValidator::RULE_DELETE);
            $delivery = $this->repository->findWithRelation($credential['id']);
            if ($delivery->schedule < date('Y-m-d H:i:s')) return redirect()->route("admin.delivery.list")->with('error',trans('message.id-invalid'));
            $this->repository->delete($credential['id']);
            $this->removePathS3(getFileMp3Name($delivery->soundMessage->url));
            $this->removePathS3(getFileMp3Name($delivery->soundNoAnswer->url));
            $this->soundRepository->delete($delivery->soundMessage->id);
            $this->soundRepository->delete($delivery->soundNoAnswer->id);
            return redirect()->route("admin.delivery.list")->with('success',trans('message.delete-answer-success'));
        }catch(ValidationException $e) {
            return redirect()->route("admin.delivery.list")->with('error',trans('message.delete-answer-error'));
        }
    }

    public function edit($id)
    {
        try {
            $credential['id'] = $id;
            $this->validator->with($credential)->passesOrFail(DeliveryValidator::RULE_EDIT);
            $msgDeli = $this->repository->findWithRelation($id);
            if ($msgDeli->schedule < date('Y-m-d H:i:s')) return redirect()->route("admin.delivery.list")->with('error',trans('message.id-invalid'));
            return view('admin.delivery.send.edit', compact('msgDeli'));
        } catch (ValidatorException $e) {
            return redirect()->route('admin.delivery.list')->with('error',trans('message.id-invalid'));
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $delivery   = $this->repository->findWithRelation($id);
            $credential = $request->only('title', 'date', 'time', 'filemsgsend', 'filemsgnoans');
            $credential['title']        = $request->has('title') ? $request->title : '';
            $credential['schedule']     = $request->date . " " . $request->time;
            $credential['filemsgsend']  = $request->has('filemsgsend') ? $request->filemsgsend : '';
            $credential['filemsgnoans'] = $request->has('filemsgnoans') ? $request->filemsgnoans : '';
            $this->validator->with($credential)->passesOrFail(DeliveryValidator::RULE_UPDATE);

            if ($credential['filemsgsend'] != '') {
                $newNameMsgSend = date('YmdHis')."message.".$credential['filemsgsend']->getClientOriginalExtension();
                $nameMsgSend    = $credential['filemsgsend']->getClientOriginalName();
                $this->removePathS3(getFileMp3Name($delivery->soundMessage->url));
                $urlSend        = $this->uploadMP3ToS3($newNameMsgSend,$credential['filemsgsend']->getPathName());
                $this->soundRepository->update(['url' => $urlSend , 'name' => $nameMsgSend], $delivery->soundMessage->id);
            }
            if ($credential['filemsgnoans'] != '') {
                $newNameMsgSend   = date('YmdHis')."noanswer.".$credential['filemsgnoans']->getClientOriginalExtension();
                $nameMsgNoAns     = $credential['filemsgnoans']->getClientOriginalName();
                $this->removePathS3(getFileMp3Name($delivery->soundNoAnswer->url));
                $urlNoAns      = $this->uploadMP3ToS3($newNameMsgSend,$credential['filemsgnoans']->getPathName());
                $this->soundRepository->update(['url' => $urlNoAns, 'name' => $nameMsgNoAns], $delivery->soundNoAnswer->id);
            }
            unset($credential['filemsgsend']);
            unset($credential['filemsgnoans']);
            unset($credential['date']);
            unset($credential['time']);

            $this->repository->update($credential, $id);
            return redirect()->route('admin.delivery.list')->with('success',trans('message.edit-delivery-success'));
        } catch (ValidatorException $e) {
            return redirect()->route('admin.delivery.list')->with('error',trans('message.edit-delivery-error'));
        }
    }

    public function showDetail($id)
    {
        $msgDeli = $this->repository->findWithRelation($id);
        $fineNameMsgAns =  getFileMp3Name($msgDeli->soundMessage->name);
        $fineNameMsgNoAns =  getFileMp3Name($msgDeli->soundNoAnswer->name);

        return view('admin.delivery.send.detail', compact('msgDeli', 'fineNameMsgAns', 'fineNameMsgNoAns'));
    }

    public function deleteMP3($key)
    {
        return json_encode(['key' => $key]);
    }

    /**
     *
     */
    public function deliveryCall() {
        $listDeliveries = $this->repository->showList('');
        return view('admin.delivery.call', compact('listDeliveries'));
    }

    /**
     * @param $delivery
     */
    public function deliveryCallPhone($delivery) {

        $data = ['data' => '', 'url' => ''];

        $deliveries = Delivery::find($delivery);
        Log::info('Begin Call 050 CMS [>>]');
        if ($deliveries->test_flg != null) {
            $listUsers = $this->repository->getUsersTestByDelivery($delivery);
            if( count($listUsers) ) {

                $delivery = DB::select("SELECT url FROM sound WHERE id IN (SELECT sound_id FROM delivery WHERE id = $delivery)");

                $play = isset($delivery[0]->url) ? $delivery[0]->url : '';
                $url = env('APP_URL', '') . "/dataxmltwilio?delivery=$play";

                $data = ['data' => $listUsers, 'url' => $url];
            }
        } else {
            $listUsers = $this->repository->getUsersByDelivery($delivery);

            if( count($listUsers) ) {

                $delivery = DB::select("SELECT url FROM sound WHERE id IN (SELECT sound_id FROM delivery WHERE id = $delivery)");

                $play = isset($delivery[0]->url) ? $delivery[0]->url : '';
                $url = env('APP_URL', '') . "/dataxmltwilio?delivery=$play";

                $data = ['data' => $listUsers, 'url' => $url];
            }
        }

        echo json_encode($data);
        die();
    }


    public function dataXMLTwi() {
        $play = isset($_GET['delivery']) ? $_GET['delivery'] : '';
        $twiml = new Twiml();

        $twiml->play($play);

        $response = response()->make($twiml, 200);
        $response->header('Content-Type', 'text/xml');
        return $response;
    }

    /**
     * @param Request $request
     * @throws \Twilio\Exceptions\ConfigurationException
     */
    public function deliveryCallList( Request $request ) {
        $url   = $request->has('url') ? $request->url : '';
        $tels  = $request->has('tels') ? $request->tels : [];
        if( is_array($tels) && count($tels) ) {
            $client   = new Client(getenv('API_KEY'), getenv('API_KEY_SECRET'), getenv('ACCOUNT_SID'));
            foreach ( $tels as $tel ) {
                try {
                    Log::info('Call 050 CMS ' . $tel);
                    $call = $client->calls->create(
                        $tel, env('TWILIO_FORM', "+815031842715"),
                        array("method" => "GET", "url" => $url)
                    );
                    if( isset($call) ) {
                        echo 'Call To Phone ' . $tel . ' => ' . $call->sid . "<br>";
                    }

                } catch (\Exception $e) {
                    Log::info("Call To Phone $tel Error: ". $e->getMessage());
                }
            }
        }
        sleep(60);
        die();
    }

    /**
     * Show screen
     */
    public function callvip(){

        $tel = isset($_REQUEST['From']) ? str_replace(' ', '+', $_REQUEST['From']) : '';

        $isVip = User::where('tel', $tel)
                        ->where('verify_status', User::STATUS_VERIFIED)
                        ->orderBy('created_at', 'DESC')
                        ->first();

        if( count($isVip) && $isVip->expired >= date('Y-m-d') ) {
            $user_id = $isVip->id;
            $call = $this->callRepository->findCallNewestWithInNoReceive($user_id);
            $list_answer = $this->answerRepository->getListWithRelation();
            if(!empty($call) && count($call) > 0) {
                if ($call->status == Call::STATUS_ACCEPT_CALL) {
                    if (count($list_answer) > 0) {
                        $random = rand(1, count($list_answer)) - 1;
                        $answer_id = $list_answer[$random]['id'];
                        $url = $this->answerRepository->findWithRelation($answer_id)->sound->url ? $this->answerRepository->findWithRelation($answer_id)->sound->url : '';
                    } else {
                        $url = false;
                    }
                } else {
                    $delivery = $this->repository->findWithRelation($call->delivery_id);
                    $url = $delivery->soundNoAnswer->url;
                }
            } else {
                if (count($list_answer) > 0) {
                    $random = rand(1, count($list_answer)) - 1;
                    $answer_id = $list_answer[$random]['id'];
                    $url = $this->answerRepository->findWithRelation($answer_id)->sound->url ? $this->answerRepository->findWithRelation($answer_id)->sound->url : '';
                } else {
                    $url = false;
                }
            }
            header("Content-Type: text/xml");
            ?><?xml version="1.0" encoding="UTF-8"?>
            <Response>
                <?php if( $url ) { ?>
                    <Play><?php echo $url;?></Play>
                <?php } ?>
            </Response>
            <?php
        } else {
            header("Content-Type: text/xml");
            ?><?xml version="1.0" encoding="UTF-8"?>
            <Response></Response>
            <?php
        }
        die();
    }

    /**
     * Show screen
     */
    public function callarchive()
    {
        $tel = isset($_REQUEST['From']) ? str_replace(' ', '+', $_REQUEST['From']) : '';
        $isVip = User::where('tel', $tel)
            ->where('verify_status', User::STATUS_VERIFIED)
            ->orderBy('created_at', 'DESC')
            ->first();

        $dataSound = collect(Delivery::join('sound', 'sound.id', '=', 'delivery.sound_id')
                            ->where('delivery.updated_at', '<=', date('Y-m-d H:i:s'))
                            ->whereNull('delivery.test_flg')
                            ->orderBy('delivery.id', 'ASC')
                            ->limit(9)->get())->toArray();

        if( count($isVip) && $isVip->expired >= date('Y-m-d') ) {
            header("Content-Type: text/xml");
            ?><?xml version="1.0" encoding="UTF-8"?>
            <Response>
                <?PHP if (empty($_POST["Digits"])) { ?>
                    <Say language="ja-jp" voice="alice">お聞きになりたいアーカイブの番号を押してください。現在 1 から <?php echo count($dataSound);?> まであります</Say>
                    <Gather numDigits="1" timeout="30" />
                <?PHP } elseif ($_POST["Digits"] && $_POST["Digits"] >= 1 && $_POST["Digits"] <= count($dataSound)) { ?>
                    <Play><?php echo $dataSound[$_POST["Digits"] - 1]['url'];?></Play>
                <?PHP } ?>
            </Response>
            <?php
        } else {
            header("Content-Type: text/xml");
            ?><?xml version="1.0" encoding="UTF-8"?>
            <Response></Response>
            <?php
        }
        die();
    }

    public function call050($delivery)
    {
        Artisan::call('call:050', [
            'delivery' => $delivery
        ]);
        return 'ok';
    }
}
