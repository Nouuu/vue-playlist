<template>
  <v-app>
    <v-row>
      <v-col cols="auto" style="max-width: 500px">

        <v-card class="d-inline-block mx-auto" v-if="!api_loading_list">
          <div class="text-center">

            <v-avatar class="ma-3" size="300" tile>
              <v-img :src="list.image_list"></v-img>
            </v-avatar>
          </div>
          <v-card-title class="headline">
            {{ list.name_list }}
          </v-card-title>

          <v-card-text>
            <ul>
              <li>Créateur : {{ list.user_pseudo }}</li>
              <li>Date de création : {{ list.date_creation_list }}</li>
              <li>Nombre d'album : {{ list.album_count }}</li>
            </ul>
          </v-card-text>

        </v-card>
        <v-skeleton-loader type="card" class="d-inline-block mx-auto" min-width="300" v-if="api_loading_list">
        </v-skeleton-loader>
      </v-col>
      <v-col cols="12" md="8" lg="6">
        <h2>Tableau</h2>
      </v-col>
    </v-row>
  </v-app>
</template>

<script>
export default {
  name: "ListView",
  data: function () {
    return {
      list_id: null,
      list: {},
      api_loading_list: true
    }
  },
  mounted() {
    this.list_id = this.$route.params.list_id;
    this.api_get_user_list();
  },
  methods: {
    api_get_user_list() {
      this.api_loading_list = true;
      this.$http.get(this.$api_url + 'list/' + 'single_read.php?id=' + this.list_id)
          .then(result => {
            this.list = result.data;
            console.log(this.list);
          })
          .catch(error => {
            console.log(error);
          })
          .finally(() => {
            this.api_loading_list = false
          })
    }
  }
}
</script>

<style scoped>

</style>