<template>
  <nav class="navbar is-black is-fixed-top is-spaced" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">

      <router-link class="navbar-item" to="/" >
        <button class="button">
          <span class="icon">
            <i class="fas fa-home"></i>
          </span>
        </button>
      </router-link>

      <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbar">
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
      </a>
    </div>

    <div id="navbar" class="navbar-menu">

      <div class="navbar-start">
        <router-link class="navbar-item has-text-white" to="/" >
          Bubbles
        </router-link>
        <router-link class="navbar-item has-text-white" to="/about" >
          About
        </router-link>
      </div>

      <div class="navbar-end">
        <div class="navbar-item">
          <div class="field">
            <div class="control">
              <div class="select">
                <select :entity="entity" @change="onChange($event)">
                  <option value="series">Series</option>
                  <option value="comics">Comics</option>
                  <option value="creators">Creators</option>
                  <option value="characters">Characters</option>
                  <option value="events">Events</option>
                  <!-- <option value="stories">Stories</option> -->
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="navbar-item">
          <div class="field">
            <div class="control">
              <input @change="onUpdate($event)" :q="q" class="input has-text-black has-background-grey" type="text" placeholder="Search..." />
            </div>
          </div>
        </div>
        <div class="navbar-item">
          <div class="field">
            <div class="control">
              <button @click="validate" class="button">
                <span class="icon">
                  <i class="fas fa-search"></i>
                </span>
              </button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </nav>
</template>

<script>
export default {
  data() {
    return {
      q: '',
      page: 1,
      entity: '',
    }
  },
  created() {
    document.addEventListener('DOMContentLoaded', () => {
      // Get all "navbar-burger" elements
      const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);
      // Add a click event on each of them
      $navbarBurgers.forEach( el => {
        el.addEventListener('click', () => {
          // Get the target from the "data-target" attribute
          const target = el.dataset.target;
          const $target = document.getElementById(target);
          // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
          el.classList.toggle('is-active');
          $target.classList.toggle('is-active');
        });
      });
    });
    document.addEventListener('keyup', this.keyUp);
    this.page = this.$route.query.hasOwnProperty('page') ? this.$route.query.page : 1
    this.q = this.$route.query.q ? this.$route.query.q : ''
    this.entity = this.$route.params.entity ? this.$route.params.entity : 'series'
    this.$watch(
      () => [this.$route.query.q, this.$route.params.entity, this.$route.query.page].join(),
      async () => {
        this.page = this.$route.query.hasOwnProperty('page') ? this.$route.query.page : 1
        this.entity = this.$route.params.hasOwnProperty('entity') ? this.$route.params.entity : 'series'
        this.q = this.$route.query.hasOwnProperty('q') ? this.$route.query.q : ''
        await this.validate()
      },
      { immediate: true }
    )
  },
  beforeUnmount() {
    document.removeEventListener('keyup', this.keyUp);
  },
  methods: {
    onChange: function(e) {
      this.entity = e.target.value
    },
    onUpdate: function(e) {
      this.q = e.target.value
    },
    keyUp: function(e) {
      switch (e.keyCode) {
        case 13:
          this.validate();
          break;
      }
    },
    async validate() {
      if (this.q.length > 2) {
        this.$router.push({ name: 'EntityResults', params: { entity: this.entity }, query: { q: this.q }})
      }
    },
  }
};
</script>

<style>
nav.navbar {
  background-color: #0a0a0a !important;
}
.navbar-item {
  background-color: #0a0a0a;
}
.navbar-menu {
  background-color: #0a0a0a;
}
a.navbar-item:focus-within {
  background-color: #0a0a0a !important;
}
</style>
