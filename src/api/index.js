import axios from 'axios'
import getEnv from '../utils/env'
import moment from 'moment'

const apiConfig = {
  protocol: window.location.protocol,
  host: getEnv('API_GATEWAY_HOST')
}

const api = axios.create({
  baseURL: apiConfig.protocol + '//' + apiConfig.host + '/api/v1/marvel'
  // timeout: 1000
})

export default {
  comicsWeek(date = '') {
    const params = {
      date: '' !== date ? date : moment().format('YYYY-MM-DD')
    }
    return api.get('/comics/week', { params })
  },
  comic(id) {
    return api.get(`/comics/${id}`)
  },
  comics(query = '', pageSize = 10, page = 1) {
    const params = {
      query: query,
      limit: pageSize,
      offset: (pageSize * page) - pageSize
    }
    return api.get('/comics', { params })
  },
  characterComics(id, pageSize = 10, page = 1) {
    const params = {
      limit: pageSize,
      offset: (pageSize * page) - pageSize
    }
    return api.get(`/characters/${id}/comics`, { params } )
  },
  character(id) {
    return api.get(`/characters/${id}`)
  },
  characters(query = '', pageSize = 10, page = 1) {
    const params = {
      query: query,
      limit: pageSize,
      offset: (pageSize * page) - pageSize
    }
    return api.get('/characters', { params })
  },
  creatorComics(id, pageSize = 10, page = 1) {
    const params = {
      limit: pageSize,
      offset: (pageSize * page) - pageSize
    }
    return api.get(`/creators/${id}/comics`, { params } )
  },
  creator(id) {
    return api.get(`/creators/${id}`)
  },
  creators(query = '', pageSize = 10, page = 1) {
    const params = {
      query: query,
      limit: pageSize,
      offset: (pageSize * page) - pageSize
    }
    return api.get('/creators', { params })
  },
  eventComics(id, pageSize = 10, page = 1) {
    const params = {
      limit: pageSize,
      offset: (pageSize * page) - pageSize
    }
    return api.get(`/events/${id}/comics`, { params } )
  },
  event(id) {
    return api.get(`/events/${id}`)
  },
  events(query = '', pageSize = 10, page = 1) {
    const params = {
      query: query,
      limit: pageSize,
      offset: (pageSize * page) - pageSize
    }
    return api.get('/events', { params })
  },
  serieComics(id, pageSize = 10, page = 1) {
    const params = {
      limit: pageSize,
      offset: (pageSize * page) - pageSize
    }
    return api.get(`/series/${id}/comics`, { params } )
  },
  serie(id) {
    return api.get(`/series/${id}`)
  },
  series(query = '', pageSize = 10, page = 1) {
    const params = {
      query: query,
      limit: pageSize,
      offset: (pageSize * page) - pageSize
    }
    return api.get('/series', { params })
  },
  storyComics(id, pageSize = 10, page = 1) {
    const params = {
      limit: pageSize,
      offset: (pageSize * page) - pageSize
    }
    return api.get(`/stories/${id}/comics`, { params } )
  },
  story(id) {
    return api.get(`/stories/${id}`)
  },
  stories(query = '', pageSize = 10, page = 1) {
    const params = {
      query: query,
      limit: pageSize,
      offset: (pageSize * page) - pageSize
    }
    return api.get('/stories', { params })
  }
}
