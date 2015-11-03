<event-list>
  <div class="text-center">
    <div each={ this.opts.events } class="list">
        <a href="#events/{ id.eventId }" class="title" title="{ name.title() }">
          <event-image event={ this.item }></event-image>
        </a>
        <strong>
          <a href="#events/{ id.eventId }" class="title" title="{ name.title() }">{ name.title() }</a>
        </strong>
    </div>
</event-list>
