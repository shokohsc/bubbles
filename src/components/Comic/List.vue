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
        this.comics.map(comic => {
          covers.push({ title: comic.title, thumbnail: comic.thumbnail, route: comic.route })
        })
        return covers
      }
    },
    props: {
      comics: Array
    }
  }
</script>
