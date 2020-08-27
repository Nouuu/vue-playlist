<template>
  <v-navigation-drawer
      v-model="drawer"
      app
      permanent
      expand-on-hover
  >
    <v-list dense>
      <v-list-item link to="/" exact>
        <v-list-item-action>
          <v-icon>mdi-monitor-dashboard</v-icon>
        </v-list-item-action>
        <v-list-item-content>
          <v-list-item-title>Dashboard</v-list-item-title>
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
          <v-list-item-title>Settings</v-list-item-title>
        </v-list-item-content>
      </v-list-item>

    </v-list>
    <template v-slot:append>
      <v-list-item link to="/logout" exact>
        <v-list-item-action>
          <v-icon>mdi-logout</v-icon>
        </v-list-item-action>
        <v-list-item-content>
          <v-list-item-title>Logout</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </template>
  </v-navigation-drawer>
</template>

<script>
export default {
  name: "Sidebar",
  data: () => ({
    drawer: null,
  })
}
</script>

<style scoped>

</style>