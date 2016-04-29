"use strict"

/**
 * ComicService class.
 */
class ComicService extends AbstractService{

  /**
   * ComicService constructor method.
   * @return ComicService
   */
  constructor() {
    super('comics')
  }

  /**
   * Fetch week comics
   * @param  string           id
   * @param  undefined|string data
   * @return Promise
   */
  fetchWeek(date) {
    return this.serve('week', date)
  }

  /**
   * Fetch comic
   * @param  int id
   * @return Promise
   */
  fetchComic(id) {
    return this.serve(id)
  }
}
