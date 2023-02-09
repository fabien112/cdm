<template>
    <div>
        <div class="wrapper">
            <Header />
            <MenuLocal />

            <div class="content-wrapper">
                <div class="container-full">

                    <section class="content">
                        <div class="box  box-default">

                            <div class="box-header" style="background-color:#0052CC;text-align: center; color:white">

                                <h4 class="box-title">
                                    Bulletins de la séquence
                                </h4>
                            </div>

                            <div class="box-body wizard-content">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for=""> Evaluation </label>

                                                    <select v-model="
                                                        datas.libelleEvaluation
                                                    " class="custom-select form-control required">
                                                        <option v-for="(data,
                                                        i) in Evaluation" :key="i" :value="data.id">
                                                            {{ data.libelle }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for=""> Classe </label>
                                                    <select @change="onChange($event)" v-model="datas.classeName"
                                                        class="custom-select form-control required">
                                                        <option v-for="(data,
                                                        i) in ClassListes" :key="i" :value="data.id">
                                                            {{
                                                                    data.libelleClasse
                                                            }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row" >
                                            <div class="col-md-12">

                                                <div class="form-group">
                                                    <button @click="update"
                                                        class="waves-effect waves-light btn mb-5  btn btn-danger btn-block ">

                                                        LANCER LE CALCULATEUR DE MOYENNES
                                                    </button>
                                                </div>
                                            </div>


                                        </div> <br>

                                        <div class="row" >
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <button @click="afficher"
                                                        class="waves-effect waves-light btn mb-5  btn btn-warning btn-block">
                                                        <!-- <Icon type="md-printer"  />
                                                         -->
                                                     LANCER LES  BULLETINS DE NOTES EN PDF
                                                    </button>
                                                </div>

                                            </div>







                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>

                    </section>

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
                classeName: "",
                idClasse: "",
                libelleEvaluation: "",
                trimestre: ""
            },

            Note: {},
            Obser: {},
            i: 1,
            showDelateModal: false,
            LIbelleMatiereclasse: "",
            checkedNames: [],
            checkBoxs: [],
            rempli: false,
            users: [],
            ClassListes: [],
            MatieresListes: [],
            Notes: [],
            Evaluation: [],
            show: false,
            val: false

        };
    },

    async mounted() {

        if (!localStorage.users) {

            this.$router.push('login');
        }

        if (localStorage.EtabInfos) {
            this.EtabInfos = JSON.parse(localStorage.getItem("EtabInfos"));
        }

        // Recuperer toutes les sessions de cette ecole

        const response2 = await this.callApi(
            "post",
            "api/locale/getClasseEtablissement",
            this.EtabInfos
        );

        this.ClassListes = response2.data;
        this.ClassListes =  this.ClassListes.filter(item => item.eleves.length > 0 )


        const response4 = await this.callApi(
            "post",
            "api/locale/getAllEvaluations",
            this.EtabInfos
        );
        this.Evaluation = response4.data;
    },
    methods: {
        async onChange(event) {

            this.datas.idClasse = event.target.value;

            this.datas.EtabInfos = this.EtabInfos;

            // Recuperer toutes les matieres de cette  classe

            const response3 = await this.callApi(
                "post",
                "api/locale/getLibelleMatiereclasseLocaleById",
                this.datas
            );

            this.LIbelleMatiereclasse = response3.data;
        },

        ShowModal() {
            this.showDelateModal = true;
        },

        async update() {



            if (this.datas.libelleEvaluation == "") {
                return this.e("Sélectionner une évaluation ");
            }

            if (this.datas.classeName == "") {
                return this.e("Sélectionner une classe ");
            }

            const response7 = await this.callApi(
                "post",
                "api/locale/updateMoyenne",
                this.datas
            );


            if (response7.status == 200) {


                this.s("Synchronisation des notes correctement éffectuée");

                this.val = true;

            }

            if (response7.status == 403) {


                this.e("Toutes les notes de cette séquence dans cette classe n'ont pas encore été saisies par les enseignants");


            }




            if (response7.status == 400) {


                this.s("Mise a jour des notes...");

                this.val = true;



            }




        },

        async afficher() {

            this.val = false

            if (this.datas.libelleEvaluation == "") {
                return this.e("Sélectionner une évaluation ");
            }

            if (this.datas.classeName == "") {
                return this.e("Sélectionner une classe ");
            }

            window.open('api/locale/getAllBulletinEval/' + this.datas.classeName + '*' + this.datas.libelleEvaluation, '_blank')

            const response2 = await this.callApi(
                "get",
                "api/locale/getAllBulletinEval/" + this.datas.classeName + '*' + this.datas.libelleEvaluation

            );

        },


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
