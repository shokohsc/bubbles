<template>
  <h1 class="title has-text-light has-text-centered">{{ title(formattedTitle) }}</h1>

  <section v-if="hasResults">
    <List :results="results" />

    <nav class="pagination is-centered">
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
    </nav>
  </section>
</template>

<script>
  import api from '../api';
  import Paginate from './Paginate.vue';
  import List from './Result/List.vue';
  import { useSearchStore } from '../stores/search'

  export default {
    components: {
      List,
      Paginate
    },
    data() {
      return {
        store: useSearchStore(),
        total: 0,
        pageSize: 10,
        results: [],
        entityId: '',
        entityMap: new Map([
          ['series', 'serie'],
          ['comics', 'comic'],
          ['characters', 'character'],
          ['creators', 'creator'],
          ['events', 'event'],
          ['stories', 'story']
        ])
      }
    },
    computed: {
      q: function() {
        return this.store.q
      },
      page: function() {
        return parseInt(this.store.page)
      },
      entity: function() {
        return this.store.entity
      },
      hasResults: function() {
        return 0 < this.results.length
      },
      formattedTitle: function() {
        if (this.hasResults)
          return `Results for '${this.$route.query.q}'`
        return `No results for '${this.$route.query.q}'`
      },
      totalPages: function() {
        return Math.ceil(this.total / this.pageSize)
      },
      entitySingular: function() {
        return this.entityMap.get(this.store.entity)
      }
    },
    async created() {
      window.scrollTo(0, 0);
      this.store.$patch({ q: this.$route.query.hasOwnProperty('q') ? this.$route.query.q : '' })
      this.store.$patch({ page: this.$route.query.hasOwnProperty('page') ? this.$route.query.page : 1 })
      this.store.$patch({ entity: this.$route.params.entity ? this.$route.params.entity : 'series' })
      this.$watch(
        () => [this.$route.query.q, this.$route.params.entity, this.$route.query.page].join(),
        async () => {
          this.store.$patch({ q: this.$route.query.hasOwnProperty('q') ? this.$route.query.q : '' })
          this.store.$patch({ page: this.$route.query.hasOwnProperty('page') ? this.$route.query.page : 1 })
          this.store.$patch({ entity: this.$route.params.entity ? this.$route.params.entity : 'series' })
          await this.fetchData(this.store.page)
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
        try {
          this.store.$patch({ page: page })
          this.results = []
          const response = await api[`${this.store.entity}`](this.store.q, pageSize, page)
          this.total = response.data.total
          response.data[this.store.entity].forEach(result => {
            result.route = { name: 'EntityComics', params: { entity: this.entitySingular, id: result[`${this.entitySingular}Id`] } }
            if ('comics' === this.store.entity) {
              result.route = { name: 'Comic', params: { id: result[`${this.entitySingular}Id`] } }
            }
            result.title = result.title || result.name || result.fullName
            this.results.push(result);
          });
          window.scrollTo(0, 0);
        } catch (e) {
          console.log(e);
        }
      },
      async paginate(page = 1) {
        this.$router.push({ name: 'EntityResults', params: { entity: this.store.entity }, query: { q: this.store.q, page: page }})
      }
    }
  }
</script>

<style>
  li.is-current a {
    background-color: #485fc7;
    border-color: #485fc7;
  }
</style>
