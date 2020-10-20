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
            :search="list_searchbar">
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
              <v-col cols="12" class="d-flex align-center">
                <v-spacer></v-spacer>
                <v-progress-circular v-if="form.progress_bar" indeterminate color="primary"
                                     class="mr-3"></v-progress-circular>
                <v-btn :disabled="!form.valid" @click="create_playlist" color="primary">Ajouter</v-btn>
              </v-col>
            </v-row>
          </v-form>
        </v-card-text>
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