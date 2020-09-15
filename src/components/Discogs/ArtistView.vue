<template>
  <v-row>
    <v-col cols="auto" style="max-width: 500px">
      <v-card class="d-inline-block mx-auto" v-if="mounted">
        <div class="text-center">

          <v-avatar class="ma-3" size="300" tile>
            <v-img :src="artist.image"></v-img>
          </v-avatar>
        </div>

        <v-card-title
            class="headline"
            v-text="artist.name"
        ></v-card-title>

        <v-card-text>
          {{ artist.profile }}
        </v-card-text>
      </v-card>
      <v-skeleton-loader type="card" class="d-inline-block mx-auto" min-width="300" v-if="!mounted">
      </v-skeleton-loader>
    </v-col>
    <v-col cols="6">
      <v-card shaped class="mx-auto pa-2" v-if="mounted">
        <v-card-title class="d-flex align-baseline">
          Albums
          <v-spacer></v-spacer>
          <v-text-field
              v-model="searchbar"
              append-icon="mdi-magnify"
              label="Rechercher"
              single-line
              hide-details>
          </v-text-field>
        </v-card-title>

        <v-data-table
            :headers="headers"
            :items="artist.releases"
            :search="searchbar"
            items-per-page="5"
            @click:row="showAlbum"
        >
          <template v-slot:item.thumb="{item}">
            <div class="my-2">
              <v-img :src="item.thumb" :alt="item.name" height="75px" contain></v-img>
            </div>
          </template>
        </v-data-table>
      </v-card>
      <v-skeleton-loader type="card" class="d-inline-block mx-auto" min-width="300" v-if="!mounted">
      </v-skeleton-loader>
    </v-col>


    <v-snackbar v-model="error_snackbar">
      Erreur Lors de la récuperation de l'artiste
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
  name: "ArtistView",
  data: function () {
    return {
      mounted: false,
      error_snackbar: false,
      artist_id: '',
      artist: {},
      headers: [
        {
          text: 'Titre',
          align: 'start',
          value: 'title'
        },
        {
          text: 'Année',
          // align: 'end',
          value: 'year'
        },
        {
          text: 'Image',
          align: 'end',
          value: 'thumb'
        }
      ],
      searchbar: ''
    }
  },
  mounted() {
    this.artist_id = this.$route.params.artist_id
    this.api_get_artist();
  },
  methods: {
    api_get_artist() {
      this.error_snackbar = false;
      this.$http.get(this.$api_url + 'discogs/get_artist.php?id=' + this.artist_id)
          .then(result => {
            this.artist = result.data;
            this.mounted = true;
          })
          .catch(() => {
            this.error_snackbar = true;
          })
    },
    showAlbum(e) {
      this.$router.push('/search/album/' + e.id)
    }

  }
}
</script>

<style scoped>
</style>