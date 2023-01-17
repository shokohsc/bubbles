import axios from 'axios'
import getEnv from '../utils/env'
import dayjs from 'dayjs'
import isSameOrBefore from 'dayjs/plugin/isSameOrBefore'

dayjs.extend(isSameOrBefore)

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
      date: '' !== date ? dayjs(date).format('YYYY-MM-DD') : dayjs().format('YYYY-MM-DD')
    }
    const headers = {
      'X-Requested-With': 'XMLHttpRequest',
      'Cache-Control': dayjs(params.date).isSameOrBefore() ? 'public, max-age=86400' : 'no-cache'
    }
    return api.get('/comics/week', { params, headers })
  },
  comic(id) {
    const headers = {
      'X-Requested-With': 'XMLHttpRequest',
      'Cache-Control': 'public, max-age=86400'
    }
    return api.get(`/comics/${id}`, { headers })
  },
  comics(query = '', pageSize = 10, page = 1) {
    const params = {
      query: query,
      limit: pageSize,
      offset: (pageSize * page) - pageSize
    }
    const headers = {
      'X-Requested-With': 'XMLHttpRequest',
      'Cache-Control': 'public, max-age=86400'
    }
    return api.get('/comics', { params, headers })
  },
  characterComics(id, pageSize = 10, page = 1) {
    const params = {
      limit: pageSize,
      offset: (pageSize * page) - pageSize
    }
    const headers = {
      'X-Requested-With': 'XMLHttpRequest',
      'Cache-Control': 'public, max-age=86400'
    }
    return api.get(`/characters/${id}/comics`, { params, headers } )
  },
  character(id) {
    const headers = {
      'X-Requested-With': 'XMLHttpRequest',
      'Cache-Control': 'public, max-age=86400'
    }
    return api.get(`/characters/${id}`, { headers })
  },
  characters(query = '', pageSize = 10, page = 1) {
    const params = {
      query: query,
      limit: pageSize,
      offset: (pageSize * page) - pageSize
    }
    const headers = {
      'X-Requested-With': 'XMLHttpRequest',
      'Cache-Control': 'public, max-age=86400'
    }
    return api.get('/characters', { params, headers })
  },
  creatorComics(id, pageSize = 10, page = 1) {
    const params = {
      limit: pageSize,
      offset: (pageSize * page) - pageSize
    }
    const headers = {
      'X-Requested-With': 'XMLHttpRequest',
      'Cache-Control': 'public, max-age=86400'
    }
    return api.get(`/creators/${id}/comics`, { params, headers } )
  },
  creator(id) {
    const headers = {
      'X-Requested-With': 'XMLHttpRequest',
      'Cache-Control': 'public, max-age=86400'
    }
    return api.get(`/creators/${id}`, { headers })
  },
  creators(query = '', pageSize = 10, page = 1) {
    const params = {
      query: query,
      limit: pageSize,
      offset: (pageSize * page) - pageSize
    }
    const headers = {
      'X-Requested-With': 'XMLHttpRequest',
      'Cache-Control': 'public, max-age=86400'
    }
    return api.get('/creators', { params, headers })
  },
  eventComics(id, pageSize = 10, page = 1) {
    const params = {
      limit: pageSize,
      offset: (pageSize * page) - pageSize
    }
    const headers = {
      'X-Requested-With': 'XMLHttpRequest',
      'Cache-Control': 'public, max-age=86400'
    }
    return api.get(`/events/${id}/comics`, { params, headers } )
  },
  event(id) {
    const headers = {
      'X-Requested-With': 'XMLHttpRequest',
      'Cache-Control': 'public, max-age=86400'
    }
    return api.get(`/events/${id}`, { headers })
  },
  events(query = '', pageSize = 10, page = 1) {
    const params = {
      query: query,
      limit: pageSize,
      offset: (pageSize * page) - pageSize
    }
    const headers = {
      'X-Requested-With': 'XMLHttpRequest',
      'Cache-Control': 'public, max-age=86400'
    }
    return api.get('/events', { params, headers })
  },
  serieComics(id, pageSize = 10, page = 1) {
    const params = {
      limit: pageSize,
      offset: (pageSize * page) - pageSize
    }
    const headers = {
      'X-Requested-With': 'XMLHttpRequest',
      'Cache-Control': 'public, max-age=86400'
    }
    return api.get(`/series/${id}/comics`, { params, headers } )
  },
  serie(id) {
    const headers = {
      'X-Requested-With': 'XMLHttpRequest',
      'Cache-Control': 'public, max-age=86400'
    }
    return api.get(`/series/${id}`, { headers })
  },
  series(query = '', pageSize = 10, page = 1) {
    const params = {
      query: query,
      limit: pageSize,
      offset: (pageSize * page) - pageSize
    }
    const headers = {
      'X-Requested-With': 'XMLHttpRequest',
      'Cache-Control': 'public, max-age=86400'
    }
    return api.get('/series', { params, headers })
  },
  storyComics(id, pageSize = 10, page = 1) {
    const params = {
      limit: pageSize,
      offset: (pageSize * page) - pageSize
    }
    const headers = {
      'X-Requested-With': 'XMLHttpRequest',
      'Cache-Control': 'public, max-age=86400'
    }
    return api.get(`/stories/${id}/comics`, { params, headers } )
  },
  story(id) {
    const headers = {
      'X-Requested-With': 'XMLHttpRequest',
      'Cache-Control': 'public, max-age=86400'
    }
    return api.get(`/stories/${id}`, { headers })
  },
  stories(query = '', pageSize = 10, page = 1) {
    const params = {
      query: query,
      limit: pageSize,
      offset: (pageSize * page) - pageSize
    }
    const headers = {
      'X-Requested-With': 'XMLHttpRequest',
      'Cache-Control': 'public, max-age=86400'
    }
    return api.get('/stories', { params, headers })
  }
}
