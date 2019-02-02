<?php

namespace App\Http\Controllers;
use App\DAL\PresentationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PresentationController extends Controller
{

    private $presentation;
    public function __construct(PresentationRepository $presentationRepository)
    {
        $this->presentation = $presentationRepository;
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


    public function getUserPresentations()
    {
        return $this->presentation->getUserClientWisePresentationList();
    }

    public function getClientPresentations($client_id)
    {
        return $this->presentation->getClientPresentationList($client_id);
    }

    public function createPresentation(Request $request)
    {
        return $this->presentation->createPresentation($request->all());
    }

    public function deletePresentation($id)
    {
        return $this->presentation->deletePresentation($id);
    }

}
