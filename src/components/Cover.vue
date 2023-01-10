<template>
  <div class="column is-narrow">
      <div class="box has-background-black">
        <router-link :to="route">
          <div class="block">
            <figure class="image cover">
              <img :src="src" :alt="title" loading="lazy" class="cover" />
            </figure>
          </div>
          <div class="block has-text-white has-text-centered">
            <div style="width: 216px; margin: 0 auto;">
              {{ title }}
            </div>
          </div>
        </router-link>
      </div>
  </div>
</template>

<script>
  import defaultImage from '../assets/images/marvel_default.jpg'

  export default {
    computed: {
      src: function() {
        if (null !== this.cover.thumbnail)
          return this.cover.thumbnail.path + '/portrait_incredible.' + this.cover.thumbnail.extension
        return defaultImage
      },
      title: function() {
        return this.cover.title
      },
      route: function() {
        return this.cover.route
      }
    },
    props: {
      cover: Object
    }
  }
</script>

<style>
img.cover {
  width: 226px;
  height: 334px;
  margin: 0 auto;
}

figure.cover img {
  padding: 4px;
  background-color: #0a0a0a;
  border: 1px solid #0a0a0a;
  border-radius: 4px;
  -webkit-transition: all 0.2s ease-in-out;
  -o-transition: all 0.2s ease-in-out;
  transition: all 0.2s ease-in-out;
}
figure.cover img:hover {
  background: #EB2025;
}
</style>
