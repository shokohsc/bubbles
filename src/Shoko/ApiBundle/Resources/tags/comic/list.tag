<comic-list>
  <div class="text-center">
    <div each={ this.opts.comics } class="list">
        <a href="#comics/{ id.comicId }" class="title" title="{ title.title() }">
          <comic-image comic={ item }></comic-image>
        </a>
        <strong>
          <a href="#comics/{ id.comicId }" class="title" title="{ title.title() }">{ title.title() }</a>
        </strong>
    </div>
  </div>
</comic-list>
