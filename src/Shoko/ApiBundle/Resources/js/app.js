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

/******************
 * Routes Handler *
 ******************/

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
 * Mount tag
 * @param  string tag
 * @param  string options
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

/**
 * Synchronize title
 */
function syncTitle() {
  if (pageTitle !== undefined ) {
    $('h1.text-center').text(pageTitle)
    document.title = pageTitle
  }
  pageTitle = undefined
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
  }

  /**
   * Serve ajax call
   * @param  string id
   * @param  string page
   * @param  string query
   * @return Promise
   */
  serve(id, page, query) {
    var self      = this,
        url       = this.protocol+this.host+this.endpoint,
        url       = (id === undefined) ? url : url+'/'+id,
        url       = (page === undefined) ? url : url+'/'+page,
        url       = (query === undefined) ? url : url+'?'+query

    return $.ajax({
      url: url
    }).fail(function(xhr, text, error) {
      mount('bubbles-error', xhr)
    })
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
   * @param  string id
   * @return Promise
   */
  fetchWeek(date) {
    this.url = this.url+'/'+date

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

  /**
   * Fetch serie comics
   * @param string id
   * @param string resource
   * @param int    page
   * @return Promise
   */
  fetchComics(id, resource, page) {
    return this.serve(id+'/'+resource, page)
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

  /**
   * Fetch creator comics
   * @param string id
   * @param string resource
   * @param int    page
   * @return Promise
   */
  fetchComics(id, resource, page) {
    return this.serve(id+'/'+resource, page)
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

  /**
   * Fetch character comics
   * @param string id
   * @param string resource
   * @param int    page
   * @return Promise
   */
  fetchComics(id, resource, page) {
    return this.serve(id+'/'+resource, page)
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

  /**
   * Fetch character comics
   * @param string id
   * @param string resource
   * @param int    page
   * @return Promise
   */
  fetchComics(id, resource, page) {
    return this.serve(id+'/'+resource, page)
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
   * Fetch comics
   * @param string id
   * @param string resource
   * @param int    page
   * @return Promise
   */
  fetchComics(id, resource, page) {
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


/*********************
 * Route definitions *
 *********************/

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
 * @param  string id
 * @param  string resource
 * @param  int    page
 */
routes.home = function(id, resource, page) {
  mount('bubbles-loading')
  comicService.fetchWeek(id).done(function(comics) {
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
      syncTitle()
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
      syncTitle()
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
      syncTitle()
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
      syncTitle()
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
      syncTitle()
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
        syncTitle()
      })
      break;
    case 'comics':
      searchService.fetchComics(id, resource, page).done(function(search) {
        mount('bubbles-search', search)
        syncTitle()
      })
      break;
    case 'creators':
      searchService.fetchCreators(id, resource, page).done(function(search) {
        mount('bubbles-search', search)
        syncTitle()
      })
      break;
    case 'characters':
      searchService.fetchCharacters(id, resource, page).done(function(search) {
        mount('bubbles-search', search)
        syncTitle()
      })
      break;
    case 'events':
      searchService.fetchEvents(id, resource, page).done(function(search) {
        mount('bubbles-search', search)
        syncTitle()
      })
      break;
    default:
      mount('bubbles-error')
  }
}

/**
 * About route definition
 * @param  string id
 * @param  string resource
 * @param  int    page
 * @return Object
 */
routes.about = function(id, resource, page) {
    mount('bubbles-about')
    document.title = Translator.trans('about.title')
}



/***********
 * Run app *
 ***********/

 if (pageTitle === undefined) {
   var pageTitle = undefined
 }

riot.mount('*')
riot.route(handler)
riot.route.start(true)






// var mount = function(mount, tag, route, id, page, options) {
//   var protocol = location.protocol+'//',
//       host     = location.host+'/',
//       endpoint = 'api/'+route+'/',
//       url      = protocol+host+endpoint+id,
//       url      = (page === undefined) ? url : url+'/'+page,
//       url      = (options === undefined) ? url : url+'/'+options
//
//   $.ajax({
//     url: url
//   }).done(function(data) {
//     riot.mount(mount, tag, data)
//     if (pageTitle !== undefined ) {
//       $('h1.text-center').text(pageTitle)
//       document.title = pageTitle
//     }
//     pageTitle = undefined
//   }).fail(function(xhr, text, error) {
//     riot.mount('div#content', 'bubbles-error', xhr)
//   })
// }
//
// var routing = function(collection, id, page, options) {
//   riot.mount('div#content', 'bubbles-loading')
//   if (collection === 'about') {
//     riot.mount('div#content', 'bubbles-about')
//     document.title = Translator.trans('about.title')
//   } else if (collection === 'comics') {
//     mount('div#content', 'bubbles-comic', collection, id)
//   } else if (collection === 'week') {
//     mount('div#content', 'bubbles-week', 'comics/'+collection, id)
//   } else if (collection === 'search') {
//     mount('div#content', 'bubbles-search', collection, id, page, options)
//   } else if (collection !== '' && collection !== 'week') {
//     mount('div#content', 'bubbles-'+collection, collection, id+'/comics', page)
//   } else {
//     mount('div#content', 'bubbles-week', 'comics/week')
//   }
// }
//
// if (pageTitle === undefined) {
//   var pageTitle = undefined
// }
//
// //google-analytics
// (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
// (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
// m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
// })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
// ga('create', 'UA-55046906-2', 'auto');
// ga('send', 'pageview');
//
// riot.mount('bubbles-navbar')
// riot.mount('div#content', 'bubbles-loading')
// riot.mount('bubbles-footer')
// riot.route.start(true)
// riot.route(routing)
// riot.route.exec(routing)
