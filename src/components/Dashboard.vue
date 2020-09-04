<template>
  <v-row>
    <v-col v-if="true" cols="12" md="8" lg="6" xl="6">
      <v-card>
        <v-card-title class="d-flex align-baseline">
          Mes listes
          <v-spacer></v-spacer>
          <v-text-field
              v-model="list_searchbar"
              append-icon="mdi-magnify"
              label="Rechercher"
              single-line
              hide-details>
          </v-text-field>
          <v-btn icon color="green"
                 @click="api_get_user_playlist">
            <v-icon>mdi-cached</v-icon>
          </v-btn>

        </v-card-title>
        <v-progress-linear
            indeterminate
            v-if="api_list_progress_bar"
        ></v-progress-linear>
        <v-data-table
            v-if="!api_list_progress_bar"
            :headers="lists_header"
            :items="lists"
            :search="list_searchbar">
        </v-data-table>
      </v-card>
    </v-col>

    <v-col cols="12" md="8" lg="6">
      <album-search @selectAlbum="select_album($event)"></album-search>
    </v-col>

    <v-snackbar v-model="error_snackbar">
      Erreur API
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
import AlbumSearch from "@/components/Discogs/AlbumSearch";

export default {
  name: "DashBoard",
  data: function () {
    return {
      lists: [],
      lists_header: [
        {
          text: 'Nom de la liste',
          align: 'start',
          value: 'name_list'
        },
        {
          text: 'Date de création',
          value: 'date_creation_list'
        },
        {
          text: 'Nombre d\'albums',
          value: 'album_count'
        }
      ],
      list_searchbar: '',
      api_list_progress_bar: false,

      error_snackbar: false
    }
  },
  components: {
    'album-search': AlbumSearch
  },
  mounted() {
    this.api_get_user_playlist();
  },
  methods: {
    api_get_user_playlist() {
      this.api_list_progress_bar = true;
      this.error_snackbar = false;
      this.$http.get(this.$api_url + 'list/read_connected_user.php')
          .then(result => {
            this.lists = result.data.body;
            this.api_list_progress_bar = false;
          })
          .catch(() => {
            this.api_list_progress_bar = false;
            this.error_snackbar = true;
          })
    },
    select_album($id) {
      console.log('selected : ' + $id);
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