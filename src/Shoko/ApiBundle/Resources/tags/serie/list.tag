<serie-list>
  <div class="text-center">
    <div each={ this.opts.series } class="list">
        <a href="#series/{ id.serieId }/comics" class="title" title="{ title.title() }">
          <serie-image serie={ item }></serie-image>
        </a>
        <strong>
          <a href="#series/{ id.serieId }/comics" class="title" title="{ title.title() }">{ title.title() }</a>
        </strong>
    </div>
  </div>
</serie-list>
