<bubbles-search>
  <h1 class="text-center">{ opts.title }</h1>
  <character-list if={ opts.entity == 'characters' } characters={ opts.comics.results }></character-list>
  <comic-list if={ opts.entity == 'comics' } comics={ opts.comics.results }></comic-list>
  <creator-list if={ opts.entity == 'creators' } creators={ opts.comics.results }></creator-list>
  <event-list if={ opts.entity == 'events' } events={ opts.comics.results }></event-list>
  <serie-list if={ opts.entity == 'series' } series={ opts.comics.results }></serie-list>
  <div id=pagination class="text-center"></div>

  <script>
    $(this.pagination).twbsPagination({
      totalPages: opts.comics.total !== 0 ? Math.ceil(opts.comics.total / 10) : 1,
      href: '#search/'+opts.entity+'/'+opts.q+'/{{number}}'
    }).find('ul.pagination > li > a').each(function(i, el) {
      $(el).addClass('title').attr('title', pageTitle)
    })
    this.on('update', function() {
      $(document).on('click', 'a.title[title]', function() {
        pageTitle = $(this).attr('title')
      })
    })
  </script>
</bubbles-search>
