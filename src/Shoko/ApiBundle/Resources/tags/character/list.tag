<character-list>
  <div class="text-center">
    <div each={ this.opts.characters } class="list">
        <a href="#characters/{ id.characterId }" class="title" title="{ name.title() }">
          <character-image character={ this.item }></character-image>
        </a>
        <strong>
          <a href="#characters/{ id.characterId }" class="title" title="{ name.title() }">{ name.title() }</a>
        </strong>
    </div>
</character-list>
