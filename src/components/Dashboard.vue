<template>
  <v-row>
    <v-col v-if="false" cols="12" md="8" lg="6" xl="6">
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
    <v-col cols="12">
      <v-text-field label="Search album" v-model="album_search" @keyup="api_search_album">
      </v-text-field>
    </v-col>

    <v-col cols="12" md="6" lg="4" xl="3" v-for="item in album_result.results" :key="item.id">
      <v-card>
        <div class="d-flex flex-no-wrap justify-space-between">
          <div>
            <v-card-title class="headline" v-text="item.title"></v-card-title>

            <v-card-subtitle v-text="item.year"></v-card-subtitle>
          </div>

          <v-avatar class="ma-3" size="125" tile>
            <v-img :src="item.thumb"></v-img>
          </v-avatar>
        </div>
      </v-card>
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
      album_search: '',
      album_result: {},
      search_delay: null,
      api_list_progress_bar: false,
      error_snackbar: false
    }
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
    api_search_album() {
      if (this.album_search.length === 0) {
        clearTimeout(this.search_delay);
        this.search_delay = null;
        this.album_result = {};
      } else {
        if (this.search_delay) {
          clearTimeout(this.search_delay);
          this.search_delay = null;
        }

        this.search_delay = setTimeout(() => {
          this.error_snackbar = false;
          this.$http.get(this.$api_url + 'discogs/search_album.php?search=' + this.album_search)
              .then(result => {
                this.album_result = result.data;
              })
              .catch(() => {
                this.error_snackbar = true;
              })
        }, 800);
      }
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