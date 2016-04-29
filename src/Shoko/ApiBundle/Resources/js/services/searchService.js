"use strict"

/**
 * SearchService class.
 */
class SearchService extends AbstractService{

  /**
   * SearchService constructor method.
   * @return SearchService
   */
  constructor() {
    super('search')
  }

  /**
   * Fetch characters|comics|creators|events|series
   * @param string id
   * @param string resource
   * @param int    page
   * @return Promise
   */
  fetchResults(id, resource, page) {
    return this.serve(id+'/'+resource, page)
  }
}
