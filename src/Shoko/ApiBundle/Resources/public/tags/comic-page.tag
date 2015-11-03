<bubbles-comic>
  <div class="row">
    <div class="col-xs-12 col-sm-4 col-md-3 ">
      <a class="lightbox" href="{ comic.thumbnail.path }/detail.{ comic.thumbnail.extension }">
        <img
          id="incredible_{ comic.id.comicId }"
          class="img-thumbnail"
          src="{ comic.thumbnail.path.useHttps() }/portrait_incredible.{ comic.thumbnail.extension }"
          alt="{ title }"
          height="324"
          width="216"
        />
      </a>
    </div>
    <div class="col-xs-12 col-sm-8 col-md-9">
      <h2>
        { comic.title.title() } <small><a href="{ comic.urls[0].url }" target="_blank" title="{ comic.title.title() }"> <span class="glyphicon glyphicon-link" aria-hidden="true"></span></a></small>
      </h2>
      <table class="table table-condensed">
        <tr>
          <td><strong>Published</strong></td>
          <td><a href="#week/{ moment(comic.dates[0].date).format('DD-MM-YYYY') }" title="Released as of { moment(comic.dates[0].date).format('MMM Do, YYYY') }">{ moment(comic.dates[0].date).format('MMMM Do, YYYY') }</a></td>
        </tr>

        <tr>
          <td><strong>Serie</strong></td>
          <td><a href="#series/{ comic.series.resourceURI.resourceURIToId() }" class="title" title="{ comic.series.name.title() }">{ comic.series.name.title() }</a></td>
        </tr>

        <tr if={ comic.events.items.length }>
          <td><strong>Events</strong></td>
          <td>
            <span each={ comic.events.items }>
              <a href="#events/{ resourceURI.resourceURIToId() }" class="title label label-primary" title="{ name.title() }">{ name.title() }</a>
            </span>
          </td>
        </tr>

        <tr each={ comic.creators.items }>
          <td><strong>{ role.title() }</strong></td>
          <td><a href="#creators/{ resourceURI.resourceURIToId() }" class="title" title="{ name.title() }">{ name.title() }</a></td>
        </tr>

        <tr if={ comic.characters.items.length }>
          <td><strong>Characters</strong></td>
          <td>
            <span each={ comic.characters.items }>
              <a href="#characters/{ resourceURI.resourceURIToId() }" class="title label label-primary" title="{ name.title() }">{ name.title() }</a>
            </span>
          </td>
        </tr>
      </table>

      <p><raw comic={comic}/></p>
    </div>
  </div>

  <script>
    this.comic = this.opts.comic.results[0]
    $(document).on('click', '.lightbox', function(e) {
      $(this).magnificPopup({type:'image'})
      e.preventDefault()
    })
    this.on('update', function() {
      $(document).on('click', 'a.title[title]', function() {
        pageTitle = $(this).attr('title')
      })
    })
  </script>
</bubbles-comic>

<raw>
  var self = this,
      updateHTML = function(){
        self.root.innerHTML = self.opts.comic.description || ""
      }
  updateHTML()
  this.on("updated", updateHTML)
</raw>
