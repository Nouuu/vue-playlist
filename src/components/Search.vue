<template>
  <v-row>
    <v-col cols="12">
      <h1>
        Recherche
      </h1>
      <v-divider></v-divider>
    </v-col>
    <v-col cols="12" md="8" lg="7">
      <v-card shaped>
        <album-search @selectAlbum="select_album($event)"></album-search>
      </v-card>
    </v-col>
  </v-row>
</template>

<script>
import AlbumSearch from "@/components/Discogs/AlbumSearch";

export default {
  name: "Music",
  data: function () {
    return {}
  },
  components: {
    'album-search': AlbumSearch
  },
  methods: {
    select_album(id) {
      this.show_album(id)
    },
    show_album(album_id) {
      this.$router.push('/search/album/' + album_id);
    }
  }
}
</script>

<style scoped>

</style>