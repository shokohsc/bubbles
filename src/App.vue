<template>
    <Header />
    <router-view />
    <Footer />
</template>

<script>
import Header from './components/Header.vue';
import Footer from './components/Footer.vue';
import 'virtual:fonts.css'

export default {
  components: {
    Header,
    Footer
  }
};
</script>

<style>
body {
  font-family: "Code New Roman";
}
body::-webkit-scrollbar {
  display: none;
}
html {
  background-color: #23232e;
  height: 100%
}
</style>
