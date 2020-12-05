<template>
  <v-app>
    <v-row>
      <v-col cols="12" lg="4">

        <v-card class="d-inline-block mx-auto" v-if="!api_loading_list">

          <!--            <v-avatar class="ma-3" size="300" tile>
                        <v-img :src="list.image_list"></v-img>
                      </v-avatar>-->
          <div class="ma-3">
            <v-img v-if="list.image_list" contain max-width="600px"
                   :src="list.image_list"></v-img>
          </div>
          <v-card-title class="headline">
            {{ list.name_list }}
          </v-card-title>

          <v-card-text>
            <ul>
              <li v-if="owner">Propriétaire</li>
              <li>Créateur : {{ list.user_pseudo }}</li>
              <li>Date de création : {{ list.date_creation_list }}</li>
              <li>Nombre d'album : {{ list.album_count }}</li>
            </ul>
          </v-card-text>

        </v-card>
        <v-skeleton-loader type="card" class="d-inline-block mx-auto" min-width="300" v-if="api_loading_list">
        </v-skeleton-loader>
      </v-col>
      <v-col cols="12" lg="8">
        <v-card>
          <v-card-title class="d-flex align-baseline">
            Albums
            <v-spacer></v-spacer>
            <v-text-field
                v-model="list_searchbar"
                append-icon="mdi-magnify"
                label="Rechercher"
                single-line
                hide-details>
            </v-text-field>
            <v-btn icon color="green"
                   @click="api_get_user_list">
              <v-icon>mdi-cached</v-icon>
            </v-btn>

          </v-card-title>
          <v-progress-linear
              indeterminate
              v-if="api_loading_list"></v-progress-linear>
          <v-data-table
              v-if="!api_loading_list"
              :headers="list_header"
              :items="list.albums"
              :search="list_searchbar"
          >

            <template v-slot:item.album.image="{item}">
              <div class="my-2">
                <v-img :src="item.album.image" :alt="item.album.image" height="75px" max-width="75px" contain></v-img>
              </div>
            </template>

            <template v-slot:item.album.title="{item}">
              <div @click="showAlbum(item)" class="text-decoration-underline font-weight-bold" style="cursor: pointer">
                {{item.album.title}}
              </div>
            </template>
            <template v-slot:item.album.artist.name="{item}">
              <div @click="showArtist(item)" class="text-decoration-underline font-weight-bold" style="cursor: pointer">
                {{item.album.artist.name}}
              </div>
            </template>
          </v-data-table>
        </v-card>
      </v-col>
    </v-row>
  </v-app>
</template>

<script>
import {UserFunctions} from "@/components/functions/User";

export default {
  name: "ListView",
  data: function () {
    return {
      list_id: null,
      list: {},
      list_header: [
        {
          text: 'Album',
          align: 'start',
          value: 'album.title'
        },
        {
          text: 'Artiste',
          align: 'start',
          value: 'album.artist.name'
        },
        {
          text: 'Commentaire',
          align: 'start',
          value: 'note'
        },
        {
          text: 'Note',
          align: 'start',
          value: 'grade'
        },
        {
          text: 'Couverture d\'album',
          align: 'start',
          value: 'album.image'
        }
      ],
      list_searchbar: '',
      api_loading_list: true,
      connectedUser: null,
    }
  },
  mounted() {
    this.list_id = this.$route.params.list_id;
    this.api_get_user_list();
    this.connectedUser = UserFunctions.getConnectedUser();
  },
  computed: {
    owner: function () {
      return this.connectedUser.email_user === this.list.user_email_fk;
    }
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
    },
    showAlbum(e) {
      this.$router.push('/search/album/' + e.album.id)
    },
    showArtist(e) {
      this.$router.push('/search/artist/' + e.album.artist.id)
    }
  }
}
</script>

<style scoped>

</style>