riot.tag2('bubbles-about', '<h1>{Translator.trans(\'about.what.question\')}</h1> <p>{Translator.trans(\'about.what.answer\')}</p> <h3>{Translator.trans(\'about.who.question\')}</h3> <p>{Translator.trans(\'about.who.answer\')}</p> <h3>{Translator.trans(\'about.why.question\')}</h3> <p><raw html="{Translator.trans(\'about.why.answer\')}"></raw></p> <h3>{Translator.trans(\'about.thanks.main\')}</h3> <p>{Translator.trans(\'about.thanks.iThank\')} <ul class="list-unstyled"> <li><strong><a href="//heroku.com">Heroku</a></strong> {Translator.trans(\'about.thanks.heroku\')}</li> <li><strong><a href="//marvel.com">Marvel</a></strong> {Translator.trans(\'about.thanks.marvel\')}</li> <li><strong><a href="//github.com/redpanda">Redpanda</a></strong> {Translator.trans(\'about.thanks.redpanda\')}</li> <li><strong><a href="//sensiolabs.com/">Sensiolabs</a></strong> {Translator.trans(\'about.thanks.sensiolabs\')}</li> <li><strong><a href="//riotjs.com/">RiotJs</a></strong> {Translator.trans(\'about.thanks.riotjs\')}</li> <li><strong><a href="//getbootstrap.com/">Twitter Bootstrap</a></strong> {Translator.trans(\'about.thanks.bootstrap\')}</li> <li><strong><a href="//fontlibrary.org/">Open Font Library</a></strong> {Translator.trans(\'about.thanks.openfonts\')}</li> </ul> </p>', '', '', function(opts) {
}, '{ }');

riot.tag2('bubbles-characters', '<h1 class="text-center"></h1> <comic-list comics="{opts.comics.results}"></comic-list> <div id="pagination" class="text-center"></div>', '', '', function(opts) {
    $(this.pagination).twbsPagination({
      totalPages: Math.ceil(opts.comics.total / 10),
      href: '#characters/'+opts.characterId+'/{{number}}'
    }).find('ul.pagination > li > a').each(function(i, el) {
      $(el).addClass('title').attr('title', pageTitle)
    })
}, '{ }');

riot.tag2('character-image', '<img id="incredible_{id.characterId}" class="img-thumbnail" riot-src="{thumbnail.path.useHttps()}/portrait_incredible.{thumbnail.extension}" alt="{title}" height="324" width="216">', '', '', function(opts) {
    this.character = opts
}, '{ }');

riot.tag2('character-list', '<div class="text-center"> <div each="{this.opts.characters}" class="list"> <a href="#characters/{id.characterId}" class="title" title="{name.title()}"> <character-image character="{this.item}"></character-image> </a> <strong> <a href="#characters/{id.characterId}" class="title" title="{name.title()}">{name.title()}</a> </strong> </div>', '', '', function(opts) {
}, '{ }');

riot.tag2('comic-image', '<img id="incredible_{id.comicId}" class="img-thumbnail" riot-src="{thumbnail.path.useHttps()}/portrait_incredible.{thumbnail.extension}" alt="{title}" height="324" width="216">', '', '', function(opts) {
    this.comic = opts
}, '{ }');

riot.tag2('comic-list', '<div class="text-center"> <div each="{this.opts.comics}" class="list"> <a href="#comics/{id.comicId}" class="title" title="{title.title()}"> <comic-image comic="{item}"></comic-image> </a> <strong> <a href="#comics/{id.comicId}" class="title" title="{title.title()}">{title.title()}</a> </strong> </div>', '', '', function(opts) {
}, '{ }');

riot.tag2('bubbles-comic', '<div class="row"> <div class="col-xs-12 col-sm-4 col-md-3 "> <a data-lightbox="cover" data-title="{comic.title.title()}" href="{comic.thumbnail.path.useHttps()}/detail.{comic.thumbnail.extension}"> <img id="incredible_{comic.id.comicId}" class="img-thumbnail" riot-src="{comic.thumbnail.path.useHttps()}/portrait_incredible.{comic.thumbnail.extension}" alt="{title}" height="324" width="216"> </a> </div> <div class="col-xs-12 col-sm-8 col-md-9"> <h2> {comic.title.title()} <small><a href="{comic.urls[0].url}" target="_blank" title="{comic.title.title()}"> <span class="glyphicon glyphicon-link" aria-hidden="true"></span></a></small> </h2> <table class="table table-condensed"> <tr> <td><strong>{Translator.trans(\'comic.published\')}</strong></td> <td><a href="#week/{moment(comic.dates[0].date).format(\'DD-MM-YYYY\')}" title="Released as of {moment(comic.dates[0].date).format(\'MMM Do, YYYY\')}">{moment(comic.dates[0].date).format(\'MMMM Do, YYYY\')}</a></td> </tr> <tr> <td><strong>{Translator.trans(\'comic.series\')}</strong></td> <td><a href="#series/{comic.series.resourceURI.resourceURIToId()}" class="title" title="{comic.series.name.title()}">{comic.series.name.title()}</a></td> </tr> <tr if="{comic.events.items.length}"> <td><strong>{Translator.trans(\'comic.events\')}</strong></td> <td> <span each="{comic.events.items}"> <a href="#events/{resourceURI.resourceURIToId()}" class="title label label-primary" title="{name.title()}">{name.title()}</a> </span> </td> </tr> <tr each="{comic.creators.items}"> <td><strong>{{ Translator.trans(role) }}</strong></td> <td><a href="#creators/{resourceURI.resourceURIToId()}" class="title" title="{name.title()}">{name.title()}</a></td> </tr> <tr if="{comic.characters.items.length}"> <td><strong>{Translator.trans(\'comic.characters\')}</strong></td> <td> <span each="{comic.characters.items}"> <a href="#characters/{resourceURI.resourceURIToId()}" class="title label label-primary" title="{name.title()}">{name.title()}</a> </span> </td> </tr> </table> <p><raw html="{comic.description}"></raw></p> </div> </div>', '', '', function(opts) {
    this.comic = this.opts.comic.results[0]
    this.on('mount', function() {
      document.title = undefined !== pageTitle ? pageTitle : $(this.root).find('h2').text().trim()
      $(document).on('click', 'a.title[title]', function() {
        pageTitle = $(this).attr('title')
      })
    })
}, '{ }');

riot.tag2('raw', '', '', '', function(opts) {
  var self = this,
      updateHTML = function(){
        self.root.innerHTML = self.opts.html || ""
      }
  updateHTML()
  this.on("updated", updateHTML)
});

riot.tag2('bubbles-creators', '<h1 class="text-center"></h1> <comic-list comics="{opts.comics.results}"></comic-list> <div id="pagination" class="text-center"></div>', '', '', function(opts) {
    $(this.pagination).twbsPagination({
      totalPages: Math.ceil(opts.comics.total / 10),
      href: '#creators/'+opts.creatorId+'/{{number}}'
    }).find('ul.pagination > li > a').each(function(i, el) {
      $(el).addClass('title').attr('title', pageTitle)
    })
}, '{ }');

riot.tag2('creator-image', '<img id="incredible_{id.creatorId}" class="img-thumbnail" riot-src="{thumbnails.path.useHttps()}/portrait_incredible.{thumbnails.extension}" alt="{title}" height="324" width="216">', '', '', function(opts) {
    this.creator = opts
}, '{ }');

riot.tag2('creator-list', '<div class="text-center"> <div each="{this.opts.creators}" class="list"> <a href="#creators/{id.creatorId}" class="title" title="{fullName.title()}"> <creator-image creator="{this.item}"></creator-image> </a> <strong> <a href="#creators/{id.creatorId}" class="title" title="{fullName.title()}">{fullName.title()}</a> </strong> </div>', '', '', function(opts) {
}, '{ }');

riot.tag2('bubbles-error', '<div class="jumbotron row"> <div class="col-lg-12"> <h1>{status}</h1> <p><span class="glyphicon glyphicon-exclamation-sign"></span> {message}</p> <p> <a class="btn btn-primary btn-lg" onclick="window.history.back()" role="button"> <span class="glyphicon glyphicon-arrow-left"></span> {Translator.trans(\'error.backward\')} </a> <a class="btn btn-primary btn-lg" href="#home" role="button"> <span class="glyphicon glyphicon-home"></span> {Translator.trans(\'home\')} </a> </p> </div> </div>', '', '', function(opts) {
    this.status = opts.status > 400 ? opts.status : 500
    this.message = opts.status > 400 ? opts.responseJSON : 'Deadpool hit his ultimate, server down !'
    this.on('update', function() {
      document.title = Translator.trans('error.500.title')
    })
}, '{ }');

riot.tag2('bubbles-events', '<h1 class="text-center"></h1> <comic-list comics="{opts.comics.results}"></comic-list> <div id="pagination" class="text-center"></div>', '', '', function(opts) {
    $(this.pagination).twbsPagination({
      totalPages: Math.ceil(opts.comics.total / 10),
      href: '#events/'+opts.eventId+'/{{number}}'
    }).find('ul.pagination > li > a').each(function(i, el) {
      $(el).addClass('title').attr('title', pageTitle)
    })
}, '{ }');

riot.tag2('event-image', '<img id="incredible_{id.eventId}" class="img-thumbnail" riot-src="{thumbnail.path.useHttps()}/portrait_incredible.{thumbnail.extension}" alt="{title}" height="324" width="216">', '', '', function(opts) {
    this.event = opts
}, '{ }');

riot.tag2('event-list', '<div class="text-center"> <div each="{this.opts.events}" class="list"> <a href="#events/{id.eventId}" class="title" title="{name.title()}"> <event-image event="{this.item}"></event-image> </a> <strong> <a href="#events/{id.eventId}" class="title" title="{name.title()}">{name.title()}</a> </strong> </div>', '', '', function(opts) {
}, '{ }');

riot.tag2('bubbles-footer', '<footer> <p class="text-center"> <small>{Translator.trans(\'credits.provided\')} <a href="//marvel.com/" title="Marvel">Marvel</a>. <span class="glyphicon glyphicon-copyright-mark" aria-hidden="true"></span> 2014 Marvel.<br> {Translator.trans(\'credits.powered\')} <span class="glyphicon glyphicon-copyright-mark" aria-hidden="true"></span> <a href="//symfony.com/" title="Symfony">Symfony</a>, <span class="glyphicon glyphicon-copyright-mark" aria-hidden="true"></span> <a href="//riotjs.com/" title="RiotJs">RiotJs</a> & <span class="glyphicon glyphicon-copyright-mark" aria-hidden="true"></span> <a href="//getbootstrap.com/" title="Bootstrap">Bootstrap</a>.</small> </p> </footer>', '', '', function(opts) {
}, '{ }');

riot.tag2('bubbles-loading', '<div class="spinner"> <div class="spinner-container container1"> <div class="circle1"></div> <div class="circle2"></div> <div class="circle3"></div> <div class="circle4"></div> </div> <div class="spinner-container container2"> <div class="circle1"></div> <div class="circle2"></div> <div class="circle3"></div> <div class="circle4"></div> </div> <div class="spinner-container container3"> <div class="circle1"></div> <div class="circle2"></div> <div class="circle3"></div> <div class="circle4"></div> </div> </div>', '', '', function(opts) {
});

riot.tag2('bubbles-navbar', '<nav class="navbar navbar-default navbar-inverse" role="navigation"> <div class="container"> <div class="navbar-header"> <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"> <span class="sr-only">{Translator.trans(\'navbar.navigation\')}</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button> <a class="navbar-brand" href="#home" title="{Translator.trans(\'comics.this_week\')}">Bubbles</a> </div> <div id="navbar" class="navbar-collapse collapse" role="navigation"> <ul class="nav navbar-nav"> <li> <a href="#about" title="About">{Translator.trans(\'navbar.about\')}</a> </li> <li> <form class="navbar-form navbar-right" id="search" onsubmit="{submit}"> <div class="input-group"> <div class="input-group-btn search-panel"> <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> <span id="search_concept">{Translator.trans(\'navbar.search.entity.series\')}</span> <span class="caret"></span> </button> <ul class="dropdown-menu" role="menu"> <li><a href="#comics" title="comics">{Translator.trans(\'navbar.search.entity.comic\')}</a></li> <li><a href="#series" title="series">{Translator.trans(\'navbar.search.entity.series\')}</a></li> <li><a href="#characters" title="characters">{Translator.trans(\'navbar.search.entity.character\')}</a></li> <li><a href="#creators" title="creators">{Translator.trans(\'navbar.search.entity.creator\')}</a></li> <li><a href="#events" title="events">{Translator.trans(\'navbar.search.entity.event\')}</a></li> </ul> </div> <input type="text" class="form-control" name="q" required="true" value="" placeholder="{Translator.trans(\'navbar.search.input\')}..."> <input type="hidden" id="entity" name="entity" class="category" value="series"> <span class="input-group-btn"> <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button> </span> </div> </form> </li> </ul> </div> </div> </nav>', '', '', function(opts) {
    $(document).on('click', '.search-panel .dropdown-menu a',  function(e) {
      e.preventDefault()
      var param   = $(this).attr("href").replace("#",""),
          concept = $(this).text()
      $('.search-panel span#search_concept').text(concept)
      $('#entity').val(param.toLowerCase())
    })
    this.submit = function(e) {
      location.hash = '#search/'+this.entity.value+'/'+this.q.value
    }.bind(this)
}, '{ }');

riot.tag2('bubbles-pagination-week', '<div class="row"> <nav class="text-center"> <ul class="pagination"> <li> <a href="#week/{moment(opts.date.date).subtract(1, \'month\').format(\'DD-MM-YYYY\')}" title="{Translator.trans(\'comics.release\')} {moment(opts.date.date).subtract(1, \'month\').format(\'MMM Do, YYYY\')}">-{Translator.trans(\'pagination.month\')}</a> </li> <li> <a href="#week/{moment(opts.date.date).subtract(1, \'week\').format(\'DD-MM-YYYY\')}" title="{Translator.trans(\'comics.release\')} {moment(opts.date.date).subtract(1, \'week\').format(\'MMM Do, YYYY\')}">-{Translator.trans(\'pagination.week\')}</a> </li> <li> <a href="#week/{moment(opts.date.date).add(1, \'week\').format(\'DD-MM-YYYY\')}" title="{Translator.trans(\'comics.release\')} {moment(opts.date.date).add(1, \'week\').format(\'MMM Do, YYYY\')}">+{Translator.trans(\'pagination.week\')}</a> </li> <li> <a href="#week/{moment(opts.date.date).add(1, \'month\').format(\'DD-MM-YYYY\')}" title="{Translator.trans(\'comics.release\')} {moment(opts.date.date).add(1, \'month\').format(\'MMM Do, YYYY\')}">+{Translator.trans(\'pagination.month\')}</a> </li> </ul> </nav> </div>', '', '', function(opts) {
}, '{ }');

riot.tag2('bubbles-search', '<h1 class="text-center">{opts.title}</h1> <character-list if="{opts.entity == \'characters\'}" characters="{opts.comics.results}"></character-list> <comic-list if="{opts.entity == \'comics\'}" comics="{opts.comics.results}"></comic-list> <creator-list if="{opts.entity == \'creators\'}" creators="{opts.comics.results}"></creator-list> <event-list if="{opts.entity == \'events\'}" events="{opts.comics.results}"></event-list> <serie-list if="{opts.entity == \'series\'}" series="{opts.comics.results}"></serie-list> <div id="pagination" class="text-center"></div>', '', '', function(opts) {
    $(this.pagination).twbsPagination({
      totalPages: Math.ceil(opts.comics.total / 10),
      href: '#search/'+opts.entity+'/'+opts.q+'/{{number}}'
    })
    this.on('update', function() {
      $(document).on('click', 'a.title[title]', function() {
        pageTitle = $(this).attr('title')
      })
    })
}, '{ }');

riot.tag2('bubbles-series', '<h1 class="text-center"></h1> <comic-list comics="{opts.comics.results}"></comic-list> <div id="pagination" class="text-center"></div>', '', '', function(opts) {
    $(this.pagination).twbsPagination({
      totalPages: Math.ceil(opts.comics.total / 10),
      href: '#series/'+opts.serieId+'/{{number}}'
    }).find('ul.pagination > li > a').each(function(i, el) {
      $(el).addClass('title').attr('title', pageTitle)
    })
}, '{ }');

riot.tag2('serie-image', '<img id="incredible_{id.serieId}" class="img-thumbnail" riot-src="{thumbnail.path.useHttps()}/portrait_incredible.{thumbnail.extension}" alt="{title}" height="324" width="216">', '', '', function(opts) {
    this.serie = opts
}, '{ }');

riot.tag2('serie-list', '<div class="text-center"> <div each="{this.opts.series}" class="list"> <a href="#series/{id.serieId}" class="title" title="{title.title()}"> <serie-image serie="{this.item}"></serie-image> </a> <strong> <a href="#series/{id.serieId}" class="title" title="{title.title()}">{title.title()}</a> </strong> </div>', '', '', function(opts) {
}, '{ }');

riot.tag2('bubbles-week', '<h1 class="text-center">{opts.title.title()}</h1> <comic-list comics="{opts.comics.results}"></comic-list> <bubbles-pagination-week date="{opts.date}"></bubbles-pagination-week>', '', '', function(opts) {
    this.on('update', function() {
      document.title = opts.title.title()
    })
}, '{ }');
