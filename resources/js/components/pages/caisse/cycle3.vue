<template>
    <div>
        <div class="wrapper">
            <Header />
            <MenuCaisse />

            <div class="content-wrapper">
                <div class="container-full">
                    <section class="content">
                        <!-- START Card With Image -->


                        <div class="row">

                            <div class="col-md-3" @click="SadeData1(1)">



                                <div class="card" style="background-color: green;color: white;">


                                    <!-- <Icon type="md-albums" style="margin-top: 60px;font-size: 60px;" /> -->

                                    <span  style="margin-top: 40px;font-size: 50px; text-align:center;" > 🖥️ </span>

                                    <div class="box-body py-25" style="text-align: center;">

                                        <p class="font-weight-600"> Enseign Gén Cycle 1 </p>
                                    </div>


                                </div>




                            </div>

                            <div class="col-md-3" @click="SadeData2(2)">



                                    <div class="card" style="background-color: #155A75;color: white;">

                                        <!-- <Icon type="md-albums" style="margin-top: 60px;font-size: 60px;" /> -->

                                        <span  style="margin-top: 40px;font-size: 50px; text-align:center;" > 📚 </span>


                                        <div class="box-body py-25" style="text-align: center;">

                                            <p class="font-weight-600"> Enseign Gén Cycle 2 </p>
                                        </div>


                                    </div>




                            </div>


                            <div class="col-md-3" @click="SadeData3(3)">


                                <div class="card" style="background-color: #E91E63 ; color:white">

                                    <!-- <Icon type="md-settings" style="margin-top: 60px;font-size: 60px;" /> -->

                                    <span  style="margin-top: 40px;font-size: 50px; text-align:center;" > 🖥️ </span>


                                    <div class="box-body py-25" style="text-align: center;">

                                        <p class="font-weight-600"> Enseign Techn Cycle 1</p>
                                    </div>


                                </div>




                            </div>

                            <div class="col-md-3" @click="SadeData4(4)">



                                    <div class="card" style="background-color: #2C353D;color: white;">

                                        <!-- <Icon type="md-settings" style="margin-top: 60px;font-size: 60px;" /> -->

                                        <span  style="margin-top: 40px;font-size: 50px; text-align:center;" > 📚 </span>


                                        <div class="box-body py-25" style="text-align: center;">

                                            <p class="font-weight-600"> Enseign Techn Cycle 2 </p>
                                        </div>


                                    </div>





                            </div>




                        </div>

                         <div class="row">
                            <div class="box">
                                <div class="box-header bg-dark" style="text-align: center;">
                                    <h4 class="box-title">

                                        Retrouver  un  élève a partir de son nom ou de son matricule  pour payer sa scolarité
                                    </h4>
                                </div>

                                <div class="box-footer">
                                    <div class="row">

                                        <div class="col-md-9">
                                            <div class="form-group">
                                                <label for> Nom ou matricule de l’élève  </label>

                                                <input  class="form-control" placeholder="Saisir le nom  ou le matricule de l’élève" v-model="data.nom" />

                                            </div>
                                        </div>

                                        <!-- <div class="col-md-6">
                                            <div class="form-group">
                                                <label for> Prénom </label>

                                                <input class="form-control" v-model="data.prenom" />

                                            </div>
                                        </div> -->


                                        <div class="col-md-3"> <br>
                                            <div class="form-group">
                                                <button @click="Afficher" class="btn btn-primary"> <Icon type="ios-search" /> Rechercher</button>
                                            </div>
                                        </div>




                                    </div>

                                    <div class="row">

                                    </div>

                                     <div class="row" >
                            <div class="col-12" v-if="cache==true">
                                <!-- /.box -->

                                <div class="box">


                                    <div class="box-body">
                                        <div class="table-responsive">
                                            <table id="example" class="table simple mb-0" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th> Matricule </th>
                                                        <th> Nom et Prénom</th>
                                                        <th> Classe </th>
                                                        <th> Date et lieu de naissance </th>
                                                        <th> </th>

                                                    </tr>
                                                </thead>
                                                <tbody name="fruit-table" is="transition-group">
                                                    <tr v-for="(data,
                                                    i) in ElevesAbsences" :key="i">

                                                    <td>
                                                            <img :src="
                                                                `/Photos/Logos/${data.user.photo}`
                                                            " alt="" width="60" style="width: 30px;" height="40" />
                                                        </td>

                                                        <td>{{data.matricule}}</td>

                                                        <td>

                                                            {{ data.nom }}  {{ data.prenom }}

                                                        </td>




                                                        <td>

                                                            {{ data.classe.libelleClasse }}


                                                        </td>




                                                        <td>

                                                            {{ data.dateNaiss|dateFormat}} à  {{ data.lieuNaiss}}


                                                        </td>

                                                        <td>


                                                           <router-link to="detailsEleveFinanceCaisse">

                                                                <button @click="ParentEleve(data, i)"
                                                                    class="btn btn btn-primary btn-sm"
                                                                    title="Les details sur cet eleve ">
                                                                    <!-- <Icon type="ios-apps" /> -->
                                                                    Payer
                                                                </button>

                                                            </router-link>


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
import MenuCaisse from "../../navs/MenuCaisse.vue";

export default {
    components: { Header, Chats, MenuCaisse },
    data() {
        return {

            data: {
                cycle1: 1,

            },

            EtabInfos:[],

             data: {

               nom: "",
                prenom: "",

            },

            cache : false ,

            ElevesAbsences:''

        };
    },

    async mounted() {

        if (!localStorage.users) {

            this.$router.push('login');
        }


        // Recuperer les donnes de cet utulisateurs dans la storage local

        if (localStorage.users) {


            this.users = JSON.parse(localStorage.getItem("users"));
            this.EtabInfos = JSON.parse(localStorage.getItem("EtabInfos"));


        }


    },

    methods: {


         ParentEleve(data, i) {


            localStorage.setItem('parentEleveInfos', JSON.stringify(data));

        },



        SadeData1(data) {

            localStorage.setItem("cycle", JSON.stringify(data));

            this.$router.push('classeFinanceCaisse');

        },

        SadeData2(data) {

            localStorage.setItem("cycle", JSON.stringify(data));

            this.$router.push('classeFinanceCaisse');

        },

        SadeData3(data) {

            localStorage.setItem("cycle", JSON.stringify(data));

            this.$router.push('classeFinanceCaisse');

        },

        SadeData4(data) {

            localStorage.setItem("cycle", JSON.stringify(data));

            this.$router.push('classeFinanceCaisse');

        },


         async Afficher() {


            if (this.data.nom == "") {
                return this.e("Mentionner le nom ou le matricule ");
            }


            this.data.EtabInfos = this.EtabInfos

            const response4 = await this.callApi(
                "post",
                "api/caisse/rechercher", this.data
            );

            this.ElevesAbsences = response4.data

            if (this.ElevesAbsences.length != 0 ) {
                this.cache = true
            } else {
                this.e("Aucun élève trouvé  dans le système ");
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
