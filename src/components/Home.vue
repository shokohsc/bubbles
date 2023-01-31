<template>
  <section class="section">
    <h1 class="title has-text-light has-text-centered">{{ title(formattedDate) }}</h1>

    <section >
      <List v-if="hasComics" :comics="comics" />

      <nav class="pagination is-centered" role="navigation" aria-label="pagination">
        <ul class="pagination-list">
          <router-link :to="previousMonth">
            <li>
              <span class="icon is-large has-text-white">
                <i class="fas fa-arrow-left" />
              </span>
            </li>
          </router-link>
          <router-link :to="previousWeek">
            <li>
              <span class="icon is-large has-text-white">
                <i class="fas fa-chevron-left" />
              </span>
            </li>
          </router-link>
          <router-link :to="nextWeek">
            <li>
              <span class="icon is-large has-text-white">
                <i class="fas fa-chevron-right" />
              </span>
            </li>
          </router-link>
          <router-link :to="nextMonth">
            <li>
              <span class="icon is-large has-text-white">
                <i class="fas fa-arrow-right" />
              </span>
            </li>
          </router-link>
        </ul>
      </nav>
    </section>
  </section>
</template>

<script>
import api from '../api';
import dayjs from 'dayjs';
import List from './Comic/List.vue';

export default {
  components: {
    List
  },
  data() {
    return {
      loaded: false,
      date: '',
      comics: []
    }
  },
  created() {
    window.scrollTo(0, 0);
    this.$watch(
      () => this.$route.query.date,
      async () => {
        this.date = this.$route.query.hasOwnProperty('date') ? this.$route.query.date : ''
        await this.fetchData(this.date)
        document.title = this.title(`Bubbles - ${this.formattedDate}`)
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
    async fetchData(date = '') {
      this.loaded = false
      try {
        this.comics = []
        const response = await api.comicsWeek(date)
        this.date = response.data.date
        response.data.comics.forEach(comic => {
          comic.route = { name: 'Comic', params: { id: comic.comicId } }
          this.comics.push(comic);
        });
        this.loaded = true
        window.scrollTo(0, 0);
      } catch (e) {
        console.log(e);
      }
    }
  },
  computed: {
    hasComics: function() {
      return 0 < this.comics.length
    },
    formattedDate: function() {
      if (!this.loaded)
        return 'Loading'
      return '' !== this.date ? dayjs().format('YYYY-MM-DD') === this.date ? 'This week' : `Released on the ${dayjs(this.date).format('MMM DD, YYYY')}` : ''
    },
    previousMonth: function() {
      return { name: 'Home', query: { date: dayjs(this.date).subtract(1, 'months').format('YYYY-MM-DD') } }
    },
    previousWeek: function() {
      return { name: 'Home', query: { date: dayjs(this.date).subtract(1, 'weeks').format('YYYY-MM-DD') } }
    },
    nextMonth: function() {
      return { name: 'Home', query: { date: dayjs(this.date).add(1, 'months').format('YYYY-MM-DD') } }
    },
    nextWeek: function() {
      return { name: 'Home', query: { date: dayjs(this.date).add(1, 'weeks').format('YYYY-MM-DD') } }
    }
  }
}
</script>
