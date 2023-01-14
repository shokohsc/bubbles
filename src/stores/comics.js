import { defineStore } from 'pinia'

export const useComicsStore = defineStore('comics', {
  state: () => ({
    page: 1
  })
})
