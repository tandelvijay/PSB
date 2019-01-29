<?php

namespace App\DAL;
use App\Models\user_data;
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
use Illuminate\Database\Eloquent\Model;

class SignDocumentRepository extends Repository
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
        return 'App\Models\user_data';
    }

    /**
     * save asset class
     * @param $data
     * @return mixed
     */
    public function createUser($data)
    {
       $userId = Auth::user()->id;
        try {
            Db::beginTransaction();

            $validator = $this->validateCreate($data);

            //VALIDATION FUNCTION
            if ($validator->fails()) {
                $response = array($this->common->success => false, 'error' => ['statusCode' => 103, 'message' => 'Validation errors in your request.', 'errorDescription' => $validator->errors()]);

            } else {
//                $saveData['first_name'] = trim($data['first_name']);
//                $saveData['last_name'] = trim($data['last_name']);
//                $saveData['email'] = trim($data['email']);
//                $saveData['password'] = trim($data['password']);
                $saveData['file'] = trim($data['file']);
//                $saveData['gender'] = trim($data['gender']);
//                $saveData['hobby'] = implode(',', $data['hobby']);
                $saveData['created_at'] = Carbon::now();
                $insertedUser = parent::create($saveData);
                dd($saveData);
                $insertedUser->save();
                $message = 'User created successfully.';

                DB::commit();

                $response = array($this->common->success => true, 'message' => $message);
            }

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
        return Response::json($response);
    }

    public function validateCreate($data)
    {
        /* $id = 0;
         if($data['id']){
             $id = $data['id'];
         }*/
        return  Validator::make($data,[
            //'name' => "required|unique:asset_class,name,$id"
            'file' => "required"
//            'first_name' => "required",
//            'last_name' => "required",
//            'email' => "required|email",
//            'password' => "required",
//            'gender' => "required"
        ],
            [
                'file.required' => 'file is required.',
//                'first_name.required' => 'first name is required.',
//                'last_name.required' => 'last name is required.',
//                'email.required' => 'Email is required.',
//                'password.required' => 'Please Enter password.',
//                'gender.required' => 'Please select gender.'
                // 'name.unique'  => 'Asset class is already exists. Please try another.'
            ]);
    }

    public function validateFileUpload($data,$type='')
    {
        $rule = ['file' => "required|mimes:doc,docx,pdf"];
        if($type =='pic'){
            $rule = ['file' => 'required|mimes:jpeg,jpg,png'];
        }
        return Validator::make($data,$rule,[
            'file.required' => 'File is required to upload.',
            'file.mimes'  => 'File type not supported.'
        ]);
        /*return  Validator::make($data,[
            'file' => "required|mimes:doc,docx,pdf"
        ],
            [
                'file.required' => 'File is required to upload.',
                'file.mimes'  => 'File type not supported.'
            ]);*/
    }

}
