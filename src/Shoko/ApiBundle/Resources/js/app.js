"use strict"

/**************
 * Prototypes *
 **************/

/**
 * First letter of any word uppercase, then lowercase
 * @return string
 */
String.prototype.title = function() {
  var string = this.toString().toLowerCase()

  return string.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g, function($charOne){
    return $charOne.toUpperCase()
  })
}

/**
 * Replace http:// by https:// in String
 * @return string
 */
String.prototype.encrypt = function() {
  var string = this.toString()

  return string.replace('http://', 'https://')
}

/**
 * Transform resourceURI to Id
 * @return string
 */
String.prototype.resourceURIToId = function() {
  var string = this.toString()

  return string.split('/').reverse()[0]
}


/*******************
 * AbstractService *
 *******************/

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
    var url       = self.url,
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


/*********************
 * Endpoint Services *
 *********************/

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

/**
 * SerieService class.
 */
class SerieService extends AbstractService{

  /**
   * SerieService constructor method.
   * @return SerieService
   */
  constructor() {
    super('series')
  }
}

/**
 * CreatorService class.
 */
class CreatorService extends AbstractService{

  /**
   * CreatorService constructor method.
   * @return CreatorService
   */
  constructor() {
    super('creators')
  }
}

/**
 * CharacterService class.
 */
class CharacterService extends AbstractService{

  /**
   * CharacterService constructor method.
   * @return CharacterService
   */
  constructor() {
    super('characters')
  }
}

/**
 * EventService class.
 */
class EventService extends AbstractService{

  /**
   * EventService constructor method.
   * @return EventService
   */
  constructor() {
    super('events')
  }
}

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
   * Fetch series
   * @param string id
   * @param string resource
   * @param int    page
   * @return Promise
   */
  fetchSeries(id, resource, page) {
    return this.serve(id+'/'+resource, page)
  }

  /**
   * Fetch creators
   * @param string id
   * @param string resource
   * @param int    page
   * @return Promise
   */
  fetchCreators(id, resource, page) {
    return this.serve(id+'/'+resource, page)
  }

  /**
   * Fetch characters
   * @param string id
   * @param string resource
   * @param int    page
   * @return Promise
   */
  fetchCharacters(id, resource, page) {
    return this.serve(id+'/'+resource, page)
  }

  /**
   * Fetch events
   * @param string id
   * @param string resource
   * @param int    page
   * @return Promise
   */
  fetchEvents(id, resource, page) {
    return this.serve(id+'/'+resource, page)
  }
}


/****************
 * Tags Handler *
 ****************/

/**
 * currentTag
 * @type string
 */
var currentTag = null

/**
 * routes
 * @type Object
 */
var routes = {}

/**
 * Synchronize title
 * @param Object data
 */
function syncTitle(data) {
  if (pageTitle || (data && data.hasOwnProperty('title'))) {
    $('h1.text-center').text(pageTitle || data.title)
    document.title = pageTitle || data.title
  }
  pageTitle = undefined
}

/**
 * Mount tag
 * @param  string tag
 * @param  Object options
 */
function mount(tag, options) {
  currentTag && currentTag.unmount(true)
  currentTag = riot.mount('#content', tag, options)[0]
}

/**
 * Routes handler
 * @param  string collection
 * @param  string id
 * @param  string action
 */
function handler(collection, id, resource, page) {
  var fn = routes[collection || 'home']
  fn ? fn(id, resource, page) : mount('bubbles-error')
}


/******************
 * Routes Handler *
 ******************/

/**
 * comicService
 * @type ComicService
 */
var comicService = new ComicService()

/**
 * serieService
 * @type SerieService
 */
var serieService = new SerieService()

/**
 * creatorService
 * @type CreatorService
 */
var creatorService = new CreatorService()

/**
 * characterService
 * @type CharacterService
 */
var characterService = new CharacterService()

/**
 * eventService
 * @type EventService
 */
var eventService = new EventService()

/**
 * searchService
 * @type SearchService
 */
var searchService = new SearchService()

/**
 * Home route definition
 */
routes.home = function() {
  mount('bubbles-loading')
  comicService.fetchWeek().done(function(comics) {
    mount('bubbles-week', comics)
  })
}

/**
 * Week route definition
 * @param  string date
 */
routes.week = function(date) {
  mount('bubbles-loading')
  comicService.fetchWeek(date).done(function(comics) {
    mount('bubbles-week', comics)
  })
}

/**
 * Comic route definition
 * @param  string id
 * @param  string resource
 * @param  int    page
 */
routes.comics = function(id, resource, page) {
  mount('bubbles-loading')
  if (id && parseInt(id)) {
    comicService.fetchComic(id, resource, page).done(function(comic) {
      mount('bubbles-comic', comic)
    })
  } else {
    mount('bubbles-error')
  }
}

/**
 * Serie route definition
 * @param  string id
 * @param  string resource
 * @param  int    page
 */
routes.series = function(id, resource, page) {
  mount('bubbles-loading')
  if (id && parseInt(id)) {
    serieService.fetchComics(id, resource, page).done(function(comics) {
      mount('bubbles-series', comics)
    })
  } else {
    mount('bubbles-error')
  }
}

/**
 * Creator route definition
 * @param  string id
 * @param  string resource
 * @param  int    page
 */
routes.creators = function(id, resource, page) {
  mount('bubbles-loading')
  if (id && parseInt(id)) {
    creatorService.fetchComics(id, resource, page).done(function(comics) {
      mount('bubbles-creators', comics)
    })
  } else {
    mount('bubbles-error')
  }
}

/**
 * Character route definition
 * @param  string id
 * @param  string resource
 * @param  int    page
 */
routes.characters = function(id, resource, page) {
  mount('bubbles-loading')
  if (id && parseInt(id)) {
    characterService.fetchComics(id, resource, page).done(function(comics) {
      mount('bubbles-characters', comics)
    })
  } else {
    mount('bubbles-error')
  }
}

/**
 * Event route definition
 * @param  string id
 * @param  string resource
 * @param  int    page
 */
routes.events = function(id, resource, page) {
  mount('bubbles-loading')
  if (id && parseInt(id)) {
    eventService.fetchComics(id, resource, page).done(function(comics) {
      mount('bubbles-events', comics)
    })
  } else {
    mount('bubbles-error')
  }
}

/**
 * Search route definition
 * @param  string id
 * @param  string resource
 * @param  int    page
 */
routes.search = function(id, resource, page) {
  mount('bubbles-loading')
  switch (id) {
    case 'series':
      searchService.fetchSeries(id, resource, page).done(function(search) {
        mount('bubbles-search', search)
      })
      break
    case 'comics':
      searchService.fetchComics(id, resource, page).done(function(search) {
        mount('bubbles-search', search)
      })
      break
    case 'creators':
      searchService.fetchCreators(id, resource, page).done(function(search) {
        mount('bubbles-search', search)
      })
      break
    case 'characters':
      searchService.fetchCharacters(id, resource, page).done(function(search) {
        mount('bubbles-search', search)
      })
      break
    case 'events':
      searchService.fetchEvents(id, resource, page).done(function(search) {
        mount('bubbles-search', search)
      })
      break
    default:
      mount('bubbles-error')
  }
}

/**
 * About route definition
 * @return Object
 */
routes.about = function() {
    mount('bubbles-about')
    document.title = Translator.trans('about.title')
}


/***********
 * Run app *
 ***********/

 if (pageTitle === undefined) {
   var pageTitle = undefined
 }

/**
 * Synchronize title mixin
 * @type Object
 */
 var TitleMixin = {
   init: function() {
     this.on('mount', function(options) {
       syncTitle(options)
     })
   }
 }

/**
 * Share TitleMixin mixin
 * @param  string 'TitleMixin'
 * @param  Object TitleMixin
 */
riot.mixin('TitleMixin', TitleMixin)

/**
 * Mount all the tags !!!!
 * @param  string '*'
 */
riot.mount('*')

/**
 * Changes the browser URL and notifies all the listeners assigned with
 * @param  Object handler
 */
riot.route(handler)

/**
 * Start listening the url changes.
 * @param  bool
 */
riot.route.start(true)
