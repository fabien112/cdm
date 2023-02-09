<template>
    <div>
        <div class="wrapper">
            <Header />
            <MenuLocal />


            <Modal v-model="EdetingModal" title="Modifier  une note ">
                <div class="row">

                </div> <br>
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label"> Note
                        </label>
                        <input type="number" class="form-control" v-model="datasEdit.note" />
                    </div> <br>
                    <!-- <div class="col-md-12">
                                    <label class="form-label"> Mention
                                    </label>
                                    <input type="text" class="form-control"  v-model="datasEdit.mention" />
                                </div> -->
                </div>



                <br />

                <div slot="footer">
                    <Button type="primary" size="large" long @click="Update()">Enregistrer</Button>
                </div>
            </Modal>

            <div class="content-wrapper" style="min-height: 653px; background-color:#FAFBFD">
                <div class="container-full">
                    <!-- Main content -->

                    <section class="content">

                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for>Sélectionner la classe</label>

                                    <select  class="form-control"
                                        v-model="datas.classeId">
                                        <option v-for="(data, i) in ClasseListes" :key="i" :value="data.id">{{
                                        data.libelleClasse
                                        }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for=""> Evaluation </label>

                                    <select v-model="
                                        datas.libelleEvaluation
                                    " class="form-control required">
                                        <option v-for="(data,
                                        i) in Evaluation" :key="i" :value="data.id">
                                            {{ data.libelle }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group"> <br>
                                    <button @click="afficher"
                                        class="waves-effect waves-light btn mb-5  btn btn-primary btn-block">
                                        Envoyer
                                    </button>
                                </div>
                            </div>

                        </div>
                        <br><br>
                        <div class="row">
                            <div class="col-4" v-for="(data,
                            i) in Notes" :key="i">
                                <div class="box">
                                    <div class="box-header bg-primary">
                                        <p class="box-title" style="margin:auto; font-size: 13px;">



                                            {{
                                                    data.nom
                                            }}

                                            {{
                                                    data.prenom
                                            }}


                                        </p>

                                    </div>

                                    <div class="box-body">
                                        <div class="table-responsive">
                                            <table class="table product-overview">
                                                <thead>
                                                    <tr>
                                                        <th> Matière </th>
                                                        <th> Note </th>
                                                        <th>  </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="( dat, j) in data.matiere" :key="j">

                                                        <td style="font-size:12px ;">

                                                            {{ dat.libelle }}

                                                        </td>
                                                        <td class="font-weight-600">

                                                            <button v-if="dat.note==NULL" class="btn btn-danger btn-xs">
                                                                    Vide

                                                            </button>

                                                            <button v-else  class="btn btn-primary btn-xs">
                                                                {{
                                                                    dat.note.valeur
                                                                }}

                                                        </button>
                                                        </td>

                                                        <td>

                                                            <span  class="btn btn-primary btn-xs"
                                                                @click="showEdetingModal(dat, i)" title="Modifier"> <i
                                                                    class="ti-pencil"></i>
                                                            </span>
                                                        </td>




                                                    </tr>



                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- /.content -->
                </div>
            </div>
        </div>
        <Chats />
    </div>
</template>

<script>
import Header from "../../headers/Header.vue";
import MenuLocal from "../../navs/MenuLocal.vue";
import Chats from "../../navs/Chats.vue";
import { mapState } from "vuex";
import {
    required,
    minLength,
    alpha,
    email,
    maxLength,
    sameAs
} from "vuelidate/lib/validators";
import { log } from "util";

export default {
    components: { Header, MenuLocal, Chats },
    data() {
        return {
            datas: {

                libelleEvaluation: '',
                classeId: ""
            },

            datasEdit: {
                note: "",
                mention: "",
                idNote: "",
            },
            delateItem: '',
            classeListe: [],
            EdetingModal: false,
            EtabInfos: [],
            parentEleveInfos: [],
            Evaluation: [],
            Notes: [],
            NotesPdf: [],
            ClasseListes:'',
            i: 1,

            dat: {
                matiere: {

                }
            }
        };
    },

    methods: {

        async Update() {

//console.log(this.delateItem.duree);

if (this.datasEdit.note == ""||this.datasEdit.note==null) {

    this.e("Saisir une note valide");

}

//  if (this.datasEdit.mention == "") {

//     this.e("Saisir une mention ");

// }

else {




    const response = await axios.post(
        "api/teacher/updateNote2",
        this.datasEdit
    );



    if (response.status === 200) {
        this.EdetingModal = false;

        const response2 = await this.callApi(
                "post",
                "api/locale/getBulletinEleve",
                this.datas
            );

            this.Notes = response2.data;

        this.s("Note modifiée correctement");



        //   this.$router.go();

    }


}



},

        showEdetingModal(data, i) {

            this.EdetingModal = true
            this.i = i;
            console.log(data)

            if(data.note == null) {

                this.datasEdit.note = ''
                this.datasEdit.idNote = ''
                data.Ideval = this.datas.libelleEvaluation

                this.datasEdit.reste = data

                // reste = matiere

            }

            else {


            this.datasEdit.note = data.note.valeur

            this.datasEdit.idNote = data.note.id



            }






        },

        async afficher() {

            if (this.datas.classeId == "") {
                return this.e("Selectionner une classe");
            }


            if (this.datas.libelleEvaluation == "") {
                return this.e("Selectionner une évaluation");
            }

            this.datas.parentEleveInfos = this.parentEleveInfos;


            const response2 = await this.callApi(
                "post",
                "api/locale/getBulletinEleve",
                this.datas
            );

            this.Notes = response2.data;

            console.log(this.Notes)


            if (this.Notes.note == "") {
                this.rempli = false;
            } else {
                this.rempli = true;
            }
        },




    },

    async mounted() {

        if (!localStorage.users) {

            this.$router.push('login');
        }
        // Recuperer les infos de cette classe  dans le storage. classdId  contient toutes les classes et leur eleves respectivement

        if (localStorage.parentEleveInfos) {

            this.parentEleveInfos = JSON.parse(localStorage.getItem("parentEleveInfos"));
        }

        // if (localStorage.classeId) {
        //     this.datas.classeId = JSON.parse(localStorage.getItem("classeId"));

        //     this.datas.classeId = this.datas.classeId.id
        // }


        if (localStorage.EtabInfos) {
            this.EtabInfos = JSON.parse(localStorage.getItem("EtabInfos"));
        }

        const response4 = await this.callApi(
            "post",
            "api/locale/getAllEvaluations",
            this.EtabInfos
        );
        this.Evaluation = response4.data;



        const response2 = await this.callApi(
            "post",
            "api/locale/getClasseEtablissement",
            this.EtabInfos
        );

        this.ClasseListes = response2.data;
        this.ClasseListes =  this.ClasseListes.filter(item => item.eleves.length > 0 )


    }
};
</script>

<style>
.content-wrapper {
    background-color: #fafbfd;
}

.demo-upload-list {
    display: inline-block;
    width: 60px;
    height: 60px;
    text-align: center;
    line-height: 60px;
    border: 1px solid transparent;
    border-radius: 4px;
    overflow: hidden;
    background: #fff;
    position: relative;
    box-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
    margin-right: 4px;
}

.demo-upload-list img {
    width: 100%;
    height: 100%;
}

.demo-upload-list-cover {
    display: none;
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0, 0, 0, 0.6);
}

.demo-upload-list:hover .demo-upload-list-cover {
    display: block;
}

.demo-upload-list-cover i {
    color: #fff;
    font-size: 20px;
    cursor: pointer;
    margin: 0 2px;
}
</style>
