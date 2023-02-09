<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\User;
use App\Models\Classe;
use App\Models\Parents;
use App\Models\Session;
use App\Models\Student;
use App\Models\Versements;
use Illuminate\Http\Request;
use App\Models\Etablissement;
use PHPUnit\Framework\MockObject\Builder\Stub;
use Illuminate\Foundation\Auth\User as AuthUser;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     *
     */

    public function updateinscripEleve(Request $request)

    {





        if ($request->imageLogo == '') {

            if ($request->sexe == 'M') {

                $request->imageLogo = 'elevedefaultgarcon.jpg';
            } else {

                $request->imageLogo = 'elevedefaultfille.jpg';
            }
        }

        $this->validate($request, [

            'id'=>'required',
            'nom' => 'required',
            'prenom' => 'required',
            'matricule' => 'required',
            'sexe' => 'required',
            'classe' => 'required',
            //'login' => 'required',
            //'pass' => 'required',
            'dateNaiss' => 'required',
            'lieuNaiss' => 'required',
            'sexe' => 'required',

        ]);


            //  Mettre a jour l'enfant dans la table user

            $idElev = $request->user['id'];

            $dataUser =  User::where('id', $idElev)->update([

                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'datenais' => $request->dateNaiss,
                'lieunais' => $request->lieuNaiss,
                'genre' => $request->sexe,
                // 'email' => $request->email,
                // 'login' => $request->login,
                // 'password' => bcrypt($request->pass),
                //'type' => 'Eleve',
                'photo' => $request->imageLogo

            ]);



            // id de sa classe

            $idclasse = $request->classe;  // Car c'est l'id qui arrive dans la value cote  vuejs

            $dataStudents  =  Student::where('id', $request->id)->update([

                //'user_id' => $idElev,
                'parent_id' => $request->parent['id'],
                'classe_id' => $idclasse,
                'nom' => $request->nom,
                'prenom' => $request->prenom,
                'dateNaiss' => $request->dateNaiss,
                'lieuNaiss' => $request->lieuNaiss,
                'sexe' => $request->sexe,
                'email' => $request->email,
                'nationalite' => $request->natio,
                // 'codeEtab' => $codeEtab,
                // 'session' => $sessionEncour,
                // 'matricule' => $request->matricule,
                'doublant' => $request->redoubl

            ]);

            // Mettre a jour ces paiement dans la nouvelle classe


            $paid = Versements::where('student_id', $request->id)->count();



            if($paid>0) {


            $dataStudentsVersement  =  Versements::where('student_id', $request->id)->update([

                'classe_id' => $idclasse,

            ]);

            }



    }


     public function  delateEleve(Request $request){

        $this->validate($request, [

            'id' => 'required'

        ]);

     // je supprime dans la table user et ca supprimerra dans la table eleve automatiquement

        User::where('id', $request->user_id)->delete();


     }



    public function rechercher (Request $request)

    {

        $codeEtab =$request->EtabInfos[0]['codeEtab'];


        $datas = Student::with('user','classe','parent')->where('nom', 'LIKE', '%' . $request->nom . '%')
        ->orWhere('matricule', 'LIKE', '%' . $request->nom . '%')->where('codeEtab',$codeEtab)->get();

        return response()->json($datas);
    }

    public function getStudentByTeacherForAppel(Request $request)
    {

        $datas = Student::with('user')->where('classe_id', $request->classeName)->orderBy('nom', 'asc')->get();

        return response()->json($datas);
    }

    public function getEleveAndParentInfos(Request $request)
    {

        // Recuperer l'id de la classe

        $idEleve = $request->id;



        // Recuperer le code de l'ecole

        $codeEtab  = $request->codeEtab;

        // Recuperer la session en cour

        $sessionEncour  = $request->session;

        // Recuperer les eleves d'une classes

        // $EleveData = Student::with('user)->where('codeEtab', $codeEtab)->where('session', $sessionEncour)->where('classe_id', $idclasse)->orderBy('id', 'desc')->get();

        $EleveData = Student::with('parent', 'user', 'classe')->where('codeEtab', $codeEtab)->where('session', $sessionEncour)->where('id', $idEleve)->orderBy('nom', 'asc')->get();

        return response()->json($EleveData[0]);
    }

    public function getEleveAndParentInfosTeacher(Request $request)
    {

        // Recuperer l'id de la classe

        $idEleve = $request->id;



        // Recuperer le code de l'ecole

        $codeEtab  = $request->codeEtab;

        // Recuperer la session en cour

        $sessionEncour  = $request->session;

        // Recuperer les eleves d'une classes

        // $EleveData = Student::with('user)->where('codeEtab', $codeEtab)->where('session', $sessionEncour)->where('classe_id', $idclasse)->orderBy('id', 'desc')->get();

        $EleveData = Student::with('parent', 'user', 'classe')->where('codeEtab', $codeEtab)->where('session', $sessionEncour)->where('id', $idEleve)->orderBy('nom', 'asc')->get();

        return response()->json($EleveData[0]);
    }

    public function getEleveclasseById(Request $request)
    {


        $classes  = Classe::where('id', $request->idClasse)->first();



        // Recuperer le code de l'ecole

        $codeEtab  = $classes->codeEtabClasse;

        // Recuperer la session en cour

        $sessionEncour  = $classes->sessionClasse;

        // Recuperer les eleves d'une classes, on couple avec la table user pour pouvoir recuperer les photos

        // $EleveData = Student::with('user)->where('codeEtab', $codeEtab)->where('session', $sessionEncour)->where('classe_id', $idclasse)->orderBy('id', 'desc')->get();

        $EleveData = Student::where('codeEtab', $codeEtab)->where('session', $sessionEncour)->where('classe_id', $request->idClasse)->orderBy('nom', 'asc')->orderBy('prenom', 'asc')->get();

        return response()->json($EleveData);
    }

    public function getEleveclasse(Request $request)

    {

        // Recuperer l'id de la classe

        $idclasse = $request->classeId['id'];

        // Recuperer le code de l'ecole

        $codeEtab  = $request->classeId['codeEtabClasse'];

        // Recuperer la session en cour

        $sessionEncour  = $request->classeId['sessionClasse'];

        // Recuperer les eleves d'une classes, on couple avec la table user pour pouvoir recuperer les photos

        // $EleveData = Student::with('user)->where('codeEtab', $codeEtab)->where('session', $sessionEncour)->where('classe_id', $idclasse)->orderBy('id', 'desc')->get();

        $EleveData = Student::with('user','parent')->where('codeEtab', $codeEtab)->where('session', $sessionEncour)->where('classe_id', $idclasse)->orderBy('nom', 'asc')->orderBy('prenom', 'asc')->get();



        // foreach ($EleveData as $data) {


        //     $bus= Versements::where('classe_id', $idclasse)->where('student_id', $data->id)->where('motif', 'bus')

        //         ->where('codeEtab', $codeEtab)->where('session', $sessionEncour)->sum('montantverser');


        //     $cantine  = Versements::where('classe_id', $idclasse)->where('student_id', $data->id)->where('motif', 'cantine')

        //         ->where('codeEtab', $codeEtab)->where('session',  $sessionEncour)->sum('montantverser');


        //         $data['bus'] = $bus;

        //         $data['cantine'] = $cantine;




        // }


        return response()->json($EleveData);
    }



    public function SearchParent(Request $request)

    {

        // dd($request->telSeach);

        $codeEtab = $request['EcoleInfos'][0]['codeEtab'];

        // Recuperons les datas de la session en cour

        $sessiondata = Session::where('codeEtab_sess', $codeEtab)->where('encours_sess', 1)->orderBy('id', 'desc')->get();

        // Recuperons le libelle  de la session en cour

        $sessionEncour = $sessiondata[0]['libelle_sess'];

        // Recuperons le parent grace a son telephone

        $parent = Parents::where('codeEtab', $codeEtab)->where('session', $sessionEncour)->where('telParent', $request->telSeach)->get();

        return response()->json($parent);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function getEleveInfos(Request $request)
    {

        // RECUPERONS LES INFOS DE LA TABLE ENSEIGNANT


        $Ecole = Student::where('user_id', $request->id)->first();


        // id enfant   dans la table eleve

        $idEleve = $Ecole->id;

        // Prenons le codeEtab

        $codeEtab = $Ecole->codeEtab;

        //  Prenons  la session

        $sessionEncour = $Ecole->session;

        $Datas = Student::with('parent', 'Classe', 'user')->where('id', $idEleve)
            ->where('codeEtab', $codeEtab)
            ->where('session', $sessionEncour)->orderBy('id', 'desc')->first();

        return  response()->json($Datas);
    }
    public function inscripEleve(Request $request)

    {



        // Recuperer le code etablissement

        $codeEtab = $request['EcoleInfos'][0]['codeEtab'];

        // Recuperons les datas de la session en cour

        $sessiondata = Session::where('codeEtab_sess', $codeEtab)->where('encours_sess', 1)->orderBy('id', 'desc')->get();

        // Recuperons le libelle  de la session en cour

        $sessionEncour = $sessiondata[0]['libelle_sess'];

        if ($request->imageLogo == '') {

            if ($request->sexe == 'M') {

                $request->imageLogo = 'elevedefaultgarcon.jpg';
            } else {

                $request->imageLogo = 'elevedefaultfille.jpg';
            }
        }

        $this->validate($request, [

            'nom' => 'required',
            'Prenom' => 'required',
            // 'matricule' => 'required',
            'sexe' => 'required',
            'classe' => 'required',
            'login' => 'required',
            'pass' => 'required',
            'dateNaiss' => 'required',
            'lieuNaiss' => 'required',
            'sexe' => 'required',

        ]);


        // Enregistrer dans la table user

        //      if (User::where('nom',$request->nom)->where('prenom', $request->PrenomAdmin)
        //      ->where('telephone',$request->telAdmin)->exists()
        //  )

        //  {
        //      return response()->json([
        //          'msg' => 'Existe deja',

        //      ], 400);
        //  }

        if (User::where('login', $request->login)->exists()) {
            return response()->json([
                'msg' => 'Ce login existe deja',

            ], 401);
        } else {

            //  Inscrivez l'enfant dans la table user


            $dataUser =  User::Create([

                'nom' => $request->nom,
                'prenom' => $request->Prenom,
                'datenais' => $request->dateNaiss,
                'lieunais' => $request->lieuNaiss,
                'genre' => $request->sexe,
                // 'email' => $request->email,
                'login' => $request->login,
                'password' => bcrypt($request->pass),
                'type' => 'Eleve',
                'photo' => $request->imageLogo

            ]);


            // id de l'eleve nouvelement inscrit dans user

            $idElev = $dataUser->id;



            // id de sa classe

            $idclasse = $request->classe;  // Car c'est l'id qui arrive dans la value cote  vuejs


            // Enregistrer dans la table students

            // Matricule automatique


            $code =  substr($sessionEncour, 2, 2); // Je recupere de 22 de 2022 , en 2023bca sera 23 et ainsi de suite


            if ($idElev < 10) {

                $matricule = $code . '000' . $idElev;
            }

            if ($idElev >= 10 && $idElev < 100) {

                $matricule = $code . '00' . $idElev;
            }

            if ($idElev >= 100 && $idElev < 1000) {

                $matricule = $code . '0' . $idElev;
            }

            if ($idElev > 1000 && $idElev < 10000) {

                $matricule = $code . $idElev;
            }



            $mat = $matricule;

            // dd($matricule);

            $dataStudents  =  Student::Create([
                'user_id' => $idElev,
                'parent_id' => $request->idParent,
                'classe_id' => $idclasse,
                'nom' => $request->nom,
                'prenom' => $request->Prenom,
                'dateNaiss' => $request->dateNaiss,
                'lieuNaiss' => $request->lieuNaiss,
                'sexe' => $request->sexe,
                'email' => $request->email,
                'nationalite' => $request->natio,
                'codeEtab' => $codeEtab,
                'session' => $sessionEncour,
                'matricule' => $mat,
                'doublant' => $request->redoubl

            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getStudentBySg (Request $request)
    {
        $EleveData = Student::where('classe_id', $request->classeName)->orderBy('nom', 'asc')->orderBy('prenom', 'asc')->get();

        return  response()->json($EleveData);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
