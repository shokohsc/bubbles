<creator-list>
  <div class="text-center">
    <div each={ this.opts.creators } class="list">
        <a href="#creators/{ id.creatorId }" class="title" title="{ fullName.title() }">
          <creator-image creator={ this.item }></creator-image>
        </a>
        <strong>
          <a href="#creators/{ id.creatorId }" class="title" title="{ fullName.title() }">{ fullName.title() }</a>
        </strong>
    </div>
</creator-list>
