<creator-list>
  <div class="text-center">
    <div each={ this.opts.creators } class="list">
        <a href="#creators/{ id.creatorId }/comics" class="title" title="{ fullName.title() }">
          <creator-image creator={ item }></creator-image>
        </a>
        <strong>
          <a href="#creators/{ id.creatorId }/comics" class="title" title="{ fullName.title() }">{ fullName.title() }</a>
        </strong>
    </div>
  </div>
</creator-list>
