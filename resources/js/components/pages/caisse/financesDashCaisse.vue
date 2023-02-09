<template>
    <div>
        <div class="wrapper">
            <Header />
            <MenuCaisse />
            <div class="content-wrapper">
                <div class="container-full">
                    <section class="content">
                        <!-- START Card With Image
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">LISTING DES FINANCES</h4>
                            </div>
                        </div>-->

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for>Sélectionner la classe</label>

                                    <select name="LeaveType" @change="onChange($event)" class="form-control"
                                        v-model="keyword">
                                        <option v-for="(data, i) in ClasseListes" :key="i" :value="data.id">{{
                                        data.libelleClasse
                                        }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <br />
                        <br />
                        <br />

                        <div class="row" v-if="EleveListes!=''">


                            <div class="col-xl-4 col-12">
                                <div class="box">
                                    <div class="box-header bg-danger " style="text-align: center;">
                                        <h4 class="box-title">
                                            <strong>
                                                INSCRIPTION
                                            </strong>
                                        </h4>
                                    </div>
                                    <div class="box-body">
                                        <div class="table-responsive">
                                            <table class="table simple mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td>Total payé </td>
                                                        <td class=" font-weight-700">
                                                            {{financeClasse.ape}}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>Total reste </td>
                                                        <td class=" font-weight-700">
                                                            {{financeClasse2.ape-financeClasse.ape}}
                                                        </td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4 col-12">
                                <div class="box">
                                    <div class="box-header bg-success " style="text-align: center;">
                                        <h4 class="box-title">
                                            <strong>
                                                TRANCHE 1
                                            </strong>
                                        </h4>
                                    </div>
                                    <div class="box-body">
                                        <div class="table-responsive">
                                            <table class="table simple mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td>Total payé </td>
                                                        <td class=" font-weight-700">
                                                            {{financeClasse.tranche1}}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>Total reste </td>
                                                        <td class=" font-weight-700">
                                                            {{financeClasse2.tranche1-financeClasse.tranche1}}
                                                        </td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4 col-12">
                                <div class="box">
                                    <div class="box-header bg-primary " style="text-align: center;">
                                        <h4 class="box-title">
                                            <strong>
                                                TRANCHE 2
                                            </strong>
                                        </h4>
                                    </div>
                                    <div class="box-body">
                                        <div class="table-responsive">
                                            <table class="table simple mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td>Total payé </td>
                                                        <td class=" font-weight-700">
                                                            {{financeClasse.tranche2}}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td>Total reste </td>
                                                        <td class=" font-weight-700">
                                                            {{financeClasse2.tranche2-financeClasse.tranche2}}
                                                        </td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-12">
                                <!-- /.box -->

                                <div class="box">
                                    <div class="box-header bg-primary">
                                        <h4 class="box-title" style="margin:auto"><button @click="RecuPDF2()"
                                                class="btn btn-primary">
                                                <Icon type="md-apps" /> Liste des non redevables
                                            </button>

                                            <span style="font-size:35px;padding-left:120px;">  {{EleveListes.length}} <Icon type="ios-school" /> </span>

                                      </h4>
                                        <span>


                                            <button type="button" class=" pull-right btn  btn-primary mb-5"
                                                @click="RecuPDF()">
                                                <Icon type="md-apps" />

                                                Liste des insolvables
                                            </button>



                                        </span>
                                    </div>

                                    <div class="box-body">
                                        <div class="table-responsive">
                                            <table id="example" class="table simple mb-0" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Noms</th>
                                                        <th>INSCRIPTION</th>
                                                        <th>TRANCHE 1</th>
                                                        <th>TRANCHE 2</th>


                                                    </tr>
                                                </thead>

                                                <tbody v-if="showRecap == true" name="fruit-table"
                                                    is="transition-group">
                                                    <tr v-for="(data,
                                                    i) in EleveListes" :key="i">

                                                        <td>

                                                            <!-- <p v-if="(data.scolarite.tranche1 + data.scolarite.tranche2 + data.scolarite.ape) ==
                                                        (data.classe.scolarite_Classe + data.classe.inscription_Classe + data.classe.scolariteaff_Classe)"
                                                            style="color: rgb(51, 143, 51);">

                                                        </p> -->

                                                            <span v-if="data.statut==2"
                                                                style="color: rgb(51, 143, 51);">
                                                                {{ data.nom }} {{ data.prenom }}

                                                            </span>

                                                            <span v-if="data.statut==1" style="color: #0052CC;">
                                                                {{ data.nom }} {{ data.prenom }}

                                                            </span>

                                                            <span v-if="data.statut==0" style="color:  #E91E63;">
                                                                {{ data.nom }} {{ data.prenom }}

                                                            </span>






                                                        </td>




                                                        <td>
                                                            <p>





                                                                <!-- Total : {{ data.classe.scolariteaff_Classe }} <br> -->

                                                                Versé : {{
                                                                data.scolarite.ape
                                                                }} <br>
                                                                <strong> Reste : {{ data.classe.scolariteaff_Classe -
                                                                data.scolarite.ape
                                                                }} </strong>

                                                            </p>



                                                        </td>

                                                        <td>
                                                            <p>

                                                                <!-- Total : {{ data.classe.scolarite_Classe }} <br> -->
                                                                Versé : {{ data.scolarite.tranche1 }} <br>
                                                                <strong>
                                                                    Reste : {{ data.classe.scolarite_Classe -
                                                                    data.scolarite.tranche1
                                                                    }}
                                                                </strong>
                                                            </p>
                                                        </td>



                                                        <td>

                                                            <p>



                                                                <!-- Total : {{ data.classe.inscription_Classe }} <br> -->

                                                                Versé : {{ data.scolarite.tranche2 }} <br>
                                                                <strong>
                                                                    Reste : {{ data.classe.inscription_Classe -
                                                                    data.scolarite.tranche2
                                                                    }}
                                                                </strong>
                                                            </p>

                                                        </td>


                                                    </tr>
                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                    <!-- /.box-body -->
                                </div>
                                <!-- /.box -->
                            </div>
                            <!-- /.col -->
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
import MenuCaisse from "../../navs/MenuCaisse.vue";
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
    components: { Header, MenuCaisse, Chats },
    data() {
        return {
            data: {
                idClasse: ""
            },

            financeClasse: {
                'ape': 0,
                'tranche1': 0,
                'tranche2': 0
            },

            keyword: "",
            keyword2: "",
            ClasseListes: "",
            EleveListes: "",
            ElevesAbsences: "",
            TotalHeure: 0,
            showRecap: false,
            cycle: ''
        };
    },

    async mounted() {

        if (!localStorage.users) {

            this.$router.push('login');
        }

        if (localStorage.EtabInfos) {
            this.EtabInfos = JSON.parse(localStorage.getItem("EtabInfos"));
            this.cycle = JSON.parse(localStorage.getItem("cycle"));
            this.EtabInfos[0].cycle = this.cycle;
        }


        const response2 = await this.callApi(
            "post",
            "api/locale/getClasseEtablissement",
            this.EtabInfos
        );

        this.ClasseListes = response2.data;

        this.ClasseListes =  this.ClasseListes.filter(item => item.eleves.length >0 )
    },

    methods: {

        async RecuPDF() {

        window.open('api/locale/InsolvablesPdf/' +  this.data.idClasse , '_blank')

        const responsePdf = await this.callApi(
         "get",
        "api/locale/InsolvablesPdf/" + this.data.idClasse

);


},

async RecuPDF2() {

window.open('api/locale/NonInsolvablesPdf/' +  this.data.idClasse , '_blank')

const responsePdf = await this.callApi(
 "get",
"api/locale/NonInsolvablesPdf/" + this.data.idClasse

);


},

        details(data, i) {

            localStorage.setItem('Elevefi', JSON.stringify(data));

        },


        async onChange(event) {
            this.data.idClasse = event.target.value;

            this.showRecap = true;

            this.data.EtabInfos = this.EtabInfos;

            // Recuperer tous les eleves de cette classe

            const response3 = await this.callApi(
                "post",
                "api/locale/getEleveclasseFinances",
                this.data
            );

            this.EleveListes = response3.data;


                for (let i in this.EleveListes) {

                    this.financeClasse = this.EleveListes[i].ClassePaye;

                    this.financeClasse2 = this.EleveListes[i].ClasseAPayer;
                }



        }
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
