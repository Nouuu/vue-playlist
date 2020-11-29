<template>
  <v-row>
    <v-col cols="12" md="8" lg="6" xl="6">
      <v-card>
        <v-card-title class="d-flex align-baseline">
          Mes listes
          <v-spacer></v-spacer>
          <v-text-field
              v-model="list_searchbar"
              append-icon="mdi-magnify"
              label="Rechercher"
              single-line
              hide-details>
          </v-text-field>
          <v-btn icon color="green"
                 @click="api_get_user_playlist">
            <v-icon>mdi-cached</v-icon>
          </v-btn>

        </v-card-title>
        <v-progress-linear
            indeterminate
            v-if="api_list_progress_bar"
        ></v-progress-linear>
        <v-data-table
            v-if="!api_list_progress_bar"
            :headers="lists_header"
            :items="lists"
            :search="list_searchbar"
        >

          <template v-slot:item.name_list="{item}">
            <div @click="showList(item)" class="text-decoration-underline font-weight-bold" style="cursor: pointer">
              {{item.name_list}}
            </div>
          </template>
          <template v-slot:item.cover="{item}">
            <div class="my-2">
              <v-img :src="item.cover" :alt="item.cover" height="75px" max-width="75px" contain></v-img>
            </div>
          </template>
          <template v-slot:item.actions="{item}">
            <v-icon small class="mr-2" @click="editItem(item)"> mdi-pencil</v-icon>
            <!--            <v-icon small @click="deleteItem(item)"> mdi-delete</v-icon>-->
          </template>
        </v-data-table>
      </v-card>
    </v-col>
    <v-col cols="12" md="8" lg="6" xl="6">
      <v-card>
        <v-card-title class="d-flex align-baseline">
          Créer une liste
        </v-card-title>
        <v-card-text>
          <v-form v-model="form.valid" ref="form">
            <v-row>
              <v-col cols="12">
                <v-text-field
                    v-model="form.name"
                    :rules="form.nameRules"
                    :counter="100"
                    label="Nom"
                    required></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                    v-model="form.imgUrl"
                    :rules="form.imgUrlRules"
                    :counter="255"
                    label="Image (optionnel)"></v-text-field>
              </v-col>

            </v-row>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-progress-circular v-if="form.progress_bar" indeterminate color="primary"
                               class="mr-3"></v-progress-circular>
          <v-btn :disabled="!form.valid" @click="create_playlist" color="primary">Ajouter</v-btn>
        </v-card-actions>
      </v-card>
    </v-col>
    <v-snackbar v-model="snackbar">
      {{ snackbar_msg }}
      <template v-slot:action="">
        <v-btn
            color="pink"
            @click="snackbar = false"
            text>Ok
        </v-btn>
      </template>
    </v-snackbar>
    <v-dialog
        v-model="dialogEdit"
        max-width="500px">
      <v-card>
        <v-card-title>
          <span class="headline">Modifier la liste</span>
        </v-card-title>

        <v-card-text>
          <v-form v-model="edit_form.valid" ref="edit_form">
            <v-row>
              <v-col cols="12">
                <v-text-field
                    v-model="edit_form.name"
                    :rules="edit_form.nameRules"
                    :counter="100"
                    label="Nom"
                    required></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                    v-model="edit_form.imgUrl"
                    :rules="edit_form.imgUrlRules"
                    :counter="255"
                    label="Image (optionnel)"></v-text-field>
              </v-col>

            </v-row>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-progress-circular v-if="edit_form.progress_bar" indeterminate color="primary"
                               class="mr-3"></v-progress-circular>
          <v-btn :disabled="!edit_form.valid" @click="update_playlist" color="primary">Modifier</v-btn>
          <v-btn color="warning"> Fermer
            <!--              @click="close"-->
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>
<script>

export default {
  name: "DashBoard",
  data: function () {
    return {
      lists: [],
      lists_header: [
        {
          text: 'Nom de la liste',
          align: 'start',
          value: 'name_list'
        },
        {
          text: 'Date de création',
          value: 'date_creation_list'
        },
        {
          text: 'Nombre d\'albums',
          value: 'album_count'
        },
        {
          text: 'Image',
          align: 'start',
          value: 'cover'
        },
        {
          text: 'Actions',
          value: 'actions',
          sortable: false
        }
      ],
      list_searchbar: '',
      api_list_progress_bar: false,
      form: {
        valid: false,
        name: '',
        nameRules: [
          v => !!v || 'Champ requis',
          v => v.length <= 100 || '100 caractères max',
          v => v.length >= 3 || 'Entrez au moins 3 caractères'
        ],
        imgUrl: '',
        imgUrlRules: [
          v => v.length === 0 || v.match(/\.(jpeg|jpg|gif|png)$/) != null || 'Entrez un lien vers une image valide',
          v => v.length <= 255 || '255 caractères max',
        ],
        progress_bar: false
      },
      edit_form: {
        valid: false,
        id: 0,
        name: '',
        nameRules: [
          v => !!v || 'Champ requis',
          v => v.length <= 100 || '100 caractères max',
          v => v.length >= 3 || 'Entrez au moins 3 caractères'
        ],
        imgUrl: '',
        imgUrlRules: [
          v => v.length === 0 || v.match(/\.(jpeg|jpg|gif|png)$/) != null || 'Entrez un lien vers une image valide',
          v => v.length <= 255 || '255 caractères max',
        ],
        progress_bar: false
      },
      dialogEdit: false,
      dialogDelete: false,
      snackbar: false,
      snackbar_msg: ''
    }
  },
  components: {},
  mounted() {
    this.api_get_user_playlist();
  },
  methods: {
    api_get_user_playlist() {
      this.api_list_progress_bar = true;
      this.snackbar = false;
      this.$http.get(this.$api_url + 'list/read_connected_user.php')
          .then(result => {
            this.lists = result.data.body;
            this.api_list_progress_bar = false;
          })
          .catch(() => {
            this.api_list_progress_bar = false;
            this.snackbar_msg = 'Erreur API';
            this.snackbar = true;
          })
    },
    create_playlist() {
      if (!this.form.valid) {
        this.snackbar_msg = 'Formulaire invalide !';
        this.snackbar = true;
        return 1;
      }
      this.form.progress_bar = true;
      const json = JSON.stringify({'name_list': this.form.name, 'image_list': this.form.imgUrl});
      this.$http.post(this.$api_url + 'list/create.php', json).then(() => {
        //SUCCESS
        this.api_get_user_playlist();
        this.snackbar_msg = 'Nouvelle liste créée';
        this.snackbar = true;
        this.form.name = '';
        this.form.imgUrl = '';
        this.$refs.form.resetValidation();
      }).catch(() => {
        //ERROR
        this.snackbar_msg = 'Erreur lors de la création de la liste...';
        this.snackbar = true;
      }).finally(() => {
        this.form.progress_bar = false;
      })
    },
    update_playlist() {
      if (!this.edit_form.valid || this.edit_form.id === null || this.edit_form.id === 0) {
        this.snackbar_msg = 'Formulaire invalide !';
        this.snackbar = true;
        return 1;
      }
      this.edit_form.progress_bar = true;
      const json = JSON.stringify({
        'id_list': this.edit_form.id,
        'name_list': this.edit_form.name,
        'image_list': this.edit_form.imgUrl
      });
      this.$http.post(this.$api_url + 'list/update.php', json).then(() => {
        //SUCCESS
        this.api_get_user_playlist();
        this.snackbar_msg = 'Liste modifiée';
        this.snackbar = true;
        this.edit_form.name = '';
        this.edit_form.imgUrl = '';
        this.$refs.edit_form.resetValidation();
        this.dialogEdit = false;
      }).catch(() => {
        //ERROR
        this.snackbar_msg = 'Erreur lors de la modification de la liste...';
        this.snackbar = true;
      }).finally(() => {
        this.edit_form.progress_bar = false;
      })
    },
    editItem(item) {
      this.edit_form.id = item.id_list;
      this.edit_form.name = item.name_list;
      this.edit_form.imgUrl = item.cover === null ? '' : item.cover;
      this.dialogEdit = true;
    },
    showList(event) {
      this.$router.push('/list/' + event.id_list);
    }
  }
}
</script>

<style scoped>
ul {
  list-style-type: none;
  padding: 0;
}
</style>