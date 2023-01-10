<template>
    <div class="columns is-justify-content-center is-multiline">
      <Cover :cover="cover" v-for="(cover, index) in covers" :key="index" />
    </div>
</template>

<script>
  import Cover from '../Cover.vue';

  export default {
    components: {
      Cover
    },
    computed: {
      covers: function() {
        const covers = []
        this.results.map(result => {
          covers.push({ title: result.title, thumbnail: result.thumbnail, route: result.route })
        })
        return covers
      }
    },
    props: {
      results: Array
    }
  }
</script>
