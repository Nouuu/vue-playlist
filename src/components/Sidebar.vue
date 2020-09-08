<template>
  <v-navigation-drawer
      v-model="drawer"
      app
      permanent
      expand-on-hover
  >

    <v-list>

      <v-list-item link>
        <v-list-item-action>
          <v-icon>mdi-account-box</v-icon>

        </v-list-item-action>
        <v-list-item-content>
          <v-list-item-title class="title text-capitalize">{{ username }}</v-list-item-title>
          <v-list-item-subtitle>{{ email }}</v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>
    </v-list>

    <v-divider></v-divider>
    <v-list dense>
      <v-list-item link to="/" exact>
        <v-list-item-action>
          <v-icon>mdi-monitor-dashboard</v-icon>
        </v-list-item-action>
        <v-list-item-content>
          <v-list-item-title>Accueil</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item link to="/search" exact>
        <v-list-item-action>
          <v-icon>mdi-music-circle-outline</v-icon>
        </v-list-item-action>
        <v-list-item-content>
          <v-list-item-title>Trouver un média</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item link to="/users" exact>
        <v-list-item-action>
          <v-icon>mdi-account-multiple</v-icon>
        </v-list-item-action>
        <v-list-item-content>
          <v-list-item-title>Utilisateurs</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item link to="/settings" exact>
        <v-list-item-action>
          <v-icon>mdi-cog</v-icon>
        </v-list-item-action>
        <v-list-item-content>
          <v-list-item-title>Paramètres</v-list-item-title>
        </v-list-item-content>
      </v-list-item>

    </v-list>
    <template v-slot:append>
      <v-list-item link to="/logout" exact>
        <v-list-item-action>
          <v-icon>mdi-logout</v-icon>
        </v-list-item-action>
        <v-list-item-content>
          <v-list-item-title>Se déconnecter</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </template>
  </v-navigation-drawer>
</template>

<script>
import {UserFunctions} from "@/components/functions/User";

export default {
  name: "Sidebar",
  data: () => ({
    drawer: null,
    username: null,
    email: null
  }),
  mounted() {
    let user = UserFunctions.getConnectedUser();
    this.username = user.pseudo_user;
    this.email = user.email_user;
  }
}
</script>

<style scoped>

</style>