<template>
  <v-app id="inspire">
<sidebar></sidebar>
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
    'sidebar':Sidebar
  },

  data: () => ({
    drawer: null,
  }),
  created() {
    this.$vuetify.theme.dark = true
  },
};
</script>
<style scoped>
</style>
