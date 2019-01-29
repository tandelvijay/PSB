<?php

namespace App\Http\Controllers;
use App\DAL\ClientRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ClientController extends Controller
{

    private $client;
    public function __construct(ClientRepository $clientRepository)
    {
        $this->client = $clientRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    /**
     * get the list of clients
     * @return mixed
     */
    public function getClients()
    {
        return $this->client->getClientList();
    }

    public function createClient(Request $request)
    {
        return $this->client->createClient($request->all());
    }

    public function deleteClient($id)
    {
        return $this->client->deleteClient($id);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\psb  $psb
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id, psb $psb)
    {
        $response = [
            'status' => 'error',
            'message' => 'Unable to delete record. Please try again'
        ];

        if (psb::where('id',$id)->delete()) {
            $response = [
                'status' => 'success',
                'message' => 'Unable to delete record. Please try again'
            ];
        }
       return Response::json($response);
    }
}
