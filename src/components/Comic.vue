<template>
  <section class="section">
    <div class="columns" v-if="loaded">

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
            <a :href="encrypt(comic.metadata.url)" target="_blank" :title="comic.title">
              <span class="icon">
                <i class="fas fa-link" />
              </span>
            </a>
          </small>
        </h2>
        <table class="table dark-table is-narrow is-fullwidth">
          <tbody>
            <tr class="is-full">
              <td>
                <strong>
                  Publishing date
                </strong>
              </td>
              <td>
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
                <router-link :to="serieComicsRoute(comic.metadata.series.id)">
                  {{ title(comic.metadata.series.title) }}
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
                <span v-for="(person, i) in creator.persons" :key="i" class="is-capitalized mr-2">
                  <router-link :to="creatorComicsRoute(person.id)">
                    {{ title(person.full_name) }}
                  </router-link>
                </span>
              </td>
            </tr>

          </tbody>
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
  import dayjs from 'dayjs';
  import VueEasyLightbox from 'vue-easy-lightbox';
  import { useComicsStore } from '../stores/comics'

  export default {
    components: {
      VueEasyLightbox
    },
    computed: {
      src: function() {
        return this.encrypt(this.comic.thumbnail.path || '') + '/portrait_incredible.' + this.comic.thumbnail.extension || ''
      },
      detail: function() {
        return this.encrypt(this.comic.thumbnail.path || '') + '/detail.' + this.comic.thumbnail.extension || ''
      },
      publishingDate: function() {
        return dayjs(this.comic.metadata.published_date || null).format('MMMM DD, YYYY')
      },
      weekRoute: function() {
        return { name: "Home", query: { date: dayjs(this.comic.metadata.published_date || null).format('YYYY-MM-DD') } }
      },
      series: function() {
        return this.comic.metadata.series.title || ''
      },
      creators: function() {
        return Object.entries(this.comic.metadata.creators).map(creator => { return { role: creator[0], persons: creator[1] } });
      }
    },
    data() {
      return {
        loaded: false,
        store: useComicsStore(),
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
      serieComicsRoute(id) {
        return { name: "EntityComics", params: { entity: 'serie', id } }
      },
      creatorComicsRoute(id) {
        return { name: "EntityComics", params: { entity: 'creator', id } }
      },
      async fetchData(comicId) {
        this.loaded = false
        try {
          this.comic = this.store.comics.find(comic => comic.comicId === comicId)
          
          this.loaded = true
        } catch (e) {
          console.log(e);
        }
      },
      onShow() {
        this.visible = true
      },
      showSingle() {
        this.imgs = [this.detail]
        // or
        // this.imgs  = {
        //   title: 'this is a placeholder',
        //   src: 'http://via.placeholder.com/350x150'
        // }
        this.onShow()
      },
      onHide() {
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

table.table tr:first-child td {
  border-top: none;
}
table.table tr:last-child td {
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
