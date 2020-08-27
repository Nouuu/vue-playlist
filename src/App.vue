<template>
  <v-app id="inspire">
    <sidebar v-if="sidebar"></sidebar>
    <v-main>
      <v-container
          class="pa-10"
          fluid
      >
        <router-view></router-view>
      </v-container>
    </v-main>

  </v-app>
</template>

<script>
import Sidebar from "@/components/Sidebar";

export default {
  name: 'App',

  components: {
    'sidebar': Sidebar
  },

  data: () => ({
    drawer: null,
    sidebar: false
  }),
  created() {
    this.$vuetify.theme.dark = true
  },
  updated() {
    this.sidebar = sessionStorage.getItem('connected') === 'true';
  },
  mounted() {
    this.sidebar = sessionStorage.getItem('connected') === 'true';
  }
};
</script>
<style scoped>
</style>
