"use strict"

/**
 * AbstractService class.
 */
class AbstractService {

  /**
   * AbstractService constructor method.
   * @param  string endpoint
   * @return AbstractService
   */
  constructor(endpoint) {
    this.protocol  = location.protocol+'//'
    this.host      = location.host+'/'
    this.endpoint  = 'api/'+endpoint
    this.url       = this.protocol+this.host+this.endpoint
  }

  /**
   * Serve ajax call
   * @param  string id
   * @param  string page
   * @return Promise
   */
  serve(id, page) {
    var url       = this.url,
        url       = (id === undefined) ? url : url+'/'+id,
        url       = (page === undefined) ? url : url+'/'+page

    return $.ajax({
      url: url
    }).fail(function(xhr) {
      mount('bubbles-error', {xhr: xhr})
    })
  }

  /**
   * Fetch comics
   * @param string id
   * @param string resource
   * @param int    page
   * @return Promise
   */
  fetchComics(id, resource, page) {
    return this.serve(id+'/'+resource, page)
  }
}
