<template>
  <v-app id="inspire">
    <v-main>
      <v-container
          class="fill-height"
          fluid
      >
        <v-row
            align="center"
            justify="center"
        >
          <v-col
              cols="12"
              sm="8"
              md="4"
          >
            <v-card class="elevation-12">
              <v-toolbar
                  color="primary"
                  dark
                  flat
              >
                <v-toolbar-title>Connexion</v-toolbar-title>
              </v-toolbar>
              <v-card-text>
                <v-form>
                  <v-text-field
                      label="Email"
                      prepend-icon="mdi-account"
                      type="text"
                      v-model="email_user"
                      @keyup.enter="login"
                  ></v-text-field>

                  <v-text-field
                      id="password"
                      label="Password"
                      prepend-icon="mdi-lock"
                      type="password"
                      v-model="password_user"
                      @keyup.enter="login"
                  ></v-text-field>
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" @click.prevent="login">Se connecter</v-btn>
              </v-card-actions>
            </v-card>
          </v-col>
        </v-row>
      </v-container>
    </v-main>
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
  </v-app>

</template>

<script>
export default {
  name: "Login",
  data: function () {
    return {
      email_user: '',
      password_user: '',
      snackbar: false,
      snackbar_msg: ""
    }
  },
  methods: {
    login: function () {
      const json = JSON.stringify({'email_user': this.email_user, 'password_user': this.password_user});
      this.$http.post(this.$api_url + 'user/login.php', json).then((response) => {
        sessionStorage.setItem('connected', 'true');
        sessionStorage.setItem('user', JSON.stringify(response.data));
        this.$router.push('/');
      }).catch((error) => {
        console.log(error.response.status);
        switch (error.response.status) {
          case 500:
            this.snackbar_msg = 'Une erreur serveur est survenue...';
            break;
          case 401:
            this.snackbar_msg = 'Identifiants incorrects';
            break;
          default:
            this.snackbar_msg = 'Erreur';
            break;
        }
        this.snackbar = true;
      })
    }
  }
}
</script>

<style scoped>

</style>