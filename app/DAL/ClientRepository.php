<?php

namespace App\DAL;
use App\Models\Client;
use App\DAL\CommonRepository as common;
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

class ClientRepository extends Repository
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
        return 'App\Models\Client';
    }

    /**
     * get list of all Clients
     * @return mixed
     */
    public function getClientList()
    {
        $userId = Auth::user()->id;
        try {
            $clientLists = DB::table('clients as c')
                ->where('c.user_id', $userId)
                ->orderBy('c.created_at', 'DESC')->get();

            $response = array($this->common->success => true);
            $response['data']['records'] = $clientLists;

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
    public function createClient($data)
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

            try {
                Db::beginTransaction();

                if ($id) {
                    // update client records
                    $message = 'Client updated successfully.';
                    $saveData['updated_at'] = Carbon::now();
                    parent::update($saveData, $id);

                } else {
                    $message = 'Client created successfully.';
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
    public function deleteClient($id)
    {

        try {
            parent::delete($id);
            $response = array($this->common->success => true, 'message' => 'Client deleted successfully.');

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
            'description' => "required"
        ],
        [
            'title.required' => 'Title is required.',
            'description.required' => 'Description is required.'
        ]);
    }

     /**
     * delete Document
     * @param $id
     * @return mixed
     */
    public function deleteDocument($id)
    {
        try {
            Db::beginTransaction();

            UserDocuments::find($id)->delete();
            Db::commit();
            $response = array($this->common->success => true, 'message' => 'Document deleted successfully.');

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
}
