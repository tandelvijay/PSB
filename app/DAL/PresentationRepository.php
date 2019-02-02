<?php

namespace App\DAL;
use App\Models\Client;
use App\DAL\CommonRepository as common;
use App\Models\Presentation;
use App\User;
use Auth;
use Illuminate\Container\Container as App;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;

class PresentationRepository extends Repository
{
    private $common;

    /**
     * CONSTRUCTOR
     * @param App $app
     */
    public function __construct(App $app)
    {

        parent::__construct($app);
        $this->common = new common();
    }

    /**
     * @return string
     * to get model for repository use
     */
    function model()
    {
        return 'App\Models\Presentation';
    }


    /**
     * get user's client wise presentation lists
     * @return mixed
     */
    public function getUserClientWisePresentationList()
    {
        $userId = Auth::user()->id;
        try {
            $presentationLists = Presentation::with('client')->where('user_id', $userId)->get();

            $response = array($this->common->success => true);
            $response['data']['records'] = $presentationLists;

        } catch (\Exception $e) {
            $response = $this->common->getErrorMessage($e->getMessage());
        }

        return Response::json($response);
    }

    /**
     * get user's clientId presentation list
     * @return mixed
     */
    public function getClientPresentationList($client_id)
    {
        $userId = Auth::user()->id;
        try {
            $presentationLists = Presentation::with('client')->where('user_id', $userId)->where('client_id', $client_id)->get();

            $response = array($this->common->success => true);
            $response['data']['records'] = $presentationLists;

        } catch (\Exception $e) {
            $response = $this->common->getErrorMessage($e->getMessage());
        }

        return Response::json($response);
    }


    /**
     * add edit update client
     * @param $data
     * @return mixed
     */
    public function createPresentation($data)
    {
        $userId = Auth::user()->id;

        $validator = $this->validateCreate($data);

        //VALIDATION FUNCTION
        if ($validator->fails()) {
            $response = array($this->common->success => false, 'error' => ['statusCode' => 103, 'message' => 'Validation errors in your request.', 'errorDescription' => $validator->errors()]);

        } else {
            $id = (int)$data['id'];
            $saveData['user_id'] = $userId;
            $saveData['title'] = trim($data['title']);
            $saveData['description'] = trim($data['description']);
            $saveData['client_id'] = trim($data['client_id']);
            $saveData['is_sale_presentation'] = (int)$data['is_sales_presentation'];

            # Presentation details - as json with key value pair
            if(isset($data['presentation_data']))
                $saveData['presentation_data'] = trim($data['presentation_data']);

            try {
                Db::beginTransaction();

                if ($id) {
                    // update client records
                    $message = 'Presentation updated successfully.';
                    $saveData['updated_at'] = Carbon::now();
                    parent::update($saveData, $id);

                } else {
                    $message = 'Presentation created successfully.';
                    $saveData['created_at'] = Carbon::now();
                    parent::create($saveData);

                }
                DB::commit();
                $response = array($this->common->success => true, 'message' => $message);

            } catch (\Exception $e) {
                DB::rollBack();
                $response = array(
                    $this->common->success => false,
                    'error' => [
                        'code' => $e->getCode(),
                        'message' => $e->getMessage()
                    ]
                );
            }
        }

        return Response::json($response);
    }

    /**
     * delete assets
     * @param $id
     * @return mixed
     */
    public function deletePresentation($id)
    {

        try {
            // Presentation::find($id)->delete();
            parent::delete($id);
            //parent::delete($id);
            $response = array($this->common->success => true, 'message' => 'Presentation deleted successfully.');

        } catch (\Exception $e) {
            $response = array(
                $this->common->success => false,
                'error' => [
                    'code' => $e->getCode(),
                    'message' => $e->getMessage()
                ]
            );
        }

        return Response::json($response);

    }

    /**
     *  CREATE VALIDATION FUNCTION
     * @param $data
     * @return mixed
     */
    public function validateCreate($data)
    {
        return  Validator::make($data,[
            'title' => "required",
            'description' => "required",
            'client_id' => "required"
        ],
            [
                'title.required' => 'Title is required.',
                'description.required' => 'Description is required.',
                'client_id.required' => 'Client is required.'
            ]);
    }
}
