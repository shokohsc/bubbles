import axios from 'axios';
import getEnv from '../utils/env';

const graphqlConfig = {
  protocol: window.location.protocol,
  host: getEnv('GRAPHQL_GATEWAY_HOST')
};

const graphql = axios.create({
  baseURL: graphqlConfig.protocol + '//' + graphqlConfig.host + '/',
  headers: {
    'Cache-Control': 'max-age=600'
  }
  // timeout: 500
});

export default {
    browse(directory = '', page = 1, pageSize = 10) {
        return graphql.post('/', {
          query: `
            {
              browse(
                directory: "${directory}"
                page: ${page}
                pageSize: ${pageSize}
              ){
                rows { urn name type cover }
                total
                totalPages
                page
                pageSize
              }
            }
          `
        });
    },
    read(urn) {
        return graphql.post('/', {
          query: `
            {
              read(
                book: "${urn}"
              ){
                rows { image }
              }
            }
          `
        });
    },
    search(query = '', page = 1, pageSize = 10) {
        return graphql.post('/', {
          query: `
            {
              search(
                query: "${query}"
                page: ${page}
                pageSize: ${pageSize}
              ){
                rows { urn name type cover }
                total
                totalPages
                page
                pageSize
              }
            }
          `
        });
    }
};
