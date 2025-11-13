import { defineStore, acceptHMRUpdate } from 'pinia'

const useComicsStore = defineStore('comics', {
  state: () => ({
    page: 1,
    comics: []
  })
})

if (import.meta.hot) {
  import.meta.hot.accept(acceptHMRUpdate(useComicsStore, import.meta.hot))
}

export { useComicsStore }
