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
    <v-col cols="6">
      <!--      TODO Transform into component-->
      <v-toolbar>
        <v-toolbar-title>Rechercher un album</v-toolbar-title>
        <v-autocomplete
            v-model="album_autocomplete.album_select"
            :loading="album_autocomplete.loading"
            :items="this.album_autocomplete.autocomplete_items"
            :search-input.sync="search"
            item-text="title"
            item-value="id"
            flat hide-no-data hide-details solo-inverted
            clearable
            solo
            class="mx-4"
        >
          <template v-slot:selection="{ item }">
            {{ item.title }} <strong class="ml-2">{{ item.year }}</strong>
          </template>

          <template v-slot:item="{ item }">
            <v-list-item-content>
              <v-list-item-title v-text="item.title"></v-list-item-title>
              <v-list-item-subtitle v-text="item.year"></v-list-item-subtitle>
            </v-list-item-content>
            <v-list-item-avatar size="60" rounded>
              <v-img :src="item.thumb"></v-img>
            </v-list-item-avatar>
          </template>

        </v-autocomplete>
      </v-toolbar>
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
      api_list_progress_bar: false,

      search: null,
      album_autocomplete: {
        album_select: null,
        loading: false,
        album_api_result: {},
        search_delay: null,
        select: null,
        autocomplete_items: []
      },
      error_snackbar: false
    }
  },
  mounted() {
    this.api_get_user_playlist();
  },
  watch: {
    search(val) {
      if (val && val.length > 0) {
        this.api_search_album(val);
      } else {
        clearTimeout(this.album_autocomplete.search_delay);
        this.album_autocomplete.search_delay = null;
      }
    }
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
    api_search_album(val) {
      clearTimeout(this.album_autocomplete.search_delay);
      this.album_autocomplete.search_delay = null;
      this.album_autocomplete.album_api_result = {};
      this.album_autocomplete.autocomplete_items = [];

      this.album_autocomplete.search_delay = setTimeout(() => {
        this.error_snackbar = false;
        this.album_autocomplete.loading = true;
        this.$http.get(this.$api_url + 'discogs/search_album.php?search=' + val)
            .then(result => {
              this.album_autocomplete.album_api_result = result.data;
              this.process_album_search_items();
            })
            .catch(() => {
              this.process_album_search_items();
              this.error_snackbar = true;
            })
            .finally(() => {
                  this.album_autocomplete.loading = false;
                }
            )
      }, 800);
    },
    process_album_search_items() {
      this.album_autocomplete.autocomplete_items = [];
      for (let i = 0; i < this.album_autocomplete.album_api_result.results.length; i++) {
        this.album_autocomplete.autocomplete_items[i] = this.album_autocomplete.album_api_result.results[i];
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