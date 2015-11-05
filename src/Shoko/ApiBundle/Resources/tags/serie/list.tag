<serie-list>
  <div class="text-center">
    <div each={ this.opts.series } class="list">
        <a href="#series/{ id.serieId }" class="title" title="{ title.title() }">
          <serie-image serie={ this.item }></serie-image>
        </a>
        <strong>
          <a href="#series/{ id.serieId }" class="title" title="{ title.title() }">{ title.title() }</a>
        </strong>
    </div>
</serie-list>
