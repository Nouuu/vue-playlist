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
  </v-app>
</template>

<script>
export default {
  name: "Login",
  data: function () {
    return {
      email_user: '',
      password_user: ''
    }
  },
  methods: {
    login: function () {
      const json = JSON.stringify({'email_user': this.email_user, 'password_user': this.password_user});
      console.log(json);
      this.$http.post(this.$api_url + 'user/login.php', json).then((response) => {
        sessionStorage.setItem('connected', 'true');
        sessionStorage.setItem('user', JSON.stringify(response.data));
        this.$router.push('/');
      }).catch((response) => {
        console.log(response);
      })
    }
  }
}
</script>

<style scoped>

</style>