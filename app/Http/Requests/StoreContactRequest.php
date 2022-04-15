<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'uuid' => 'required|uuid',
            'prenom' => 'required|max:80',
            'nom' => 'required|max:80',
            'ville' => 'required|max:100',
            'telephone' => 'required|max:40',
            'email' => 'required|max:100|email',
            'adresse' => 'required',
            'commentaire' => 'required',
            'code_postal' => 'required|max:15',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'uuid' => strip_tags($this->uuid),
            'prenom' => strip_tags($this->prenom),
            'nom' => strip_tags($this->nom),
            'ville' => strip_tags($this->ville),
            'telephone' => strip_tags($this->telephone),
            'email' => strip_tags($this->email),
            'adresse' => strip_tags($this->adresse),
            'commentaire' => strip_tags($this->commentaire),
            'code_postal' => strip_tags($this->code_postal),
        ]);
    }

    public function attributes()
    {
        return [
            'prenom' => 'Prénom',
            'nom' => 'Nom',
            'ville' => 'Ville',
            'telephone' => 'Téléphone',
            'email' => 'Email',
            'adresse' => 'Adresse',
            'commentaire' => 'Commentaire',
            'code_postal' => 'Code Postal',
        ];
    }
}
