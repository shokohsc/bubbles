String.prototype.resourceURIToId = function() {
  var string = this.toString()
  return string.split('/').reverse()[0]
}
String.prototype.title = function() {
  var string = this.toString().toLowerCase()
  return string.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g, function($charOne){
    return $charOne.toUpperCase()
  })
}
String.prototype.useHttps = function() {
  var string = this.toString()
  return string.replace('http://', 'https://')
}

var mount = function(mount, tag, route, id, page, options) {
  var protocol = 'http://',
      host     = location.host+'/',
      endpoint = 'api/'+route+'/',
      url      = protocol+host+endpoint+id,
      url      = (page === undefined) ? url : url+'/'+page,
      url      = (options === undefined) ? url : url+'/'+options

  $.ajax({
    url: url
  }).done(function(data) {
    riot.mount(mount, tag, data)
    if (pageTitle !== undefined ) {
      $('h1.text-center').text(pageTitle)
      document.title = pageTitle
    }
    pageTitle = undefined
  }).fail(function(xhr, text, error) {
    riot.mount('div#content', 'bubbles-error', xhr)
  })
}

var routing = function(collection, id, page, options) {
  riot.mount('div#content', 'bubbles-loading')
  if (collection === 'about') {
    riot.mount('div#content', 'bubbles-about')
    document.title = 'About Bubbles'
  } else if (collection === 'comics') {
    mount('div#content', 'bubbles-comic', collection, id)
  } else if (collection === 'week') {
    mount('div#content', 'bubbles-week', 'comics/'+collection, id)
  } else if (collection === 'search') {
    mount('div#content', 'bubbles-search', collection, id, page, options)
  } else if (collection !== '') {
    mount('div#content', 'bubbles-'+collection, collection, id+'/comics', page)
  } else {
    mount('div#content', 'bubbles-week', 'comics/week')
  }
}

if (pageTitle === undefined) {
  var pageTitle = undefined
}

riot.mount('bubbles-navbar')
riot.mount('div#content', 'bubbles-loading')
riot.mount('bubbles-footer')
riot.route(routing)
riot.route.exec(routing)
