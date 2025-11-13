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
      'Cache-Control': dayjs(params.date).isSameOrBefore() ? 'public, max-age=300' : 'no-cache'
    }
    return api.get('/comics/week', { params, headers })
  },
  comics(query = '', pageSize = 10, page = 1) {
    const params = {
      query: query,
      limit: pageSize,
      offset: (pageSize * page) - pageSize
    }
    const headers = {
      'X-Requested-With': 'XMLHttpRequest',
      'Cache-Control': 'public, max-age=300'
    }
    return api.get('/comics', { params, headers })
  },
  creatorComics(id, pageSize = 10, page = 1) {
    const params = {
      limit: pageSize,
      offset: (pageSize * page) - pageSize
    }
    const headers = {
      'X-Requested-With': 'XMLHttpRequest',
      'Cache-Control': 'public, max-age=300'
    }
    return api.get(`/creators/${id}/comics`, { params, headers } )
  },
  serieComics(id, pageSize = 10, page = 1) {
    const params = {
      limit: pageSize,
      offset: (pageSize * page) - pageSize
    }
    const headers = {
      'X-Requested-With': 'XMLHttpRequest',
      'Cache-Control': 'public, max-age=300'
    }
    return api.get(`/series/${id}/comics`, { params, headers } )
  }
}
