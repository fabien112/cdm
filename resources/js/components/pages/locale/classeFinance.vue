<template>
    <div>
        <div class="wrapper">
            <Header />
            <MenuLocal />

            <div class="content-wrapper" style="min-height: 653px; background-color:#FAFBFD">
                <div class="container-full">
                    <!-- Main content -->
                    <section class="content">
                        <div class="row">
                            <div class="col-12">
                                <!-- /.box -->

                                <div class="box">
                                    <div class="box-header bg-primary">
                                        <h2 class="box-title" style="margin:auto">
                                            {{ somme }}
                                            <Icon type="ios-school" />

                                        </h2>


                                    </div>


                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        <div class="table-responsive">
                                            <table id="example" class="table simple mb-0" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Classes</th>
                                                        <!-- <th>Effectifs</th> -->
                                                        <th>Inscription</th>
                                                        <th>Tranche 1 </th>
                                                        <th>Tranche 2 </th>

                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody name="fruit-table" is="transition-group">
                                                    <tr v-for="(data,
                                                    i) in datas" :key="i">
                                                        <td >
                                                            {{
                                                                    data.libelleClasse
                                                            }}
                                                        </td>

                                                        <!-- <td>
                                                            <span class="btn btn-xs"
                                                                style="background-color:#E91E63;color:white"
                                                                title="Effectif de la classe  ">
                                                                {{
                                                                        data.eleves
                                                                            .length
                                                                }}
                                                            </span>
                                                        </td> -->

                                                        <td >
                                                            {{
                                                                    data.scolariteaff_Classe
                                                            }}
                                                        </td>

                                                        <td >
                                                            {{
                                                                    data.scolarite_Classe
                                                            }}
                                                        </td>

                                                        <td >
                                                            {{
                                                                    data.inscription_Classe
                                                            }}
                                                        </td>


                                                        <td >

                                                            <router-link to="listeStudenversement">

                                                                <span title="Liste des eleves de cette classe ">

                                                                    <button type="button"
                                                                        class="btn btn-primary btn-sm">


                                                                        <Icon type="ios-apps" @click="
    ListeEleve(
        data,
        i
    )
                                                                        " />

                                                                    </button>

                                                                </span>
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
                        <!-- /.row -->
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
import MenuLocal from "../../navs/MenuLocal.vue";

export default {
    components: { Header, Chats, MenuLocal },
    data() {
        return {
            UserData: [],
            BtnDisabled: "",
            EtabInfos: "",
            Modal: false,
            modal6: false,
            cycle: '',
            data: {
                sigleClasse: "",
                MontantScol: "",
                FraisInscrip: "",
                MontantScolAffect: "",
                imageEmploiTmp: ""
            },
            somme: 0,

            datas: [],
            classeItem: "",
            classeId: "",
            visible: false,
            uploadList: []
        };
    },

    methods: {
        async handleRemove(file) {
            const image = this.data;

            this.data.imageLogo = "";

            this.$refs.uploads.clearFiles();

            try {
                await axios.post("api/admin/delateImage", image);
            } catch (e) {
                this.generalError = e.response.data.errors;
            }
        },

        handleView(name) {
            this.data.imageLogo = name;
            this.visible = true;
        },

        handleSuccess(res, file) {
            this.data.imageEmploiTmp = res;
            console.log(res);
        },

        handleError(res, file) {
            this.w("Selectionner un jpg, png , jpeg, pdf, doc, ou docx");
        },
        handleFormatError(file) {
            this.w("Selectionner un jpg, png , jpeg, pdf, doc, ou docx");
        },
        handleMaxSize(file) {
            this.w("Selctionner un fichier de moins de 2M.");
        },

        handleBeforeUpload() {
            const check = this.uploadList.length < 1;
            if (!check) {
                this.w("Le logo est requi...");
            }
            return check;
        },
        async Submit() {
            if (this.data.sigleClasse.trim() == "") {
                return this.e("Saisir un nom de la classe ");
            }

            if (this.data.FraisInscrip.trim() == "") {
                return this.e(
                    "Saisir un chiffre pour  les frais d'incription "
                );
            }

            if (this.data.MontantScolAffect.trim() == "") {
                return this.e(
                    "Saisir un chiffre le montant de la scolarite affecte"
                );
            }

            if (this.data.MontantScol.trim() == "") {
                return this.e("Saisir un chiffre le montant de la scolarite ");
            }

            if (this.data.imageEmploiTmp.trim() == "") {
                return this.e("Inserer l'emploi du temps");
            }

            this.data.EcoleInfos = this.EtabInfos;

            const res = await this.callApi(
                "post",
                "api/locale/Addclasse",
                this.data
            );

            if (res.status == 200) {
                this.s("Classe ajoutée correctement");

                this.modal6 = false;

                this.datas.unshift(res.data);
            } else {
                this.e("Une erreure est survenue");
            }
        },

        cloturer(data, i) {
            this.classeItem = data;

            // Enregistrer les donnees de la classe  dans le local storage

            localStorage.setItem("classeItem", JSON.stringify(data));
        },

        ListeEleve(data, i) {
            this.classeId = data;

            // mettre  id de la classe cliquee  dans le local storage

            localStorage.setItem("classeId", JSON.stringify(data));

            // console.log(data);
        }
    },

    async created() {
        // Recuperer toutes les infos de cette ecole dans le storage

        if (!localStorage.users) {

            this.$router.push('login');
        }

        if (localStorage.EtabInfos) {
            this.EtabInfos = JSON.parse(localStorage.getItem("EtabInfos"));

            if (localStorage.cycle) {
                this.cycle = JSON.parse(localStorage.getItem("cycle"));
                this.EtabInfos[0].cycle = this.cycle;
            }
        }

        // Recuperer toutes les sessions de cette ecole

        const response2 = await this.callApi(
            "post",
            "api/locale/getClasseEtablissement2",
            this.EtabInfos
        );

        this.datas = response2.data;

        this.datas =  this.datas.filter(item => item.eleves.length >0 )



        for (let i = 0; i <= this.datas.length; i++) {

            this.somme = this.somme + parseInt(this.datas[i].eleves.length)


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
