<template>
  <h1 class="title has-text-light has-text-centered">{{ title(formattedTitle) }}</h1>

  <section v-if="hasResults">
    <List :results="results" />

    <div class="pagination">
      <Paginate
        :page-count="totalPages"
        :click-handler="fetchData"
        :prev-text="'Prev'"
        :next-text="'Next'"
        :container-class="'pagination-list is-justify-content-center'"
        :page-link-class="'pagination-link has-text-white'"
        :next-link-class="'pagination-link has-text-white'"
        :prev-link-class="'pagination-link has-text-white'"
        :active-class="'is-current'"
        :hide-prev-next="true" />
    </div>
  </section>
</template>

<script>
  import api from '../api';
  import Paginate from 'vuejs-paginate-next';
  import List from './Result/List.vue';

  export default {
    components: {
      List,
      Paginate
    },
    data() {
      return {
        q: '',
        page: 1,
        total: 0,
        pageSize: 10,
        results: [],
        entityId: '',
        entity: '',
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
        return this.entityMap.get(this.entity)
      }
    },
    created() {
      window.scrollTo(0, 0);
      this.entity = this.$route.params.entity ? this.$route.params.entity : 'series'
      this.q = this.$route.query.hasOwnProperty('q') ? this.$route.query.q : ''
      this.page = this.$route.query.hasOwnProperty('page') ? this.$route.query.page : 1
      this.$watch(
        () => [this.$route.query.q, this.$route.params.entity, this.$route.query.page].join(),
        async () => {
          this.page = this.$route.query.hasOwnProperty('page') ? this.$route.query.page : 1
          this.entity = this.$route.params.hasOwnProperty('entity') ? this.$route.params.entity : 'series'
          this.q = this.$route.query.hasOwnProperty('q') ? this.$route.query.q : ''
          await this.fetchData(this.page)
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
          this.page = page
          this.results = []
          const response = await api[`${this.entity}`](this.q, pageSize, page)
          this.total = response.data.total
          response.data[this.entity].forEach(result => {
            result.route = { name: 'EntityComics', params: { entity: this.entitySingular, id: result[`${this.entitySingular}Id`] } }
            result.title = result.title || result.name || result.fullName
            this.results.push(result);
          });
          window.scrollTo(0, 0);
        } catch (e) {
          console.log(e);
        }
      }
    }
  }
</script>
