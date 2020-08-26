<template>
  <div class="row">
    <v-card class="col-xl-8 col-md-12">
      <v-card-title class="d-flex align-baseline">
        Utilisateurs
        <v-spacer></v-spacer>
        <v-text-field
            v-model="searchbar"
            append-icon="mdi-magnify"
            label="Rechercher"
            single-line
            hide-details>
        </v-text-field>
        <v-btn icon color="green"
               @click="api_get_user_list">
          <v-icon>mdi-cached</v-icon>
        </v-btn>

      </v-card-title>
      <v-progress-linear
          indeterminate
          v-if="api_progress_bar"
      ></v-progress-linear>
      <v-data-table
          v-if="!api_progress_bar"
          :headers="headers"
          :items="user_list"
          :search="searchbar"
          @click:row="showUser"
      >
      </v-data-table>
    </v-card>

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
  name: "Users",
  data: function () {
    return {
      user_list: [],
      api_progress_bar: false,
      error_snackbar: false,
      headers: [
        {
          text: 'Email',
          align: 'start',
          value: 'email_user'
        },
        {
          text: 'Pseudo',
          value: 'pseudo_user'
        },
        {
          text: 'Mot de passe',
          value: 'password_user'
        },
        {
          text: 'Role',
          value: 'role_user'
        },
        {
          text: 'Date inscription',
          value: 'date_inscription_user'
        },
        {
          text: 'Listes',
          value: 'list_count'
        }
      ],
      searchbar: ''
    }
  },
  mounted() {
    this.api_get_user_list()
  },
  methods: {
    api_get_user_list() {
      this.api_progress_bar = true;
      this.error_snackbar = false;
      this.$http.get(this.$api_url + 'user/' + 'read.php')
          .then(result => {
            this.user_list = result.data.body;
            this.api_progress_bar = false;
          })
          .catch(() => {
            this.api_progress_bar = false;
            this.error_snackbar = true;
          })
    },
    showUser(e) {
      this.$router.push('/user/' + e.email_user)
    }
  }
}
</script>

<style scoped>

</style>