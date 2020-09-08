<template>
  <v-row>
    <v-col cols="auto">
      <v-card class="d-inline-block mx-auto" v-if="mounted">
        <v-avatar class="ma-3" size="300" tile>
          <v-img :src="album.images[0]"></v-img>
        </v-avatar>

        <v-card-title
            class="headline"
            v-text="album.artist.name"
        ></v-card-title>

        <v-card-subtitle v-text="album.title"></v-card-subtitle>
        <v-card-text>
          <ul>
            <li>
              Date de la release : {{ album.release_date || 'inconnu' }}
            </li>
            <li>
              Genres : {{album.genres.join(', ')}}
            </li>
          </ul>
        </v-card-text>
      </v-card>
      <v-skeleton-loader type="card" class="d-inline-block mx-auto" min-width="300" v-if="!mounted">
      </v-skeleton-loader>
    </v-col>
    <v-col cols="6">
      <v-card shaped class="mx-auto pa-2" v-if="mounted">
        <v-card-title>
          Pistes
        </v-card-title>
        <v-simple-table fixed-header height="390px">
          <template v-slot:default>
            <thead>
            <tr>
              <th class="text-left">N°</th>
              <th class="text-left">Titre</th>
              <th class="text-right">Durée</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="track in album.tracklist" :key="track.position">
              <td>{{track.position}}</td>
              <td>{{track.title}}</td>
              <td class="text-right">{{track.duration}}</td>
            </tr>
            </tbody>
          </template>
        </v-simple-table>
      </v-card>
      <v-skeleton-loader type="card" class="d-inline-block mx-auto" min-width="300" v-if="!mounted">
      </v-skeleton-loader>
    </v-col>


    <v-snackbar v-model="error_snackbar">
      Erreur Lors de la récuperation d'album
      <template v-slot:action="">
        <v-btn
            color="pink"
            @click="error_snackbar = false"
            text>Ok
        </v-btn>
      </template>
    </v-snackbar>

  </v-row>
</template>

<script>
export default {
  name: "AlbumView",
  data: function () {
    return {
      mounted: false,
      error_snackbar: false,
      album_id: '',
      album: {}
    }
  },
  mounted() {
    this.album_id = this.$route.params.album_id
    this.api_get_album();
  },
  methods: {
    api_get_album() {
      this.error_snackbar = false;
      this.$http.get(this.$api_url + 'discogs/get_album.php?id=' + this.album_id)
          .then(result => {
            this.album = result.data;
            this.mounted = true;
          })
          .catch(() => {
            this.error_snackbar = true;
          })
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