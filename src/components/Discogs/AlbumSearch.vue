<template>
  <v-toolbar>
    <v-toolbar-title>Rechercher un album</v-toolbar-title>
    <v-autocomplete
        v-model="album_autocomplete.album_select"
        :items="album_autocomplete.autocomplete_items"
        :loading="album_autocomplete.loading"
        :search-input.sync="search"
        @change="select_album"
        item-text="title"
        item-value="id"
        no-filter
        flat hide-no-data hide-details solo-inverted
        clearable
        class="mx-4"
    >
      <!--      <template v-slot:selection="{ item }">-->
      <!--        {{ item.title }} <strong class="ml-2">{{ item.year }}</strong>-->
      <!--      </template>-->

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

    <v-snackbar v-model="error_snackbar">
      Erreur Lors de la recherche d'album
      <template v-slot:action="">
        <v-btn
            color="pink"
            @click="error_snackbar = false"
            text>Ok
        </v-btn>
      </template>
    </v-snackbar>
  </v-toolbar>
</template>

<script>
export default {
  name: "AlbumSearch",
  data: function () {
    return {
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
  watch: {
    search(val) {
      if (val) {
        this.api_search_album(val);
      } else {
        clearTimeout(this.album_autocomplete.search_delay);
        this.album_autocomplete.search_delay = null;
      }
    }
  },
  methods: {
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
              this.album_autocomplete.autocomplete_items = this.album_autocomplete.album_api_result.results;
            })
            .catch(() => {
              this.album_autocomplete.autocomplete_items = [];
              this.error_snackbar = true;
            })
            .finally(() => {
                  this.album_autocomplete.loading = false;
                }
            )
      }, 800);
    },
    select_album() {
      this.$emit('selectAlbum', this.album_autocomplete.album_select);
    }
  }
}
</script>

<style scoped>

</style>