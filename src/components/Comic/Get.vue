<template>
  <section class="section">
    <div class="columns">

      <div class="column is-4">
        <figure class="image portrait">
          <div class="hovered" @click="showSingle">
            <img :src="src" :alt="comic.title" loading="lazy" class="cover" width="216" height="324"/>
          </div>
        </figure>
      </div>

      <div class="column is-8">
        <h2 class="title has-text-light">
          {{ comic.title }}
          <small>
            <a :href="encrypt(comic.urls[0].url)" target="_blank" :title="comic.title">
              <span class="icon">
                <i class="fas fa-link" />
              </span>
            </a>
          </small>
        </h2>
        <table class="table dark-table is-narrow is-fullwidth">
          <tr class="is-full">
            <td class="top-table">
              <strong>
                Publishing date
              </strong>
            </td>
            <td class="top-table">
              <router-link :to="weekRoute">
                {{ publishingDate }}
              </router-link>
            </td>
          </tr>

          <tr>
            <td>
              <strong>
                Series
              </strong>
            </td>
            <td>
              <router-link :to="serieComicsRoute(comic.series.resourceURI)">
                {{ title(comic.series.name) }}
              </router-link>
            </td>
          </tr>

          <tr v-for="(creator, index) in creators" :key="index">
            <td>
              <strong class="is-capitalized">
                {{ creator.role }}
              </strong>
            </td>
            <td>
              <span class="is-capitalized">
                <router-link :to="creatorComicsRoute(creator.resourceURI)">
                  {{ title(creator.name) }}
                </router-link>
              </span>
            </td>
          </tr>

          <tr v-if="hasCharacters">
            <td>
              <strong>
                Characters
              </strong>
            </td>
            <td>
              <div class="tags">
                <span class="tag is-info is-capitalized" v-for="(character, index) in characters" :key="index">
                  <router-link class="has-text-light" :to="characterComicsRoute(character.resourceURI)">
                    {{ character.name }}
                  </router-link>
              </span>
              </div>
            </td>
          </tr>

          <tr v-if="hasEvents">
            <td>
              <strong>
                Events
              </strong>
            </td>
            <td>
              <span class="is-capitalized" v-for="(event, index) in events" :key="index">
                <router-link :to="eventComicsRoute(event.resourceURI)">
                  {{ title(event.name) }}
                </router-link>
              </span>
            </td>
          </tr>

          <tr v-if="hasStories">
            <td>
              <strong>
                Stories
              </strong>
            </td>
            <td>
              <span class="is-capitalized" v-for="(story, index) in stories" :key="index">
                <router-link :to="storyComicsRoute(story.resourceURI)">
                  {{ title(story.name) }}
                </router-link>
              </span>
            </td>
          </tr>
        </table>

        <p v-html="comic.description" class="has-text-light" />
      </div>
    </div>

    <VueEasyLightbox
        :visible="visible"
        :imgs="imgs"
        :index="index"
        @hide="onHide" />
  </section>
</template>

<script>
  import api from '../../api';
  import dayjs from 'dayjs';
  import VueEasyLightbox from 'vue-easy-lightbox';

  export default {
    components: {
      VueEasyLightbox
    },
    computed: {
      src: function() {
        return this.encrypt(this.comic?.thumbnail?.path) + '/portrait_incredible.' + this.comic?.thumbnail?.extension
      },
      detail: function() {
        return this.encrypt(this.comic?.thumbnail?.path) + '/detail.' + this.comic?.thumbnail?.extension
      },
      publishingDate: function() {
        return dayjs(this.comic?.dates[0].date).format('MMMM DD, YYYY')
      },
      weekRoute: function() {
        return { name: "Home", query: { date: dayjs(this.comic?.dates[0].date).format('YYYY-MM-DD') } }
      },
      series: function() {
        return this.comic?.series.name
      },
      hasEvents: function() {
        return this.comic?.events?.items.length > 0
      },
      events: function() {
        return this.comic?.events?.items
      },
      hasCharacters: function() {
        return this.comic?.characters?.items.length > 0
      },
      characters: function() {
        return this.comic?.characters?.items
      },
      creators: function() {
        return this.comic?.creators?.items
      },
      hasStories: function() {
        return this.comic?.stories?.items.length > 0
      },
      stories: function() {
        return this.comic?.stories?.items
      }
    },
    data() {
      return {
        comic: {},
        visible: false,
        index: 0,
        imgs: []
      }
    },
    created() {
      this.$watch(
        () => this.$route.params.id,
        async () => {
          await this.fetchData(this.$route.params.id)
          document.title = this.title(`Bubbles - ${this.comic.title}`)
        },
        { immediate: true }
      )
    },
    methods: {
      encrypt(protocol = '') {
        return protocol.replace('http://', 'https://')
      },
      title(name = '') {
        return name.toLowerCase().replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g, ($charOne) => {
          return $charOne.toUpperCase()
        })
      },
      resourceURIToId(resourceURI = '') {
        return resourceURI.split('/').reverse()[0]
      },
      characterComicsRoute(resourceURI) {
        return { name: "EntityComics", params: { entity: 'character', id: this.resourceURIToId(resourceURI) } }
      },
      serieComicsRoute(resourceURI) {
        return { name: "EntityComics", params: { entity: 'serie', id: this.resourceURIToId(resourceURI) } }
      },
      eventComicsRoute(resourceURI) {
        return { name: "EntityComics", params: { entity: 'event', id: this.resourceURIToId(resourceURI) } }
      },
      creatorComicsRoute(resourceURI) {
        return { name: "EntityComics", params: { entity: 'creator', id: this.resourceURIToId(resourceURI) } }
      },
      storyComicsRoute(resourceURI) {
        return { name: "EntityComics", params: { entity: 'story', id: this.resourceURIToId(resourceURI) } }
      },
      async fetchData(comicId) {
        try {
          const response = await api.comic(comicId)
          this.comic = response.data.comic
          // Remove stories for now
          this.comic.stories.items = []
        } catch (e) {
          console.log(e);
        }
      },
      onShow() {
        this.visible = true
      },
      showSingle() {
        this.imgs = [this.detail]
        // or
        // this.imgs  = {
        //   title: 'this is a placeholder',
        //   src: 'http://via.placeholder.com/350x150'
        // }
        this.onShow()
      },
      onHide() {
        this.visible = false
      }
    }
  };
</script>

<style>
table.dark-table {
  background-color: #23232E;
  color: white;
}

table.dark-table tr td strong {
  color: white;
}

td.top-table {
  border-top: 1px solid #dbdbdb;
}

td.bottom-table {
  border-bottom: none;
}

figure.portrait img {
  padding: 4px;
  background-color: #0a0a0a;
  border: 1px solid #23232e;
  border-radius: 4px;
  -webkit-transition: all 0.2s ease-in-out;
  -o-transition: all 0.2s ease-in-out;
  transition: all 0.2s ease-in-out;
}
figure.portrait img:hover {
  background: #EB2025;
}

div.vel-modal {
  background-color: #0a0a0a;
  opacity: 95%;
}
</style>
