<bubbles-navbar>
  <nav class="navbar navbar-default navbar-inverse" role="navigation">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#" title="Bubbles">Bubbles</a>
      </div>

      <div id="navbar" class="navbar-collapse collapse" role="navigation">
        <ul class="nav navbar-nav">
          <li>
            <a href="#about" title="About">About</a>
          </li>
          <li>
            <form class="navbar-form navbar-right" id="search" onsubmit={ submit }>
                <div class="input-group">
                  <div class="input-group-btn search-panel">
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <span id="search_concept">Series</span> <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu" role="menu">
                          <li><a href="#comics" title="comics">Comic</a></li>
                          <li><a href="#series" title="series">Series</a></li>
                          <li><a href="#characters" title="characters">Character</a></li>
                          <li><a href="#creators" title="creators">Creator</a></li>
                          <li><a href="#events" title="events">Event</a></li>
                      </ul>
                  </div>
                  <input type="text" class="form-control" name="q" required="true" value="" placeholder="Search...">
                  <input type="hidden" id="entity" name="entity" class="category" value="series">
                  <span class="input-group-btn">
                      <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                  </span>
               </div>
            </form>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <script>
    $(document).on('click', '.search-panel .dropdown-menu a',  function(e) {
      e.preventDefault()
      var param   = $(this).attr("href").replace("#",""),
          concept = $(this).text()
      $('.search-panel span#search_concept').text(concept)
      $('#entity').val(param.toLowerCase())
    })
    submit(e) {
      location.hash = '#search/'+this.entity.value+'/'+this.q.value
    }
  </script>
</bubbles-navbar>
