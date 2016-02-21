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
function handler(collection, id, action) {
  var fn = routes[collection || 'home']
  fn ? fn(id, action) : mount('bubbles-error')
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
    }).fail(function(xhr) {
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
   * @return Object
   */
  fetchWeek(date) {
    this.url = this.url+'/'+date

    return this.serve('week', date)
  }

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
   * Fetch serie
   * @param string id
   * @return Object
   */
  fetchSerie(id) {
    return this.serve(id)
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
   * Fetch creator
   * @param string id
   * @return Object
   */
  fetchCreator(id) {
    return this.serve(id)
  }

  /**
   * Fetch creators
   * @return Object
   */
  fetchCreators() {
    var query = riot.route.query()
        query = $.param(query)

    return this.serve(undefined, undefined, query)
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
 * Home route definition
 * @param  string id
 * @param  string action
 * @return Object
 */
routes.home = function(id, action) {
  mount('bubbles-loading')
  comicService.fetchWeek(id).done(function(week) {
    mount('bubbles-week', week)
  })
}

/**
 * Serie route definition
 * @param  string id
 * @param  string action
 * @return Object
 */
routes.series = function(id, action) {
  mount('bubbles-loading')
  if (id && !id.match(/=/)) {
    serieService.fetchSerie(id).done(function(serie) {
      mount('bubbles-serie', {serie: serie})
    })
  } else {
    mount('bubbles-error')
  }
}

/**
 * Creator route definition
 * @param  string id
 * @param  string action
 * @return Object
 */
routes.creators = function(id, action) {
  mount('bubbles-loading')
  if (id && !id.match(/=/)) {
    creatorService.fetchCreator(id).done(function(creator) {
      mount('bubbles-creator', {creator: creator})
    })
  } else {
    creatorService.fetchCreators().done(function(creators) {
      mount('bubbles-creators', {creators: creators})
    })
  }
}

/**
 * About route definition
 * @param  string id
 * @param  string action
 * @return Object
 */
routes.about = function(id, action) {
    mount('bubbles-about')
}



/***********
 * Run app *
 ***********/
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
