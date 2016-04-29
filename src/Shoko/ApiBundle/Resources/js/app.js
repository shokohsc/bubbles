"use strict"

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
  if (id) {
    searchService.fetchResults(id, resource, page).done(function(search) {
      mount('bubbles-search', search)
    })
  } else {
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
