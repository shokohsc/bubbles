<template>
  <h1 class="title has-text-light has-text-centered">{{ title(formattedTitle) }}</h1>

  <section v-if="hasComics">
    <List :comics="comics" />

    <div class="pagination">
      <Paginate
        :page-count="totalPages"
        :click-handler="paginate"
        :prev-text="'Prev'"
        :next-text="'Next'"
        :container-class="'pagination-list is-justify-content-center'"
        :page-link-class="'pagination-link has-text-white'"
        :next-link-class="'pagination-link has-text-white'"
        :prev-link-class="'pagination-link has-text-white'"
        :active-class="'is-current'"
        :hide-prev-next="true"
        :initial-page="page" />
    </div>
  </section>
</template>

<script>
import api from '../api';
import Paginate from './Paginate.vue';
import List from './Comic/List.vue';
import { useComicsStore } from '../stores/comics'

export default {
  components: {
    List,
    Paginate
  },
  data() {
    return {
      loaded: false,
      store: useComicsStore(),
      total: 0,
      pageSize: 10,
      comics: [],
      entityId: '',
      entity: {},
    }
  },
  computed: {
    page: function() {
      return parseInt(this.store.page)
    },
    hasComics: function() {
      return 0 < this.comics.length
    },
    formattedTitle: function() {
      if (!this.loaded)
        return 'Loading'
      if (this.hasComics)
        return this.entity?.name || this.entity?.title || this.entity?.fullName
      return "No comics for " + (this.entity?.name || this.entity?.title || this.entity?.fullName)
    },
    totalPages: function() {
      return Math.ceil(this.total / this.pageSize)
    }
  },
  async created() {
    window.scrollTo(0, 0);
    this.store.$patch({ page: this.$route.query.hasOwnProperty('page') ? this.$route.query.page : 1 })
    this.entityId = this.$route.params.id
    this.$watch(
      () => this.$route.query.page,
      async () => {
        this.store.$patch({ page: this.$route.query.hasOwnProperty('page') ? this.$route.query.page : 1 })
        await Promise.all([this.fetchEntityData(this.entityId), this.fetchData(this.store.page)])
        document.title = this.title(`Bubbles - ${this.formattedTitle}`)
      },
      { immediate: true }
    )
  },
  methods: {
    title(name = '') {
      return name.toLowerCase().replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g, ($charOne) => {
        return $charOne.toUpperCase()
      })
    },
    async fetchData(page = 1, pageSize = 10) {
      this.loaded = false
      try {
        this.store.$patch({ page: page })
        this.comics = []
        const response = await api[`${this.$route.params.entity}Comics`](this.entityId, pageSize, page)
        this.total = response.data.total
        response.data.comics.forEach(comic => {
          comic.route = { name: 'Comic', params: { id: comic.comicId } }
          this.comics.push(comic);
        });
        this.loaded = true
        window.scrollTo(0, 0);
      } catch (e) {
        console.log(e);
      }
    },
    async fetchEntityData(id = '') {
      try {
        if ('' === id) {
          return
        }
        const response = await api[this.$route.params.entity](id)
        this.entity = response.data[this.$route.params.entity]
      } catch (e) {
        console.log(e);
      }
    },
    async paginate(page = 1) {
      this.$router.push({ name: 'EntityComics', params: { entity: this.$route.params.entity }, query: { page: page }})
    }
  }
}
</script>
