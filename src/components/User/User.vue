<template>
  <div class="row">
    <v-card outlined elevation="6" class="col-xl-4 col-lg-6 col-md-8 col-12">
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
      api_user_progress_bar: false,
      api_list_progress_bar: false,
      error_snackbar: false
    }
  },
  mounted() {
    this.email_user = this.$route.params.email_user;
    this.api_get_user();
  },
  methods: {
    api_get_user() {
      this.api_user_progress_bar = true;
      this.error_snackbar = false;
      this.$http.get(this.$api_url + 'users/' + 'user.php?email=' + this.email_user)
          .then(result => {
            this.user = result.data;
            this.api_user_progress_bar = false;
          })
          .catch(() => {
            this.api_user_progress_bar = false;
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