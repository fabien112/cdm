<?php

namespace App\Http\Controllers;

use App\Models\Notes;
use App\Models\Classe;
use App\Models\Session;
use App\Models\Student;
use App\Models\Moyennes;
use App\Models\Evaluations;
use App\Models\Matieres;
use App\Models\MoyenneTrimestres;
use App\Models\NotesTrimestres;
use Illuminate\Http\Request;

class MoyennesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateMoyenne(Request $request)

    {

        $idClasse = $request->idClasse;
        $idEvaluation = $request->libelleEvaluation;
        $codeEtab = $request->EtabInfos[0]['codeEtab'];
        $session = Session::where('codeEtab_sess', $codeEtab)->first('libelle_sess');
        $sessionEncour = $session->libelle_sess;

        $classeData = Student::where('classe_id', $idClasse)->where('session', $sessionEncour)
            ->where('codeEtab', $codeEtab)->get();




        foreach ($classeData  as $key => $eleve) {

            $datas[$eleve->id] = Notes::with('matiere')
                ->where('evaluation_id', $idEvaluation)
                ->where('classe_id', $idClasse)
                ->where('student_id', $eleve->id)
                ->where('codeEtab', $codeEtab)
                ->where('session', $sessionEncour)->get();

            $sommeNoteCoef = 0;
            $sommeCoef = 0;


            foreach ($datas[$eleve->id] as $data) {


                $sommeNoteCoef = $sommeNoteCoef + ($data->valeur * $data->matiere->coef);

                $sommeCoef = $sommeCoef + $data->matiere->coef;
            }

            if ($sommeCoef == 0) {
                return response()->json([
                    'msg' => 'Pas de note pour cette sequence',

                ], 403);
            } else {

                $moyenne[$eleve->id] =  number_format($sommeNoteCoef / $sommeCoef, 2);



                // Inserrons la moyenne de chaue eleve dans la table moyenne

                if (Moyennes::where('evaluation_id', $idEvaluation)->where('student_id', $eleve->id)->exists()) {


                    $resp = Moyennes::where('evaluation_id', $idEvaluation)->where('student_id', $eleve->id)->update([

                        'evaluation_id' => $idEvaluation,
                        'classe_id' => $request->idClasse,
                        'student_id' => $eleve->id,
                        'valeur' =>  $moyenne[$eleve->id],
                        //'mention' => $metions,
                        'codeEtab' => $codeEtab,
                        'session' => $sessionEncour

                    ]);
                } else {

                    $resp = Moyennes::create([

                        'evaluation_id' => $idEvaluation,
                        'classe_id' => $request->idClasse,
                        'student_id' => $eleve->id,
                        'valeur' =>  $moyenne[$eleve->id],
                        //'mention' => $metions,
                        'codeEtab' => $codeEtab,
                        'session' => $sessionEncour

                    ]);
                }
            }
        }
    }



    public function updateNoteTrimestre(Request $request)

    {



        $idClasse = $request->classeName;
        $idTrimestre = $request->libelleEvaluation;
        $code = Classe::where('id', $idClasse)->first();
        $codeEtab = $code->codeEtabClasse;
        $session = $code->sessionClasse;

        // Recuperons les id des evaluations correspondant au trimestre dont on a l'id en haut

        $Evalutions = Evaluations::where('trimestre_id', $idTrimestre)->where('session', $session)
            ->where('codeEtab', $codeEtab)->get();


        NotesTrimestres::where('trimestre_id', $idTrimestre)->where('session', $session)
            ->where('codeEtab', $codeEtab)->where('classe_id', $idClasse)->delete();


        // Id premiere evaluation de ce trimestre



        if (count($Evalutions) != 2) {

            return response()->json([
                'msg' => 'Pas de note evaluation',

            ], 402);
        } else {

            $idEval1 = $Evalutions[0]->id;

            // Id seconde  evaluation de ce trimestre

            $idEval2 = $Evalutions[1]->id;

            // Verifions s'il ya les notes de deux sequences pour cette classe


            $Notesequen1 = Notes::with('matiere', 'student', 'evaluation', 'user')

                ->where('classe_id', $idClasse)
                ->where('codeEtab', $codeEtab)
                ->where('session', $session)
                ->where('evaluation_id', $idEval1)->orderBy('matiere_id')->get();

            $Notesequen2 = Notes::with('matiere', 'student', 'evaluation', 'user')

                ->where('classe_id', $idClasse)
                ->where('codeEtab', $codeEtab)
                ->where('session', $session)
                ->where('evaluation_id', $idEval2)->orderBy('matiere_id')->get();

            // Lorsque les notes des deux sequences n'ont pas ete saisie dans cette classe

            if ((count($Notesequen1) || count($Notesequen2)) == 0) {

                return response()->json([
                    'msg' => 'Pas de note pour cette sequence',

                ], 403);
            }

            // Lorsque les notes des deux sequences ont ete saisie dans cette classe

            else {


                $classeData = Student::with('user', 'classe')->where('classe_id', $idClasse)->where('session', $session)
                    ->where('codeEtab', $codeEtab)->get();


                    // dd( $classeData);

                foreach ($classeData   as $key => $data) {



                    $MoyenneSequence[$data->id] = Moyennes::where('student_id', $data->id)->get();

                    // dd($MoyenneSequence[$data->id]);


                    $sommeNoteCoef = 0;

                    $sommeCoef = 0;

                    $Note1[$data->id] = Notes::with('matiere', 'student', 'evaluation', 'user')

                        ->where('classe_id', $idClasse)
                        ->where('student_id', $data->id)
                        ->where('codeEtab', $codeEtab)
                        ->where('session', $session)
                        ->where('evaluation_id', $idEval1)->orderBy('matiere_id')->get();


                    $Note2[$data->id] = Notes::with('matiere', 'student', 'evaluation', 'user')

                        ->where('classe_id', $idClasse)
                        ->where('student_id', $data->id)
                        ->where('codeEtab', $codeEtab)
                        ->where('session', $session)
                        ->where('evaluation_id', $idEval2)->orderBy('matiere_id')->get();



                    // Je recupere la somme des coef de toutes les matieres dans cette classe


                    $sommeCoef = Matieres::where('classe_id', $idClasse)->get()->sum('coef');

                    //dd($sommeCoef);

                    foreach ($Note1[$data->id] as $key => $note1) {




                        $note2 = $Note2[$data->id][$key];


                        $noteMatiereTrimestre[$data->id] = ($note1->valeur + $note2->valeur) / 2;


                        //  dd('JE ne comprends pas') ;

                        // La note du trimestre dans une matiere



                        if ($MoyenneSequence[$data->id][0]->valeur == 0 || $MoyenneSequence[$data->id][1]->valeur == 0) {

                            //  sil a 0 dans un trimestre on ne divise pas ses notes par 2



                            $noteMatiereTrimestre[$data->id] = ($note1->valeur + $note2->valeur);
                        } else {

                            $noteMatiereTrimestre[$data->id] = ($note1->valeur + $note2->valeur) / 2;
                        }




                        // Ajoutons la note du trimestre dans la bd

                        // si le nombre de note dans la table NoteTrimestre est egale au nombre de note saisie dans
                        // les tables notes des sequence alors on a deja  rempli la table noteTrimestre et il faut juste la mettre a jour


                        if (count(NotesTrimestres::where('trimestre_id', $idTrimestre)->where('classe_id', $idClasse)->get()) == count($Notesequen2)) {

                            $resp = NotesTrimestres::where('trimestre_id', $idTrimestre)->update([

                                'valeur' =>  $noteMatiereTrimestre[$data->id],
                                // 'mention' => $metions,

                            ]);
                        } else {


                            $resp = NotesTrimestres::create([

                                'trimestre_id' => $idTrimestre,
                                'matiere_id' => $note1->matiere->id,
                                'classe_id' => $idClasse,
                                'user_id' => $note1->user->id,
                                'student_id' => $data->id,
                                'valeur' =>   $noteMatiereTrimestre[$data->id],
                                // 'mention' => $metions,
                                'cat_id' => $note1->cat_id,
                                'codeEtab' => $codeEtab,
                                'session' => $session

                            ]);


                            // Calculer la moyenne generale  du trimstre

                        }

                        $sommeNoteCoef = $sommeNoteCoef + ($noteMatiereTrimestre[$data->id] * $note1->matiere->coef);
                    }

                    // dd($sommeNoteCoef);

                    $moyenne[$data->id] =  number_format($sommeNoteCoef / $sommeCoef, 2);



                    //     // Inserrons la moyenne generale du trimestre  de chaue eleve dans la table moyenneTrimestre

                    if (MoyenneTrimestres::where('trimestre_id', $idTrimestre)->where('student_id', $data->id)->exists()) {

                        $resp = MoyenneTrimestres::where('trimestre_id', $idTrimestre)->where('student_id', $data->id)->update([

                            'trimestre_id' => $idTrimestre,
                            'classe_id' => $idClasse,
                            'student_id' => $data->id,
                            'valeur' =>  $moyenne[$data->id],
                            //'mention' => $metions,
                            'codeEtab' => $codeEtab,
                            'session' => $session

                        ]);
                    } else {

                        $resp = MoyenneTrimestres::create([

                            'trimestre_id' => $idTrimestre,
                            'classe_id' => $idClasse,
                            'student_id' => $data->id,
                            'valeur' => $moyenne[$data->id],
                            // 'mention' => $metions,
                            'codeEtab' => $codeEtab,
                            'session' => $session

                        ]);
                    }
                }
            }
        }
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
