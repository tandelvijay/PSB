<?php



//Route::get('/', function () {
//    return view('home');
//});

Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');

Auth::routes();
Route::get('/home/{name}',function(){
    return redirect('/');
})->where('name','[A-Za-z]+'); //Where means(key , value
//)

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::resource('home','ClientController');
    Route::get('/home', 'ClientController@index')->name('home');
    Route::get('getClients', 'ClientController@getClients');
    Route::post('create-client', 'ClientController@createClient');
    Route::delete('delete-Client/{id}', 'ClientController@deleteClient');

    # Presentations
    Route::get('getUserPresentations', 'PresentationController@getUserPresentations');
    Route::get('getClientPresentations/{client_id}', 'PresentationController@getClientPresentations');
    Route::post('create-presentation', 'PresentationController@createPresentation');
    Route::delete('delete-presentation/{id}', 'PresentationController@deletePresentation');

});

