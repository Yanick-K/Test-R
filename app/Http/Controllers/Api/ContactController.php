<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Contact::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\StoreContactRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreContactRequest $request)
    {
       $data = $request->validated();

        $contact = Contact::create([
            'first_name' => $data['prenom'],
            'last_name' => $data['nom'],
            'email' => $data['email'],
            'address' => $data['adresse'],
            'comment' => $data['commentaire'],
            'city' => $data['ville'],
            'tel' => $data['telephone'],
            'postal_code' => $data['code_postal'],
            'uuid' => $data['uuid']
        ]);
        return $contact;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\UpdateContactRequest;  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateContactRequest $request, $id)
    {
        $data = $request->validated();
        $contact = Contact::find($id);
        $contact->update([
            'first_name' => $data['prenom'],
            'last_name' => $data['nom'],
            'email' => $data['email'],
            'address' => $data['adresse'],
            'comment' => $data['commentaire'],
            'city' => $data['ville'],
            'tel' => $data['telephone'],
            'postal_code' => $data['code_postal'],
        ]);
        return $contact;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact = Contact::find($id);
        $contact->delete();
    }
}
