<bubbles-week>
  <h1 class="text-center">{ opts.title.title() }</h1>
  <comic-list comics={ opts.comics.results }></comic-list>
  <bubbles-pagination-week date={ opts.date }></bubbles-pagination-week>

  <script>
    this.on('update', function() {
      document.title = opts.title.title()
    })
  </script>
</bubbles-week>
