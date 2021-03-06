<bubbles-characters>
  <h1 class="text-center"></h1>
  <comic-list comics={ opts.comics.results }></comic-list>
  <div id=pagination class="text-center"></div>

  <script>
    $(this.pagination).twbsPagination({
      totalPages: opts.comics.total !== 0 ? Math.ceil(opts.comics.total / 10) : 1,
      href: '#characters/'+opts.characterId+'/comics/{{number}}'
    }).find('ul.pagination > li > a').each(function(i, el) {
      $(el).addClass('title').attr('title', pageTitle)
    })
    this.mixin(TitleMixin)
  </script>
</bubbles-characters>
