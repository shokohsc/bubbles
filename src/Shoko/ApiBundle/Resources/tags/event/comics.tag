<bubbles-events>
  <h1 class="text-center"></h1>
  <comic-list comics={ opts.comics.results }></comic-list>
  <div id=pagination class="text-center"></div>

  <script>
    $(this.pagination).twbsPagination({
      totalPages: Math.ceil(opts.comics.total / 10),
      href: '#events/'+opts.eventId+'/comics/{{number}}'
    }).find('ul.pagination > li > a').each(function(i, el) {
      $(el).addClass('title').attr('title', pageTitle)
    })
  </script>
</bubbles-events>
