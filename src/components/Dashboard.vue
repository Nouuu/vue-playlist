<template>
  <div>
    <div class="d-flex justify-space-between align-center">
      <h1>Test API</h1>
      <v-spacer></v-spacer>
      <v-btn icon color="green"
             @click="api_get">
        <v-icon>mdi-cached</v-icon>
      </v-btn>
    </div>
    <v-progress-linear
        indeterminate
        v-if="album_get_progress"
    ></v-progress-linear>

    <v-card
        class="my-5"
        v-for="album in album_list"
        v-bind:key="album.id"
        v-bind:id="'album_' + album.id"
        elevation="12">
      <v-card-title class="d-flex justify-space-between align-center">
        {{ album.name }}
        <v-btn icon color="red"
               @click="delete_album('album_' + album.id)">
          <v-icon>mdi-minus</v-icon>
        </v-btn>
      </v-card-title>
      <v-card-subtitle>
        <ul>
          <li>Artist : {{ album.artist }}</li>
          <li>Tracks : {{ album.tracks }}</li>
          <li>Duration : {{ album.duration }} min</li>
        </ul>
      </v-card-subtitle>
    </v-card>

    <v-snackbar v-model="snackbar">
      Erreur API
      <template v-slot:action="">
        <v-btn
            color="pink"
            @click="snackbar = false"
            text>Ok
        </v-btn>
      </template>
    </v-snackbar>
  </div>
</template>

<script>
export default {
  name: "DashBoard",
  data: function () {
    return {
      album_list: [],
      album_get_progress: true,
      snackbar: false
    }
  },
  mounted() {
    this.api_get()
  },
  methods: {
    api_get() {
      this.album_get_progress = true;
      this.snackbar = false;
      this.$http.get(this.$api_url + 'test.php')
          .then(result => {
            this.album_list = result.data;
            this.album_get_progress = false;
          })
          .catch(() => {
            this.album_get_progress = false;
            this.snackbar = true;
          })
    },
    delete_album(id) {
      console.log(id);
      console.log(id.substr(6, 1));
      let ind = this.album_list.findIndex(e => e.id === id.substr(6, 1));
      this.album_list.splice(ind, 1);
    }
  }
}
</script>

<style scoped>
ul {
  list-style-type: none;
  padding: 0;
}
</style>