<template>
  <div class="row">
    <v-col cols="12" md="8" lg="6" xl="4">

      <v-card outlined elevation="6">
        <v-card-title>
          {{ email_user }}
        </v-card-title>
        <v-progress-linear
            indeterminate
            v-if="api_user_progress_bar"
        ></v-progress-linear>
        <v-card-text v-if="!api_user_progress_bar">
          <ul>
            <li>Pseudo : {{ user.pseudo_user }}</li>
            <li>Role : {{ user.role_user }}</li>
            <li>Date d'inscription : {{ user.date_inscription_user }}</li>
          </ul>
        </v-card-text>
      </v-card>
    </v-col>

    <v-col cols="12" md="8" lg="6" xl="6">
      <v-card>
        <v-card-title class="d-flex align-baseline">
          Listes
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


    <v-snackbar v-model="error_snackbar">
      Erreur API
      <template v-slot:action="">
        <v-btn
            color="pink"
            @click="error_snackbar = false"
            text>Ok
        </v-btn>
      </template>
    </v-snackbar>

  </div>
</template>

<script>
export default {
  name: "User",
  data: function () {
    return {
      email_user: '',
      user: {},
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
      api_user_progress_bar: false,
      api_list_progress_bar: false,
      error_snackbar: false
    }
  },
  mounted() {
    this.email_user = this.$route.params.email_user;
    this.api_get_user();
    this.api_get_user_playlist();
  },
  methods: {
    api_get_user() {
      this.api_user_progress_bar = true;
      this.error_snackbar = false;
      this.$http.get(this.$api_url + 'user/single_read.php?email=' + this.email_user)
          .then(result => {
            this.user = result.data;
            this.api_user_progress_bar = false;
          })
          .catch(() => {
            this.api_user_progress_bar = false;
            this.error_snackbar = true;
          })
    },
    api_get_user_playlist() {
      this.api_list_progress_bar = true;
      this.error_snackbar = false;
      this.$http.get(this.$api_url + 'list/read_user.php?email=' + this.email_user)
          .then(result => {
            this.lists = result.data.body;
            this.api_list_progress_bar = false;
          })
          .catch(() => {
            this.api_list_progress_bar = false;
            this.error_snackbar = true;
          })
    },
  }

}
</script>

<style scoped>
ul {
  list-style-type: none;
  padding: 0;
}
</style>