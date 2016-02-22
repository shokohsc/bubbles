<event-list>
  <div class="text-center">
    <div each={ this.opts.events } class="list">
        <a href="#events/{ id.eventId }/comics" class="title" title="{ title.title() }">
          <event-image event={ item }></event-image>
        </a>
        <strong>
          <a href="#events/{ id.eventId }/comics" class="title" title="{ title.title() }">{ title.title() }</a>
        </strong>
    </div>
  </div>
</event-list>
