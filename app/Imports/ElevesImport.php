<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Session;
use App\Models\Enseignants;
use App\Models\Student;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;



class ElevesImport implements ToCollection, WithHeadingRow

{


    public function collection(Collection $rows)

    {

        $sessions = Session::first();
        $libelleSession = $sessions['libelle_sess'];
        $CodeEtab = $sessions['codeEtab_sess'];

        foreach ($rows as $row) {




            $user = User::create([

                'nom' => $row['nom'],
                'prenom' => $row['prenom'],
                'email' => $row['email'],
                'datenais' => $row['datenaiss'],
                'photo' => $row['photo'],
                'lieunais' => $row['lieunaiss'],
                'genre' => $row['genre'],
                'login' => $row['login'],
                'password' => bcrypt($row['password']),
                'type' => 'Eleve'
            ]);


            $Student =  Student::create([

                'user_id' => $user->id,
                'parent_id' => $row['parentlien'],
                'classe_id' => $row['classelien'],
                'nom' => $row['nom'],
                'prenom' => $row['prenom'],
                'matricule' => $row['matricule'],
                'dateNaiss' => $row['datenaiss'],
                'lieuNaiss' =>  $row['lieunaiss'],
                'sexe' => $row['genre'],
                'email' => $row['email'],
                'nationalite' => $row['email'],
                'doublant' => $row['doublant'],
                'codeEtab' => $CodeEtab,
                'session' => $libelleSession,

                // 'codeEtab'=>$row['codeetab'],
                // 'session'=>$row['session']

            ]);
        }
    }
}
